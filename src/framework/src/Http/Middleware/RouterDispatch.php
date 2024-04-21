<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;
use Aruka\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routerHandler, $vars] = $this->router->dispatch($request, $this->container);

         // Вызывает callback-функцию с массивом параметров
        $response = call_user_func_array($routerHandler, $vars);

        return $response;
    }
}
