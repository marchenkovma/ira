<?php

namespace Aruka\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{

    public function __construct(
        private readonly string $databaseUrl
    )
    {
    }

    public function create(): Connection
    {
        return $connection = DriverManager::getConnection([
            'url' => $this->databaseUrl
        ]);
    }

}
