<?php
require_once __DIR__ . '/../utils/db_util.php';

class UsuarioDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Para el registro
    public function registrar($nombre, $correo, $password, $id_tipo = 4) { // 4 = USER por defecto
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
    }

    // Para el login
    public function login($correo, $password) {
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