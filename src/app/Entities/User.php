<?php

namespace Ira\Entities;

class User
{
    public function __construct(
        private ?int $id,
        private ?string $name,
        private string $email,
        private string $password,
        private \DateTimeImmutable $createdAt
    )
    {
    }

    public static function create(string $email, string $password, \DateTimeImmutable $createdAt = null, string $name = null, int $id = null): static
    {
        return new static($id, $name, $email, $password, $createdAt ?? new \DateTimeImmutable());
    }
}
