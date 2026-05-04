<?php
require_once __DIR__ . '/../utils/db_util.php';

class UsuarioDAO {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getConnection();
        } catch (Exception $e) {
            $this->db = null;
        }
    }

    /**
     * Registra un nuevo usuario. 
     * Se apoya en las restricciones UNIQUE de la DB para nombre y correo.
     */
    public function registrar($nombre, $correo, $password, $id_tipo = 3) {
        if (!$this->db) return false;
        try {
            // Encriptación BCRYPT
            $hash = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
        } catch (PDOException $e) {
            // Si hay un error de duplicidad (23000), devuelve false
            return false;
        }
    }

    public function login($identificador, $password) {
        if (!$this->db) throw new Exception("no_db");
        
        // Buscamos coincidencia en correo O en nombre
        $sql = "SELECT * FROM USUARIO WHERE correo = ? OR nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$identificador, $identificador]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}