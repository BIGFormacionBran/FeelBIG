<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Leemos el archivo .env manualmente para no depender de librerías externas
        $env = parse_ini_file(__DIR__ . '/../../.env');
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_NAME'] . ";charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            // No mostramos el error real al usuario para no filtrar el HOST o el USER
            die("Error crítico de infraestructura. Contacte con el administrador.");
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}