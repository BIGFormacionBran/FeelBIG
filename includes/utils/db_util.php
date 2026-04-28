<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $path = __DIR__ . '/../../.env';
        
        if (!file_exists($path)) {
            die("Error: El archivo .env no existe.");
        }

        $env = parse_ini_file($path);
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_NAME'] . ";charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Error de conexión PDO."); 
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}