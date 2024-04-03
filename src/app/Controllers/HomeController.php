<?php

declare(strict_types=1);

namespace Ira\Controllers;

use Aruka\Controller\AbstractController;
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
        return $this->render('home.html.twig', [
            'youTubeChannel' => $this->youTubeService->getChannelUrl(),
            ]);
    }
}
