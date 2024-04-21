<?php

use Aruka\Http\Kernel;
use Aruka\Http\Request;
use League\Container\Container;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/constants.php';

//$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
//$dotenv->load();

$request = Request::createFromGlobals();

$container = require BASE_PATH . '/config/services.php';

/** @var Container $container */
$kernel = $container->get(Kernel::class);

// Kernel получает Request, обрабатывает его handel() и возвращает Response
// Дальше Response методом send() возвращает результат в браузер
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
