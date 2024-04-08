<?php

declare(strict_types=1);

namespace Aruka\Routing;

use Aruka\Controller\AbstractController;
use Aruka\Http\Exceptions\MethodNotAllowedException;
use Aruka\Http\Exceptions\RouteNotFoundException;
use Aruka\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use League\Container\Container;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes;
    public function dispatch(Request $request, Container $container): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            // Т.к. переменная $controller содержит весь путь
            // App\Controllers\HomeController, благодаря использованию
            // HomeController:class в web.php
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request);
            }

            $handler = [$controller, $method];
        }

        //$vars['request'] = $request;

        return [$handler, $vars];
    }

    public function registerRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    // Возвращает информацию о совпадение URI и метода из запроса
    // с маршрутом и методом из описанных маршрутов
    private function extractRouteInfo(Request $request): array
    {
        // Функция simpleDispatcher описывает маршруты
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                // Аналог $collector->addRoute($route[0], $route[1], $route[2]);
                $collector->addRoute(...$route);
            }
        });

        // Проверяет совпадение URI и метода из запроса
        // с маршрутом и методом из описанных маршрутов
        // Возвращает массив из 3-х элементов
        // 1. Получает результат проверки совпадения маршрута:
        // NOT FOUND = 0, FOUND = 1, METHOD NOT ALLOWED = 2
        // 2. Контроллер и метод
        // 3. Параметры (id) из маршрута /user/{id:\d+}
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );

        // $status = $routeInfo[0];
        // $handler = $routeInfo[1];
        // $vars = $routeInfo[2];
        // [$status, $handler, $var] = $routeInfo;

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                // $routeInfo[1] - обработчик
                // $routeInfo[2] - vars
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                // Объединяет элементы массива в строку
                $allowedMethods = implode(', ', $routeInfo[1]);
                $exception = new MethodNotAllowedException("Supported HTTP methods: $allowedMethods");
                $exception->setStatusCode(405);
                throw $exception;
            default:
                $exception = new RouteNotFoundException('Route not found');
                $exception->setStatusCode(404);
                throw $exception;
        }
    }
}
