<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Try to get from environment variables first (Wasmer), fallback to .env file (local)
        $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? null);
        $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? null);
        $dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? null);
        $username = getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? null);
        $password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? null);
        
        // Fallback to .env file for local development
        if (!$host && file_exists(__DIR__ . '/../../.env')) {
            $dotenv = parse_ini_file(__DIR__ . '/../../.env');
            $host = $dotenv['DB_HOST'] ?? null;
            $port = $dotenv['DB_PORT'] ?? null;
            $dbname = $dotenv['DB_NAME'] ?? null;
            $username = $dotenv['DB_USERNAME'] ?? null;
            $password = $dotenv['DB_PASSWORD'] ?? null;
        }

        if (!$host || !$dbname || !$username) {
            throw new Exception("Database configuration missing");
        }

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
