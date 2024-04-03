<?php

declare(strict_types=1);

namespace Aruka\Http;

use Aruka\Http\Exceptions\HttpException;
use Aruka\Routing\RouterInterface;
use Doctrine\DBAL\Connection;
use Exception;
use League\Container\Container;

class Kernel
{
    private string $appEnv = 'production';

    public function __construct(
        private readonly RouterInterface $router,
        private readonly Container $container,
    ) {
        $this->appEnv = $container->get('APP_ENV');
    }

    // Обрабывает запрос и возвращает ответ
    public function handle(Request $request): Response
    {
        try {
            [$routerHandler, $vars] = $this->router->dispatch($request, $this->container);

            // Вызывает callback-функция с массивом параметров
            $response = call_user_func_array($routerHandler, $vars);
        } catch (Exception $e) {
            $response = $this->createExpectionResponse($e);
        }

        return $response;
    }

    private function createExpectionResponse(Exception $e): Response
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }

        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server error', 500);
    }
}
