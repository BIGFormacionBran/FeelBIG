<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $path = __DIR__ . '/../../.env';
        
        // Log de ruta
        echo "<script>console.log('1. Buscando .env en: " . addslashes($path) . "');</script>";
        
        if (!file_exists($path)) {
            echo "<script>console.error('ERROR: Archivo .env no encontrado');</script>";
            die("<p style='color:red'>Detenido: .env no encontrado. Revisa la consola (F12).</p>");
        }

        $env = parse_ini_file($path);
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_NAME'] . ";charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "<script>console.log('2. Conexión PDO EXITOSA');</script>";
        } catch (PDOException $e) {
            echo "<script>console.error('ERROR PDO: " . addslashes($e->getMessage()) . "');</script>";
            die("<p style='color:red'>Detenido: Error de conexión DB. Revisa la consola (F12).</p>");
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}