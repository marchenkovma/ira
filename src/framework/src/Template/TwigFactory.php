<?php

namespace Aruka\Template;

use Aruka\Sessions\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string $viewPath,
        private SessionInterface $session
    ) {
    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->viewPath);

        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
