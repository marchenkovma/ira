<?php

declare(strict_types=1);

namespace Ira\Controllers;

use Aruka\Http\Response;

class IndexController
{
    public function index(): Response
    {
        $content = '<h1>Hello, I\'m a B24-Cruder!</h1>';

        return new Response($content);
    }
}
