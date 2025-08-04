<?php

declare(strict_types=1);

namespace DAIMON\Core;

use PDO;
use PDOException;

class Config
{
    private static ?self $instance = null;
    private PDO $pdo;
    private array $cache = [];
    private bool $cacheEnabled = true;
    private bool $initialized = false;

    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getInstance(PDO $pdo = null): self
    {
        if (self::$instance === null && $pdo === null) {
            throw new \RuntimeException('Config class not initialized. Please provide a PDO instance on first call.');
        }

        if (self::$instance === null) {
            self::$instance = new self($pdo);
        }

        return self::$instance;
    }

    public function init(): void
    {
        if ($this->initialized) {
            return;
        }

        // Load all configurations into cache
        $this->loadAllConfigs();
        $this->initialized = true;
    }

    public function get(string $key, $default = null, ?string $namespace = 'app')
    {
        $cacheKey = $this->getCacheKey($namespace, $key);

        if ($this->cacheEnabled && array_key_exists($cacheKey, $this->cache)) {
            return $this->cache[$cacheKey];
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT `value`, `type`, `is_encrypted` 
                FROM `config` 
                WHERE `namespace` = :namespace AND `key` = :key
            ");
            
            $stmt->execute([
                ':namespace' => $namespace,
                ':key' => $key
            ]);

            $config = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($config === false) {
                return $default;
            }

            $value = $config['value'];

            // Decrypt if needed
            if ($config['is_encrypted']) {
                $value = $this->decrypt($value);
            }

            // Convert to proper type
            $value = $this->castToType($value, $config['type']);

            // Cache the value
            $this->cache[$cacheKey] = $value;

            return $value;
        } catch (PDOException $e) {
            error_log("Config error: " . $e->getMessage());
            return $default;
        }
    }

    public function set(string $key, $value, string $type = 'string', string $namespace = 'app', 
                       string $description = '', bool $isPublic = false, bool $isEncrypted = false, 
                       string $accessLevel = 'admin'): bool
    {
        try {
            $currentValue = $this->get($key, null, $namespace);
            $value = $this->prepareValueForStorage($value, $type, $isEncrypted);
            
            if ($currentValue === null) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO `config` 
                    (`namespace`, `key`, `value`, `type`, `access_level`, `is_public`, `is_encrypted`, `description`)
                    VALUES (:namespace, :key, :value, :type, :access_level, :is_public, :is_encrypted, :description)
                ");
            } else {
                $stmt = $this->pdo->prepare("
                    UPDATE `config` 
                    SET `value` = :value, 
                        `type` = :type,
                        `access_level` = :access_level,
                        `is_public` = :is_public,
                        `is_encrypted` = :is_encrypted,
                        `description` = :description,
                        `updated_at` = CURRENT_TIMESTAMP
                    WHERE `namespace` = :namespace AND `key` = :key
                
                ");
            }

            $result = $stmt->execute([
                ':namespace' => $namespace,
                ':key' => $key,
                ':value' => $value,
                ':type' => $type,
                ':access_level' => $accessLevel,
                ':is_public' => $isPublic ? 1 : 0,
                ':is_encrypted' => $isEncrypted ? 1 : 0,
                ':description' => $description
            ]);

            // Update cache
            $cacheKey = $this->getCacheKey($namespace, $key);
            $this->cache[$cacheKey] = $this->castToType(
                $isEncrypted ? $this->decrypt($value) : $value,
                $type
            );

            return $result;
        } catch (PDOException $e) {
            error_log("Config error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(?string $namespace = null, ?string $accessLevel = null): array
    {
        $query = "SELECT * FROM `config` WHERE 1=1";
        $params = [];

        if ($namespace !== null) {
            $query .= " AND `namespace` = :namespace";
            $params[':namespace'] = $namespace;
        }

        if ($accessLevel !== null) {
            // This assumes you have a way to compare access levels
            // For simplicity, we're doing an exact match here
            $query .= " AND `access_level` = :access_level";
            $params[':access_level'] = $accessLevel;
        }

        $query .= " ORDER BY `namespace`, `key`";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        $configs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $value = $row['value'];
            
            if ($row['is_encrypted']) {
                $value = $this->decrypt($value);
            }
            
            $configs[] = [
                'namespace' => $row['namespace'],
                'key' => $row['key'],
                'value' => $this->castToType($value, $row['type']),
                'type' => $row['type'],
                'access_level' => $row['access_level'],
                'is_public' => (bool)$row['is_public'],
                'description' => $row['description'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }

        return $configs;
    }

    public function delete(string $key, string $namespace = 'app'): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM `config` 
                WHERE `namespace` = :namespace AND `key` = :key
            
            ");
            
            $result = $stmt->execute([
                ':namespace' => $namespace,
                ':key' => $key
            ]);

            // Clear from cache
            $cacheKey = $this->getCacheKey($namespace, $key);
            unset($this->cache[$cacheKey]);

            return $result;
        } catch (PDOException $e) {
            error_log("Config error: " . $e->getMessage());
            return false;
        }
    }

    public function clearCache(): void
    {
        $this->cache = [];
    }

    public function enableCache(bool $enabled = true): void
    {
        $this->cacheEnabled = $enabled;
        if (!$enabled) {
            $this->clearCache();
        }
    }

    private function loadAllConfigs(): void
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM `config`");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['value'];
                
                if ($row['is_encrypted']) {
                    $value = $this->decrypt($value);
                }
                
                $cacheKey = $this->getCacheKey($row['namespace'], $row['key']);
                $this->cache[$cacheKey] = $this->castToType($value, $row['type']);
            }
        } catch (PDOException $e) {
            // Table might not exist yet, ignore for now
            error_log("Failed to load configs: " . $e->getMessage());
        }
    }

    private function getCacheKey(string $namespace, string $key): string
    {
        return "{$namespace}.{$key}";
    }

    private function prepareValueForStorage($value, string $type, bool $isEncrypted = false): string
    {
        // Convert to string representation
        switch ($type) {
            case 'boolean':
                $value = $value ? '1' : '0';
                break;
            case 'number':
                $value = (string)$value;
                break;
            case 'json':
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            case 'text':
            case 'string':
            default:
                $value = (string)$value;
        }

        // Encrypt if needed
        if ($isEncrypted) {
            $value = $this->encrypt($value);
        }

        return $value;
    }

    private function castToType($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case 'boolean':
                return (bool)$value;
            case 'number':
                return is_numeric($value) ? $value + 0 : 0;
            case 'json':
                return json_decode($value, true) ?? $value;
            case 'text':
            case 'string':
            default:
                return (string)$value;
        }
    }

    private function encrypt(string $data): string
    {
        // In a real application, use a proper encryption method
        // This is just a placeholder - replace with your encryption logic
        return base64_encode($data);
    }

    private function decrypt(string $data): string
    {
        // In a real application, use the corresponding decryption method
        // This is just a placeholder - replace with your decryption logic
        return base64_decode($data);
    }
}
