<?php
// feelbig\includes\utils\db_util.php
require_once __DIR__ . '/config_util.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $host = ConfigUtil::get('DB_HOST');
            $name = ConfigUtil::get('DB_NAME');
            $user = ConfigUtil::get('DB_USER');
            $pass = ConfigUtil::get('DB_PASS');

            $this->conn = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8",
                $user,
                $pass,
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