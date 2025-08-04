<?php

return [
    'up' => function (PDO $pdo) {
        $pdo->exec("
            CREATE TABLE `config` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `namespace` VARCHAR(50) NOT NULL DEFAULT 'app',
                `key` VARCHAR(100) NOT NULL,
                `value` TEXT,
                `type` ENUM('string', 'number', 'boolean', 'json', 'text') NOT NULL DEFAULT 'string',
                `access_level` VARCHAR(50) DEFAULT 'admin' COMMENT 'Minimum access level required',
                `is_public` TINYINT(1) NOT NULL DEFAULT 0,
                `is_encrypted` TINYINT(1) NOT NULL DEFAULT 0,
                `description` TEXT,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `config_namespace_key_unique` (`namespace`, `key`),
                KEY `config_namespace_index` (`namespace`),
                KEY `config_access_level_index` (`access_level`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Insert default configurations
        $defaultConfigs = [
            [
                'namespace' => 'app',
                'key' => 'debug_mode',
                'value' => '1',
                'type' => 'boolean',
                'access_level' => 'admin',
                'is_public' => 0,
                'is_encrypted' => 0,
                'description' => 'Enable/disable debug mode',
            ],
            [
                'namespace' => 'app',
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'access_level' => 'admin',
                'is_public' => 1,
                'is_encrypted' => 0,
                'description' => 'Enable/disable maintenance mode',
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO `config` 
            (`namespace`, `key`, `value`, `type`, `access_level`, `is_public`, `is_encrypted`, `description`)
            VALUES (:namespace, :key, :value, :type, :access_level, :is_public, :is_encrypted, :description)
        ");

        foreach ($defaultConfigs as $config) {
            $stmt->execute($config);
        }
    },

    'down' => function (PDO $pdo) {
        $pdo->exec("DROP TABLE IF EXISTS `config`");
    }
];
