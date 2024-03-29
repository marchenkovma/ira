<?php

declare(strict_types=1);

use Aruka\Routing\Route;
use Ira\Controllers\IndexController;
use Ira\Controllers\InfoController;
use Ira\Controllers\TestController;

return [
    // (Метод, Маршрут,[Путь до класса контроллера, Метод контроллера])
    Route::get('/', [IndexController::class, 'index']),
    Route::get('/info', [InfoController::class, 'index']),
    Route::get('/test', [TestController::class, 'index']),
    Route::post('/test', [TestController::class, 'index']),
    Route::put('/test', [TestController::class, 'index']),
    Route::delete('/test', [TestController::class, 'index']),
    Route::patch('/test', [TestController::class, 'index']),
    //Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    /*Route::get('/hi/{name}', function ($name) {
        return new \B24Cruder\framework\src\Container\Response("Hello, $name");
    }),*/
];
