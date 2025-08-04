<?php

declare(strict_types=1);

namespace DAIMON\Core;

use PDO;
use PDOException;
use PDOStatement;
use Psr\Log\LoggerInterface;

/**
 * Database handler class using PDO
 */
class Database
{
    /**
     * @var PDO The PDO instance
     */
    private static ?PDO $instance = null;

    /**
     * @var LoggerInterface Logger instance for query logging
     */
    private static ?LoggerInterface $logger = null;

    /**
     * @var array Database configuration
     */
    private static array $config = [
        'host' => 'localhost',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]
    ];

    /**
     * Private constructor to prevent creating a new instance
     */
    private function __construct() {}

    /**
     * Clone method to prevent cloning of the instance
     */
    private function __clone() {}

    /**
     * Set the database configuration
     *
     * @param array $config Database configuration
     * @return void
     */
    public static function setConfig(array $config): void
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Set the logger instance
     *
     * @param LoggerInterface $logger Logger instance
     * @return void
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * Get the PDO instance (singleton pattern)
     *
     * @return PDO
     * @throws PDOException If connection fails
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::$config['host'],
                self::$config['database'],
                self::$config['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
                    self::$config['options']
                );
            } catch (PDOException $e) {
                self::logError('Database connection failed: ' . $e->getMessage());
                throw $e;
            }
        }

        return self::$instance;
    }

    /**
     * Execute a query with parameters
     *
     * @param string $sql SQL query
     * @param array $params Parameters for prepared statement
     * @return PDOStatement
     * @throws PDOException If query execution fails
     */
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $start = microtime(true);
        $pdo = self::getInstance();
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            self::logQuery($sql, $params, $start);
            
            return $stmt;
        } catch (PDOException $e) {
            self::logError('Query failed: ' . $e->getMessage(), [
                'sql' => $sql,
                'params' => $params
            ]);
            throw $e;
        }
    }

    /**
     * Begin a transaction
     *
     * @return bool True on success, false on failure
     */
    public static function beginTransaction(): bool
    {
        try {
            self::logDebug('Beginning transaction');
            return self::getInstance()->beginTransaction();
        } catch (PDOException $e) {
            self::logError('Begin transaction failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Commit a transaction
     *
     * @return bool True on success, false on failure
     */
    public static function commit(): bool
    {
        try {
            self::logDebug('Committing transaction');
            return self::getInstance()->commit();
        } catch (PDOException $e) {
            self::logError('Commit failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Rollback a transaction
     *
     * @return bool True on success, false on failure
     */
    public static function rollback(): bool
    {
        try {
            self::logDebug('Rolling back transaction');
            return self::getInstance()->rollBack();
        } catch (PDOException $e) {
            self::logError('Rollback failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the last insert ID
     *
     * @param string|null $name Name of the sequence object
     * @return string
     */
    public static function lastInsertId(?string $name = null): string
    {
        return self::getInstance()->lastInsertId($name);
    }

    /**
     * Log a query
     *
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @param float $start Start time of the query
     * @return void
     */
    private static function logQuery(string $sql, array $params, float $start): void
    {
        if (self::$logger === null) {
            return;
        }

        $time = microtime(true) - $start;
        $context = [
            'params' => $params,
            'time' => round($time * 1000, 2) . 'ms'
        ];

        self::$logger->debug($sql, $context);
    }

    /**
     * Log an error
     *
     * @param string $message Error message
     * @param array $context Additional context
     * @return void
     */
    private static function logError(string $message, array $context = []): void
    {
        if (self::$logger !== null) {
            self::$logger->error($message, $context);
        }
    }

    /**
     * Log a debug message
     *
     * @param string $message Debug message
     * @param array $context Additional context
     * @return void
     */
    private static function logDebug(string $message, array $context = []): void
    {
        if (self::$logger !== null) {
            self::$logger->debug($message, $context);
        }
    }
}
