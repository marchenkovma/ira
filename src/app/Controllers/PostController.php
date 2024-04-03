<?php

declare(strict_types=1);

namespace Ira\Controllers;

use Aruka\Controller\AbstractController;
use Aruka\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('posts.html.twig', [
            'postId' => $id
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }
}
