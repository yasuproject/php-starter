<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dotenv = parse_ini_file(__DIR__ . '/../../.env');
        
        $host = $dotenv['DB_HOST'];
        $port = $dotenv['DB_PORT'];
        $dbname = $dotenv['DB_NAME'];
        $username = $dotenv['DB_USERNAME'];
        $password = $dotenv['DB_PASSWORD'];

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
