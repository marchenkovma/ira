<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
