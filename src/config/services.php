<?php declare(strict_types=1);

use Aruka\Console\Application;
use Aruka\Console\Commands\MigrateCommand;
use Aruka\Controller\AbstractController;
use Aruka\Dbal\ConnectionFactory;
use Aruka\Http\Kernel;
use Aruka\Console\Kernel as ConsoleKernel;
use Aruka\Http\Middleware\RequestHandler;
use Aruka\Http\Middleware\RequestHandlerInterface;
use Aruka\Http\Middleware\RouterDispatch;
use Aruka\Routing\Router;
use Aruka\Routing\RouterInterface;
use Aruka\Sessions\Session;
use Aruka\Sessions\SessionInterface;
use Aruka\Template\TwigFactory;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;

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

$container->add('framework-commands-namespace', new StringArgument('Aruka\\Console\\Commands\\'));

// Регистрирует переменную окружения приложения
$container->add('APP_ENV', new StringArgument($appEnv));

// Создает и настраивает маршрутизатор
$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

// Создает и настраивает ядро веб-приложения
$container->add(Kernel::class)
    ->addArguments([
        RouterInterface::class, // Внедряет маршрутизатор
        $container, // Внедряет контейнер
        RequestHandlerInterface::class // Внедряет обработчик запросов
    ]);

/*
// Создает и настраивает загрузчик Twig
// Метод addShared позволяет использовать уже созданный объект и не создает новый
$container->addShared('twig-loader', Filesystemloader::class)
    ->addArgument(new StringArgument($viewPath));

// Создает и настраивает Twig
$container->addShared('twig', Environment::class)
    ->addArgument('twig-loader');
*/

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewPath),
        SessionInterface::class
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

// Внедряет контейнер в класс AbstractController
$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)
    ->addArgument($container);

// Создает и настраивает ядро консольного приложения
$container->add(ConsoleKernel::class)
    ->addArgument($container) // Внедряет контейнер
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container
    ]);

// Возвращает контейнер со всеми настроенными сервисами
return $container;
