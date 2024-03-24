<?php

declare(strict_types=1);

namespace B24Cruder\Controllers;

use Aruka\Http\Response;

class InfoController
{
    public function index(): Response
    {
        $content = phpinfo();

        return new Response($content);
    }
}
