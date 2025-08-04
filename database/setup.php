<?php
declare(strict_types=1);

// Load configuration
$configFile = __DIR__ . '/../config/database.local.php';

if (!file_exists($configFile)) {
    die("Error: Database configuration file not found. Please create config/database.local.php\n");
}

$config = require $configFile;
$dbConfig = $config['default'];

// Create connection without database name first
$dsn = sprintf(
    'mysql:host=%s;charset=%s',
    $dbConfig['host'],
    $dbConfig['charset']
);

try {
    // Connect to MySQL server
    $pdo = new PDO(
        $dsn,
        $dbConfig['username'],
        $dbConfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    echo "✅ Connected to MySQL server successfully\n";

    // Create database if not exists
    $dbName = $dbConfig['database'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$dbName' checked/created\n";

    // Select the database
    $pdo->exec("USE `$dbName`");
    echo "✅ Using database '$dbName'\n";

    // Find and run migrations
    $migrationsDir = __DIR__ . '/migrations';
    $migrations = glob("$migrationsDir/*.sql");
    
    if (empty($migrations)) {
        die("❌ No migration files found in $migrationsDir\n");
    }

    sort($migrations);
    
    foreach ($migrations as $migration) {
        $migrationName = basename($migration);
        echo "🚀 Applying migration: $migrationName\n";
        
        $sql = file_get_contents($migration);
        $pdo->exec($sql);
        
        echo "✅ Applied migration: $migrationName\n";
    }
    
    echo "\n🎉 Database setup completed successfully!\n";
    echo "Default admin credentials:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n\n";
    echo "⚠️  IMPORTANT: Change the default password after first login!\n";
    
} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage() . "\n");
}
