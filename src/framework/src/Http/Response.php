<?php

declare(strict_types=1);

namespace Aruka\Http;

class Response
{
    public function __construct(
        // Контент
        private string $content = '',
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

    public function setContent(string $content): Response
    {
        $this->content = $content;

        return $this;
    }

    public function getHeader(string $key): string
    {
        return $this->headers[$key];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
