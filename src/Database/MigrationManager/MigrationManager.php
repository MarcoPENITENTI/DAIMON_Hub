<?php

declare(strict_types=1);

namespace DAIMON\Database\MigrationManager;

use PDO;
use PDOException;

class MigrationManager
{
    private PDO $pdo;
    private string $migrationsTable = 'migrations';
    private string $migrationsPath;

    public function __construct(PDO $pdo, string $migrationsPath)
    {
        $this->pdo = $pdo;
        $this->migrationsPath = rtrim($migrationsPath, '/') . '/';
        $this->ensureMigrationsTableExists();
    }

    private function ensureMigrationsTableExists(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `{$this->migrationsTable}` (
                `migration` VARCHAR(255) NOT NULL,
                `batch` INT NOT NULL,
                `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`migration`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function migrate(): array
    {
        $applied = [];
        $batch = $this->getNextBatchNumber();
        
        foreach ($this->getMigrationFiles() as $file) {
            $migrationName = basename($file, '.php');
            
            if ($this->isMigrated($migrationName)) {
                continue;
            }

            $migration = require $file;
            
            try {
                $this->pdo->beginTransaction();
                
                if (is_callable($migration['up'])) {
                    $migration['up']($this->pdo);
                }
                
                $this->markAsMigrated($migrationName, $batch);
                $this->pdo->commit();
                
                $applied[] = $migrationName;
            } catch (\Exception $e) {
                $this->pdo->rollBack();
                throw new \RuntimeException("Migration failed: {$migrationName}\n" . $e->getMessage());
            }
        }
        
        return $applied;
    }

    public function rollback(int $steps = 1): array
    {
        $rolledBack = [];
        $batch = $this->getLastBatchNumber();
        
        if ($batch === 0) {
            return [];
        }
        
        $migrations = $this->getMigrationsByBatch($batch);
        
        foreach (array_reverse($migrations) as $migration) {
            if ($steps-- <= 0) {
                break;
            }
            
            $file = $this->migrationsPath . $migration . '.php';
            
            if (!file_exists($file)) {
                throw new \RuntimeException("Migration file not found: {$file}");
            }
            
            $migrationContent = require $file;
            
            try {
                $this->pdo->beginTransaction();
                
                if (isset($migrationContent['down']) && is_callable($migrationContent['down'])) {
                    $migrationContent['down']($this->pdo);
                }
                
                $this->removeMigration($migration);
                $this->pdo->commit();
                
                $rolledBack[] = $migration;
            } catch (\Exception $e) {
                $this->pdo->rollBack();
                throw new \RuntimeException("Rollback failed: {$migration}\n" . $e->getMessage());
            }
        }
        
        return $rolledBack;
    }

    public function getStatus(): array
    {
        $migrated = $this->getMigratedMigrations();
        $files = $this->getMigrationFiles();
        
        $status = [];
        
        foreach ($files as $file) {
            $migration = basename($file, '.php');
            $status[] = [
                'migration' => $migration,
                'batch' => $migrated[$migration] ?? null,
                'status' => isset($migrated[$migration]) ? 'ran' : 'pending',
            ];
        }
        
        return $status;
    }

    private function getMigrationFiles(): array
    {
        $files = glob($this->migrationsPath . '*.php');
        
        if ($files === false) {
            return [];
        }
        
        sort($files);
        return $files;
    }

    private function getMigratedMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT migration, batch FROM {$this->migrationsTable} ORDER BY batch, migration");
        $migrations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $migrations[$row['migration']] = (int)$row['batch'];
        }
        
        return $migrations;
    }

    private function getMigrationsByBatch(int $batch): array
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM {$this->migrationsTable} WHERE batch = ? ORDER BY migration");
        $stmt->execute([$batch]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getLastBatchNumber(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return (int)$stmt->fetchColumn();
    }

    private function getNextBatchNumber(): int
    {
        return $this->getLastBatchNumber() + 1;
    }

    private function isMigrated(string $migration): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);
        return (bool)$stmt->fetchColumn();
    }

    private function markAsMigrated(string $migration, int $batch): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    private function removeMigration(string $migration): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);
    }
}
