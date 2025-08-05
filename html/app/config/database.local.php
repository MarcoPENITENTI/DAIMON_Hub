<?php

declare(strict_types=1);

/**
 * Local Database Configuration
 * 
 * This file contains sensitive database credentials and should NOT be committed
 * to version control. It's included in .gitignore.
 * 
 * Credenziali per il database di DAIMON Hub
 */

return [
    'default' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'hub_daimon',
        'username'  => 'mc',
        'password'  => 'mc.armoniaDel8',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ],
];
