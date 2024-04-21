<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        StartSession::class,
        Authenticate::class,
        RouterDispatch::class
    ];

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function handle(Request $request): Response
    {
        // Если нет middleware-классов для выполнения, возвращает ответ по умолчанию
        // Ответ должен был быть возвращен до того, как список станет пустым
        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }

        // Получает следующий middleware-класс для выполнения
        $middlewareClass = array_shift($this->middleware);

        $middleware = $this->container->get($middlewareClass);
        $response = $middleware->process($request, $this);

        return $response;
    }
}
