<?php

/**
 * ReddiPH Database Configuration
 *
 * Automatically uses:
 * - XAMPP database when running localhost
 * - Hostinger database when uploaded to your domain
 */

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $hostName = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $isLocal =
            PHP_SAPI === 'cli' ||
            str_contains($hostName, 'localhost') ||
            str_contains($hostName, '127.0.0.1');

        if ($isLocal) {

            // ==========================================
            // XAMPP DATABASE
            // ==========================================

            $dbHost = getenv('DB_HOST') ?: 'localhost';
            $dbPort = getenv('DB_PORT') ?: '3306';
            $dbName = getenv('DB_NAME') ?: 'reddiph_db';
            $dbUser = getenv('DB_USER') ?: 'root';
            $dbPass = getenv('DB_PASS') ?: '';

        } else {

            // ==========================================
            // HOSTINGER DATABASE
            // ==========================================

            $dbHost = getenv('DB_HOST') ?: 'localhost';
            $dbPort = getenv('DB_PORT') ?: '3306';
            $dbName = getenv('DB_NAME') ?: 'u672637579_reddi_ph';
            $dbUser = getenv('DB_USER') ?: 'u672637579_reddi_ph';
            $dbPass = getenv('DB_PASS') ?: 'James101x';
        }

        $charset = 'utf8mb4';

        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$charset}";

        try {

            self::$connection = new PDO(
                $dsn,
                $dbUser,
                $dbPass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return self::$connection;

        } catch (PDOException $e) {

            error_log(
                'Database connection error: ' .
                $e->getMessage()
            );

            throw new RuntimeException(
                'Database connection failed: ' . $e->getMessage()
            );
        }
    }
}