<?php

declare(strict_types=1);

namespace Aruka\Routing;

class Route
{
    public static function get(string $uri, callable|array $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, callable|array $handler): array
    {
        return ['POST', $uri, $handler];
    }

    public static function put(string $uri, callable|array $handler): array
    {
        return ['PUT', $uri, $handler];
    }

    public static function patch(string $uri, callable|array $handler): array
    {
        return ['PATCH', $uri, $handler];
    }

    public static function delete(string $uri, callable|array $handler): array
    {
        return ['DELETE', $uri, $handler];
    }
}
