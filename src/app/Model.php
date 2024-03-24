<?php

declare(strict_types=1);

namespace B24Cruder;

use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
