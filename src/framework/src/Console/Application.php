<?php

namespace Aruka\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        // 1. Получает имя команды
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        // 2. Возвращает исключение, если имя команды не указана
        if ($commandName === null) {
            throw new ConsoleException('Invalid console command');
        }

        // 3. Используем имя команды для получения объекта класса команды из контейнера
        /** @var $command CommandInterface */
        $command = $this->container->get("console:$commandName");

        // 4. Получает опцию и команды
        $args = array_slice($argv, 2);
        $options = $this->parseOptions($args);

        // 5. Выполняет команду, возвращая код статуса
        $status = $command->execute($options);

        return $status;
    }

    private function parseOptions(array $args): array
    {
       $options = [];

       foreach ($args as $arg) {
           if (str_starts_with($arg, '--')) {
               $option = explode('=', substr($arg, 2));
               $options[$option[0]] = $option[1] ?? true;
           }
       }

        return $options;
    }
}
