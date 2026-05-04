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

    public function registrar($nombre, $correo, $password, $id_tipo = 3) {
        if (!$this->db) return false;
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($identificador, $password) {
        if (!$this->db) throw new Exception("no_db");
        
        $sql = "SELECT * FROM USUARIO WHERE correo = ? OR nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$identificador, $identificador]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Actualiza los datos del perfil del usuario
     */
    public function actualizarPerfil($id, $nombre, $correo, $password = null) {
        if (!$this->db) return false;
        try {
            if ($password) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE USUARIO SET nombre = ?, correo = ?, password = ? WHERE id = ?";
                $params = [$nombre, $correo, $hash, $id];
            } else {
                $sql = "UPDATE USUARIO SET nombre = ?, correo = ? WHERE id = ?";
                $params = [$nombre, $correo, $id];
            }
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
}