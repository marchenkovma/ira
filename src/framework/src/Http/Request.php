<?php

declare(strict_types=1);

namespace Aruka\Http;

use Aruka\Sessions\SessionInterface;

class Request
{
    private SessionInterface $session;
    public function __construct(
        // Данные из глобального массива $_GET
        private array $getParams,
        // Данные из глобального массива $_POST
        private array $postData,
        // Данные из глобального массива $_COOKIE
        private array $cookies,
        // Данные из глобального массива $_FILES
        private array $files,
        // Данные из глобального массива $_SERVER
        private array $server,
    ) {
    }

    // Получает данные из глобальных массивов PHP:
    // $_GET, $_POST, $_COOKIE, $_FILES, $_SERVER
    public static function createFromGlobals(): static
    {
        // Информация о разнице static и self
        // https://ru.stackoverflow.com/a/494850
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    // Возвращает URI запроса без GET-параметров
    public function getPath(): string
    {
        // С помощью strtok убирает все что идет после символа ?
        return strtok($this->server['REQUEST_URI'], '?');
    }

    // Возвращает HTTP-метод запроса
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function input(string $key, mixed $default = null)
    {
        return $this->postData[$key] ?? $default;
    }
}
