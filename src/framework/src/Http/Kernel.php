<?php

declare(strict_types=1);

namespace Aruka\Http;

use Aruka\Http\Exceptions\HttpException;
use Aruka\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    // Обрабывает запрос и возвращает ответ
    public function handle(Request $request)
    {
        try {
            [$routerHandler, $vars] = $this->router->dispatch($request);

            // Вызывает callback-функция с массивом параметров
            $response = call_user_func_array($routerHandler, $vars);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }
        return $response;
    }
}
