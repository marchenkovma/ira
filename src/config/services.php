<?php

declare(strict_types=1);

use Aruka\Controllers\AbstractController;
use Aruka\Http\Kernel;
use Aruka\Routing\Router;
use Aruka\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');

// Application parameters

$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewPath = BASE_PATH . '/views';

// Application services

$container = new Container();

// Позволяет создавать объект класса со всеми зависимыми классами
$container->delegate(new ReflectionContainer(true));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class)
->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

// Метод addShared позволяет использовать уже созданный объект и не создает новый
$container->addShared('twig-loader', Filesystemloader::class)
    ->addArgument(new StringArgument($viewPath));

$container->addShared('twig', Environment::class)
    ->addArgument('twig-loader');

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

return $container;
