<?php
require_once __DIR__ . '/../utils/db_util.php';

class RegistroPendienteDAO {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getConnection();
        } catch (Exception $e) {
            $this->db = null;
        }
    }

    public function crear_temporal($nombre, $correo, $password, $codigo) {
        if (!$this->db) return false;
        
        // Escenario: Borrar contenido antiguo (más de 1 hora)
        $this->db->query("DELETE FROM REGISTRO_PENDIENTE WHERE fecha < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

        try {
            // ENCRIPTACIÓN: Los datos sensibles se guardan cifrados
            $pass_hash = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO REGISTRO_PENDIENTE (nombre, correo, password, codigo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $pass_hash, $codigo]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtener_y_validar($correo, $codigo) {
        if (!$this->db) return null;
        $sql = "SELECT * FROM REGISTRO_PENDIENTE WHERE correo = ? AND codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo, $codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function borrar_temporal($correo) {
        if (!$this->db) return false;
        $sql = "DELETE FROM REGISTRO_PENDIENTE WHERE correo = ?";
        return $this->db->prepare($sql)->execute([$correo]);
    }
}