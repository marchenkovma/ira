<?php

namespace Aruka\Console;

interface CommandInterface
{
    public function execute(array $parameters = []): int;
}
