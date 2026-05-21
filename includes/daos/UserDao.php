<?php
require_once __DIR__ . '/../utils/DbUtil.php';

class UserDao {
    private $db;

    public function __construct() {
        try {
            $this->db = DbUtil::getConnection();
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

    public function register($name, $email, $password, $typeId = 3) {
        if (!$this->db) return false;
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            return $this->registerWithHash($name, $email, $hash, $typeId);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function registerWithHash($name, $email, $hash, $typeId = 3) {
        if (!$this->db) return false;
        try {
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$name, $email, $hash, $typeId]);
        } catch (PDOException $e) {
            LoggerUtil::error("UserDao: SQL Error in registerWithHash: " . $e->getMessage());
            return false;
        }
    }

    public function login($identifier, $password) {
        if (!$this->db) {
            LoggerUtil::error("UserDao: No DB connection.");
            throw new Exception("Error de conexión");
        }
        try {
            $sql = "SELECT * FROM USUARIO WHERE correo = ? OR nombre = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return password_verify($password, $user['password']) && $user ? $user : false;
        } catch (PDOException $e) {
            LoggerUtil::error("UserDao: SQL Error in login: " . $e->getMessage());
            return false;
        }
    }

    public function updateProfile($id, $name, $email, $password = null) {
        if (!$this->db) return false;
        try {
            if ($password) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE USUARIO SET nombre = ?, correo = ?, password = ? WHERE id = ?";
                $params = [$name, $email, $hash, $id];
            } else {
                $sql = "UPDATE USUARIO SET nombre = ?, correo = ? WHERE id = ?";
                $params = [$name, $email, $id];
            }
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
}