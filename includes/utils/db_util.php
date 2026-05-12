<?php
// feelbig\includes\utils\db_util.php
require_once __DIR__ . '/config_util.php';
require_once __DIR__ . '/logger_util.php'; // Aseguramos que el logger esté disponible

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        Logger::info("Database: Iniciando constructor privado...");
        try {
            $host = ConfigUtil::get('DB_HOST');
            $name = ConfigUtil::get('DB_NAME');
            $user = ConfigUtil::get('DB_USER');
            $pass = ConfigUtil::get('DB_PASS');

            Logger::info("Database: Intentando conectar a $host / $name con usuario $user");

            if (!$host || !$name) {
                Logger::error("Database: Configuración incompleta. HOST: '$host', NAME: '$name'");
                throw new Exception("Configuración de base de datos incompleta en .env");
            }

            // Establecemos un timeout corto para el intento de conexión de PDO 
            // para que no cuelgue el servidor infinitamente si la IP es errónea.
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5, // 5 segundos de margen
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ];

            $this->conn = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8",
                $user,
                $pass,
                $options
            );
            
            Logger::info("Database: ¡Conexión PDO establecida con éxito!");

        } catch (PDOException $e) {
            Logger::error("Database: ERROR PDO [" . $e->getCode() . "]: " . $e->getMessage());
            die("Error de conexión a la base de datos. Consulta los logs.");
        } catch (Exception $e) {
            Logger::error("Database: EXCEPCIÓN GENERAL: " . $e->getMessage());
            die("Error crítico de configuración."); 
        }
    }

    public static function getConnection() {
        Logger::info("Database: Solicitando instancia de conexión...");
        
        // Es mejor inicializar la propia clase Database y guardarla en $instance
        if (self::$instance === null) {
            Logger::info("Database: No hay instancia previa. Creando nueva...");
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}