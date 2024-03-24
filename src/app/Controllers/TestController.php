<?php

declare(strict_types=1);

namespace B24Cruder\Controllers;

use Aruka\Http\Response;

class TestController
{
    public function index(): Response
    {
        $headers[] = ('Content-Type: application/json');
        $content = json_encode([
            'STATUS' => 'OK',
            'MESSAGE' => 'Hello World!',
        ]);

        return new Response($content, 200,$headers);
    }
}
