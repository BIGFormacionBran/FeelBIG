<?php
require_once __DIR__ . '/ConfigUtil.php';
require_once __DIR__ . '/LoggerUtil.php';

class DbUtil {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $host = ConfigUtil::get('DB_HOST');
            $name = ConfigUtil::get('DB_NAME');
            $user = ConfigUtil::get('DB_USER');
            $pass = ConfigUtil::get('DB_PASS');

            if (!$host || !$name) {
                LoggerUtil::error("Database: Incomplete configuration. HOST: '$host', NAME: '$name'");
                throw new Exception("Configuración de base de datos incompleta en .env");
            }

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ];

            $this->connection = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8",
                $user,
                $pass,
                $options
            );

        } catch (PDOException $e) {
            LoggerUtil::error("Database: PDO ERROR [" . $e->getCode() . "]: " . $e->getMessage());
            die("Error de conexión a la base de datos. Consulta los logs.");
        } catch (Exception $e) {
            LoggerUtil::error("Database: GENERAL EXCEPTION: " . $e->getMessage());
            die("Error crítico de configuración."); 
        }
    }

    public static function getConnection() {
        
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}