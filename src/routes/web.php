<?php

declare(strict_types=1);

use Aruka\Http\Response;
use Aruka\Routing\Route;
use Ira\Controllers\HomeController;
use Ira\Controllers\InfoController;
use Ira\Controllers\PostController;
use Ira\Controllers\TestController;

return [
    // (Метод, Маршрут,[Путь до класса контроллера, Метод контроллера])
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::get('/hi/{name}', function ($name) {
        return new Response("Hello, $name");
    }),
    Route::get('/info', [InfoController::class, 'index']),

    Route::get('/test', [TestController::class, 'index']),
    Route::post('/test', [TestController::class, 'index']),
    Route::put('/test', [TestController::class, 'index']),
    Route::delete('/test', [TestController::class, 'index']),
    Route::patch('/test', [TestController::class, 'index']),
];
