<?php

namespace Aruka\Console\Commands;

use Aruka\Console\CommandInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    private const string MIGRATIONS_TABLE = 'migrations';


    public function __construct(
        private Connection $connection,
        private string $migrationsPath
    ) {
    }

    public function execute(array $parameters = []): int
    {
        try {
            $this->connection->setAutoCommit(false);

            // 1. Создает таблицу миграций (migrations), если таблица еще не существует
            $this->createMigrationsTable();

            $this->connection->beginTransaction();

            // 2. Получает $appliedMigrations (миграции, которые уже есть в таблице migrations)
            $appliedMigrations = $this->getAppliedMigrations();

            // 3. Получает $migrationFiles из папки миграций
            $migrationsFiles = $this->getMigrationFiles();

            // 4. Получает миграции для применения
            // Функция array_values используется чтобы индексы результирующего массива были с 0
            $migrationsToApply = array_values(array_diff($migrationsFiles, $appliedMigrations));

            $schema = new Schema();

            foreach ($migrationsToApply as $migration) {
                $migrationInstance = require_once $this->migrationsPath . "/$migration";
                $migrationInstance->up($schema);
                // 5. Создает SQL-запрос для миграций, которые еще не были выполнены

                // 6. Добавляет миграцию в базу данных

                $this->addMigration($migration);
            }

            // 7. Выполняет SQL-запрос
            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeStatement($sql);
            }

            $this->connection->commit();

        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->connection->setAutoCommit(true);

        return 0;
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP'
            ]);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            echo 'Migrations table created' . PHP_EOL;
        }
    }

    private function getAppliedMigrations(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->orderBy('id')
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        $filteredFiles = array_filter($migrationFiles, function ($fileName) {
            //return !in_array($fileName, ['.', '..']);
            return str_ends_with($fileName, '.php');
        });

        // Делает нумерацию в массиве с 0
        return array_values($filteredFiles);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS_TABLE)
            ->values([
                'migration' => ':migration'
            ])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }

}
