<?php

declare(strict_types=1);

namespace B24Cruder\Controllers;

use Aruka\Http\Response;

class IndexController
{
    public function index(): Response
    {
        $content = '<h1>Hello, I\'m a B24-Cruder3!</h1>';

        return new Response($content);
    }
}
