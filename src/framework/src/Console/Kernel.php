<?php

namespace Aruka\Console;

use Aruka\Console\Commands\MigrateCommand;
use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    )
    {
    }

    public function handle(): int
    {
        // 1. Регистрация команд с помощью контейнера
        $this->registerCommands();

        // 2. Запуск команды
        $status = $this->application->run();

        // 3. Возвращаем код
        return 0;
    }

    private function registerCommands(): void
    {

        // Регистрация системных команд

        // 1. Получить все файлы из папки Commands
        $commandsFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $namespace = $this->container->get('framework-commands-namespace');

        // 2. Пройти по всем файлам
        foreach ($commandsFiles as $commandFile) {
            // Если это не файл
            if (! $commandFile->isFile()) {
                continue;
            }

            // 3. Получить имя класса команды

            // Получает полный путь для класса команды
            $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME); // Получает имя команды без расширения

            // Проверяет, является ли класс в $command подклассом CommandInterface
            if (is_subclass_of($command, CommandInterface::class)) {
                // 4. Если это подкласс CommandInterface -> добавить в контейнер (используя имя команды в качестве ID)
                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();

                $this->container->add("console:$name", $command);
            }
        }

        // Регистрация пользовательских команд
    }
}
