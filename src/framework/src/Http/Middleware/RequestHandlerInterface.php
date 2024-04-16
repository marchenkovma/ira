<?php

namespace Aruka\Http\Middleware;

use Aruka\Http\Request;
use Aruka\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
