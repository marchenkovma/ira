<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;
use Aruka\Sessions\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}
