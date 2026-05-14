<?php
require_once __DIR__ . '/ConfigUtil.php';
require_once __DIR__ . '/LoggerUtil.php';

class DbUtil {
    private static $instance = null;
    private $connection;

    private function __construct() {
        Logger::info("Database: Starting private constructor...");
        try {
            $host = ConfigUtil::get('DB_HOST');
            $name = ConfigUtil::get('DB_NAME');
            $user = ConfigUtil::get('DB_USER');
            $pass = ConfigUtil::get('DB_PASS');

            Logger::info("Database: Attempting to connect to $host / $name with user $user");

            if (!$host || !$name) {
                Logger::error("Database: Incomplete configuration. HOST: '$host', NAME: '$name'");
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
            
            Logger::info("Database: PDO connection established successfully!");

        } catch (PDOException $e) {
            Logger::error("Database: PDO ERROR [" . $e->getCode() . "]: " . $e->getMessage());
            die("Error de conexión a la base de datos. Consulta los logs.");
        } catch (Exception $e) {
            Logger::error("Database: GENERAL EXCEPTION: " . $e->getMessage());
            die("Error crítico de configuración."); 
        }
    }

    public static function getConnection() {
        Logger::info("Database: Requesting connection instance...");
        
        if (self::$instance === null) {
            Logger::info("Database: No previous instance. Creating new...");
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}