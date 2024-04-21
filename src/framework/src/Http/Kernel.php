<?php

declare(strict_types=1);

namespace Aruka\Http;

use Aruka\Http\Exceptions\HttpException;
use Aruka\Http\Middleware\RequestHandler;
use Aruka\Http\Middleware\RequestHandlerInterface;
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
        private RequestHandlerInterface $requestHandler
    ) {
        $this->appEnv = $container->get('APP_ENV');
    }

    // Обрабывает запрос и возвращает ответ
    public function handle(Request $request): Response
    {
        try {
           $response = $this->requestHandler->handle($request);
        } catch (Exception $e) {
            $response = $this->createExpectionResponse($e);
        }

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        // ?-> - null safe оператор
        $request->getSession()?->clearFlash();

        // Здесь можно очистить куки, закрыть соединение с сервисом и т.д.
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
