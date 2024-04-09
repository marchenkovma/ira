<?php

declare(strict_types=1);

namespace Ira\Controllers;

use Aruka\Controller\AbstractController;
use Aruka\Http\RedirectResponse;
use Aruka\Http\Response;
use Aruka\Sessions\SessionInterface;
use Ira\Enitites\Post;
use Ira\Services\PostService;

;
class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
        private SessionInterface $session
    )
    {
    }

    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('posts.html.twig', [
            'post' => $post
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store(): Response
    {
        $post = Post::create(
            $this->request->postData['title'],
            $this->request->postData['body'],
        );

        $post = $this->service->save($post);

        $this->session->setFlash('success', 'Пост успешно создан');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
