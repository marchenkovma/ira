<?php

declare(strict_types=1);

namespace Aruka\Http;

class Response
{
    public function __construct(
        // Контент
        private mixed $content = null,

        // HTTP-код ответа
        private int $statusCode = 200,

        // HTTP-заголовки ответа
        private readonly array $headers = []
    ) {
        http_response_code($this->statusCode);
    }

    // Возвращает контент
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->content;
    }
}
