#!/usr/bin/env php
<?php

declare(strict_types=1);

// Carica l'autoloader personalizzato
require_once __DIR__ . '/src/core/autoload.php';

use DAIMON\Database\MigrationManager\MigrationManager;

// Load configuration
$config = require __DIR__ . '/config/database.php';

// Create database connection
try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['default']['host'],
        $config['default']['database'],
        $config['default']['charset']
    );

    $pdo = new PDO(
        $dsn,
        $config['default']['username'],
        $config['default']['password'],
        $config['default']['options'] ?? []
    );

    // Create migration manager
    $migrationManager = new MigrationManager($pdo, __DIR__ . '/database/migrations');

    // Handle command
    $command = $argv[1] ?? 'migrate';
    $steps = (int)($argv[2] ?? 1);

    switch ($command) {
        case 'migrate':
            $applied = $migrationManager->migrate();
            if (empty($applied)) {
                echo "No new migrations to run.\n";
            } else {
                echo "Successfully ran the following migrations:\n";
                foreach ($applied as $migration) {
                    echo "- $migration\n";
                }
            }
            break;

        case 'rollback':
            $rolledBack = $migrationManager->rollback($steps);
            if (empty($rolledBack)) {
                echo "No migrations to rollback.\n";
            } else {
                echo "Successfully rolled back the following migrations:\n";
                foreach ($rolledBack as $migration) {
                    echo "- $migration\n";
                }
            }
            break;

        case 'status':
            $status = $migrationManager->getStatus();
            $maxNameLength = max(array_map('strlen', array_column($status, 'migration'))) + 2;
            
            echo str_pad('Migration', $maxNameLength) . "Batch  Status\n";
            echo str_repeat('-', $maxNameLength + 15) . "\n";
            
            foreach ($status as $item) {
                echo sprintf(
                    '%-' . $maxNameLength . 's %-7s %s\n',
                    $item['migration'],
                    $item['batch'] ?? '--',
                    $item['status'] === 'ran' ? '✓ Ran' : '⏳ Pending'
                );
            }
            break;

        default:
            echo "Usage: php migrate.php [command] [steps]\n";
            echo "Commands:\n";
            echo "  migrate    Run all pending migrations (default)\n";
            echo "  rollback   Rollback the last migration or [steps] migrations\n";
            echo "  status     Show migration status\n";
            exit(1);
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
