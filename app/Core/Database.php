<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require BASE_PATH . '/config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['name'],
                $config['charset']
            );

            try {
                self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } catch (PDOException $e) {
                // In production, never expose DB errors
                $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
                if ($debug) {
                    throw $e;
                }
                http_response_code(500);
                die('Database connection failed. Please try again later.');
            }
        }

        return self::$instance;
    }

    /** Convenience: get PDO directly */
    public static function get(): PDO
    {
        return self::getInstance();
    }

    /** Execute a prepared query, returns PDOStatement */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::get()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Fetch single row */
    public static function fetchOne(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    /** Fetch all rows */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /** Get last insert ID */
    public static function lastInsertId(): string
    {
        return self::get()->lastInsertId();
    }

    /** Begin transaction */
    public static function beginTransaction(): bool
    {
        return self::get()->beginTransaction();
    }

    /** Commit transaction */
    public static function commit(): bool
    {
        return self::get()->commit();
    }

    /** Roll back transaction */
    public static function rollBack(): bool
    {
        return self::get()->rollBack();
    }
}
