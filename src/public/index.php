<?php

declare(strict_types=1);

use Aruka\Http\Kernel;
use Aruka\Http\Request;
use Aruka\Routing\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/constants.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = Request::createFromGlobals();

$router = new Router();

// Kernel получает Request, обрабатывает его handel() и возвращает Response
// Дальше Response методом send() возвращает результат в браузер
$kernel = new Kernel($router);
$response = $kernel->handle($request);
$response->send();

