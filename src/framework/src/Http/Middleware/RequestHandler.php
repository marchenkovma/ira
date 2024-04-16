<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        Authenticate::class,
        Success::class
    ];

    public function handle(Request $request): Response
    {
        // Если нет middleware-классов для выполнения, возвращает ответ по умолчанию
        // Ответ должен был быть возвращен до того, как список станет пустым
        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }

        // Получает следующий middleware-класс для выполнения
        /** @var MiddlewareInterface $middlewareClass */
        $middlewareClass = array_shift($this->middleware);

        // Создает новый экземпляр вызова процесса middleware на нем
        $response = (new $middlewareClass())->process($request, $this);

        return $response;
    }
}
