<?php

declare(strict_types=1);

/**
 * Database Configuration
 * 
 * Copy this file to 'config/database.local.php' and update the values
 * according to your environment. The '.gitignore' file is already configured
 * to exclude the local configuration file from version control.
 */

return [
    'default' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'hub_daimon',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
        'options'   => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ],
];
