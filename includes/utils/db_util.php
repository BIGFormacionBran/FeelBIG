<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $path = __DIR__ . '/../../.env';
        
        if (!file_exists($path)) {
            // Esto te confirmará si el Docker falló al crear el archivo
            die("Error: El archivo .env no existe en la ruta: " . $path);
        }

        $env = parse_ini_file($path);
        
        if (!$env) {
            die("Error: El archivo .env está vacío o mal formado.");
        }
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_NAME'] . ";charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            // Si llega aquí, el archivo existe pero los datos (user/pass) están mal
            die("Error de conexión PDO: " . $e->getMessage()); 
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}