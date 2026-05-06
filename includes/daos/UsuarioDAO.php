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

    public function getById($id) {
        if (!$this->db) return null;
        $sql = "SELECT * FROM USUARIO WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $correo, $password, $id_tipo = 3) {
        if (!$this->db) return false;
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            return $this->registrar_con_hash($nombre, $correo, $hash, $id_tipo);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function registrar_con_hash($nombre, $correo, $hash, $id_tipo = 3) {
        if (!$this->db) return false;
        try {
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($identificador, $password) {
        if (!$this->db) {
            Logger::error("UsuarioDAO: No hay conexión a la base de datos.");
            throw new Exception("no_db");
        }
        
        try {
            $sql = "SELECT * FROM USUARIO WHERE correo = ? OR nombre = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$identificador, $identificador]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                Logger::info("UsuarioDAO: No se encontró ningún usuario con identificador: $identificador");
                return false;
            }

            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                Logger::error("UsuarioDAO: Contraseña incorrecta para el usuario: $identificador");
                // Debug opcional: Si sospechas del hash, loguea el hash de la DB (NUNCA la pass plana)
                // Logger::info("Hash en DB: " . $user['password']); 
                return false;
            }
        } catch (PDOException $e) {
            Logger::error("UsuarioDAO: Error de SQL en login: " . $e->getMessage());
            return false;
        }
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