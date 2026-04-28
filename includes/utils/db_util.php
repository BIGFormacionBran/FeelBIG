<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $path = __DIR__ . '/../../.env';
        echo "<script>console.log('Buscando .env en: " . addslashes($path) . "');</script>";
        
        if (!file_exists($path)) {
            echo "<script>console.error('ERROR: Archivo .env no encontrado');</script>";
            die();
        }

        $env = parse_ini_file($path);
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_NAME'] . ";charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "<script>console.log('Conexión PDO establecida correctamente');</script>";
        } catch (PDOException $e) {
            echo "<script>console.error('Fallo PDO: " . addslashes($e->getMessage()) . "');</script>";
            die();
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}