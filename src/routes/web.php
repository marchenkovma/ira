<?php

declare(strict_types=1);

use Aruka\Routing\Route;
use Ira\Controllers\HomeController;
use Ira\Controllers\PostController;

return [
    // (Метод, Маршрут,[Путь до класса контроллера, Метод контроллера])
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::post('/posts', [PostController::class, 'store']),
];
