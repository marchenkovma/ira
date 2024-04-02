<?php

namespace Ira;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private string $driver;
    private string $host;
    private string $database;
    private string $username;
    private string $password;

    private function __construct()
    {
        $this->driver = $_ENV['DB_DRIVER'] ?? 'mysql';
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->database = $_ENV['DB_DATABASE'] ?? 'default';
        $this->username = $_ENV['DB_USERNAME'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? 'root';

        $dsn = "{$this->driver}:host={$this->host};dbname={$this->database}";

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            self::$instance = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            new self();
        }

        return self::$instance;
    }
}
