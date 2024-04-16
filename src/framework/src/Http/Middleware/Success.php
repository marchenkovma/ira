<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Middleware\MiddlewareInterface;
use Aruka\Http\Request;
use Aruka\Http\Response;

class Success implements MiddlewareInterface
{

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return new Response('Hello World');
    }
}
