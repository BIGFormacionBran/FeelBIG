<?php
require_once __DIR__ . '/../utils/DbUtil.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';

class PendingRegistrationDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function createTemporal($name, $email, $password, $code) {
        if (!$this->db) return false;
        try {
            $sqlCleanup = "DELETE FROM REGISTRO_PENDIENTE WHERE correo = ?";
            $this->db->prepare($sqlCleanup)->execute([$email]);
            $this->db->query("DELETE FROM REGISTRO_PENDIENTE WHERE fecha < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
            
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO REGISTRO_PENDIENTE (nombre, correo, password, codigo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$name, $email, $hash, $code]);
        } catch (Exception $e) {
            LoggerUtil::error("PendingRegistrationDao Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAndValidate($email, $code) {
        if (!$this->db) return null;
        $sql = "SELECT * FROM REGISTRO_PENDIENTE WHERE correo = ? AND codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTemporal($email) {
        if (!$this->db) return false;
        $sql = "DELETE FROM REGISTRO_PENDIENTE WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$email]);
    }
}