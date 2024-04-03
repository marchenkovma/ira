<?php

namespace Aruka\Controller;

use Aruka\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');

        $response ??= new Response();

        $content = $twig->render($view, $parameters);

        $response->setContent($content);

        return $response;
    }
}
