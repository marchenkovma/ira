<?php

declare(strict_types=1);

namespace Ira\Controllers;

use Aruka\Controllers\AbstractController;
use Aruka\Http\Response;
use Ira\Services\YouTubeService;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly YouTubeService $youTubeService,
    ) {
    }

    public function index(): Response
    {
        $this->container->get('twig');
        $content = '<h1>Hello, World!</h1><br>';
        $content .= "<a href =\"{$this->youTubeService->getChannelUrl()}\">YouTube</a>";

        return new Response($content);
    }
}
