<?php declare(strict_types=1);

use Aruka\Controller\AbstractController;
use Aruka\Dbal\ConnectionFactory;
use Aruka\Http\Kernel;
use Aruka\Routing\Router;
use Aruka\Routing\RouterInterface;
use Doctrine\DBAL\Connection;
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
$databaseUrl = 'pdo-mysql://root:root@mysql:3306/default?charset=utf8mb4';

// Application services

$container = new Container();

// Включает автоматическое внедрение зависимостей через рефлексию
$container->delegate(new ReflectionContainer(true));

// Регистрирует переменную окружения приложения
$container->add('APP_ENV', new StringArgument($appEnv));

// Создает и настраивает маршрутизатор
$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

// Создает и настраивает ядро приложения с зависимостями
$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)   // Внедряет маршрутизатор
    ->addArgument($container);                  // Внедряет контейнер

// Создает и настраивает загрузчик Twig
// Метод addShared позволяет использовать уже созданный объект и не создает новый
$container->addShared('twig-loader', Filesystemloader::class)
    ->addArgument(new StringArgument($viewPath));

// Создает и настраивает Twig
$container->addShared('twig', Environment::class)
    ->addArgument('twig-loader');

// Внедряет контейнер в класс AbstractController
$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

// Возвращает контейнер со всеми настроенными сервисами
return $container;
