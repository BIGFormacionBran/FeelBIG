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

    public function registrar($nombre, $correo, $password, $id_tipo = 4) {
        if (!$this->db) return false;
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
        } catch (PDOException $e) {
            return false; // Probablemente correo duplicado
        }
    }

    public function login($correo, $password) {
        if (!$this->db) throw new Exception("no_db");
        
        $sql = "SELECT * FROM USUARIO WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}