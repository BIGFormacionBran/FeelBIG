<?php
// feelbig\includes\daos\RegistroPendienteDAO.php
require_once __DIR__ . '/../utils/db_util.php';
require_once __DIR__ . '/../utils/logger_util.php';

class RegistroPendienteDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        if (!$this->db) {
            Logger::error("RegistroPendienteDAO: No se pudo establecer conexión con la base de datos.");
        }
    }

    public function crear_temporal($nombre, $correo, $password, $codigo) {
        if (!$this->db) return false;

        Logger::info("RegistroPendienteDAO: Iniciando proceso para [$correo]");

        try {
            // 1. ELIMINAR INTENTOS PREVIOS (Solución al error Duplicate Entry)
            // Esto asegura que si el usuario reintenta, el código viejo se borre.
            $sqlCleanup = "DELETE FROM REGISTRO_PENDIENTE WHERE correo = ?";
            $stmtCleanup = $this->db->prepare($sqlCleanup);
            $stmtCleanup->execute([$correo]);

            // 2. Limpiar registros expirados (más de 1 hora)
            $this->db->query("DELETE FROM REGISTRO_PENDIENTE WHERE fecha < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
            
            // 3. INSERTAR NUEVO REGISTRO
            $pass_hash = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO REGISTRO_PENDIENTE (nombre, correo, password, codigo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([$nombre, $correo, $pass_hash, $codigo]);

            if ($res) {
                Logger::info("RegistroPendienteDAO: Nuevo código [$codigo] guardado correctamente para [$correo].");
                return true;
            } else {
                Logger::error("RegistroPendienteDAO: Error en execute: " . implode(" - ", $stmt->errorInfo()));
                return false;
            }
        } catch (Exception $e) {
            Logger::error("RegistroPendienteDAO: Excepción: " . $e->getMessage());
            return false;
        }
    }

    public function obtener_y_validar($correo, $codigo) {
        if (!$this->db) return null;
        $sql = "SELECT * FROM REGISTRO_PENDIENTE WHERE correo = ? AND codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo, $codigo]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            Logger::info("RegistroPendienteDAO: Código validado con éxito para [$correo]");
        } else {
            Logger::error("RegistroPendienteDAO: No se encontró coincidencia para [$correo] y código [$codigo]");
        }
        return $data;
    }

    public function borrar_temporal($correo) {
        if (!$this->db) return false;
        $sql = "DELETE FROM REGISTRO_PENDIENTE WHERE correo = ?";
        return $this->db->prepare($sql)->execute([$correo]);
    }
}