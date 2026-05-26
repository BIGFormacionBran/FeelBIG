<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';

class AdminUserDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function getAllUsers() {
        $sql = "SELECT u.*, t.nombre as tipo_cuenta_nombre 
                FROM USUARIO u 
                LEFT JOIN TIPO_CUENTA t ON u.id_tipo_cuenta = t.id 
                ORDER BY u.id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserType($id, $id_tipo_cuenta) {
        $sql = "UPDATE USUARIO SET id_tipo_cuenta = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$id_tipo_cuenta, (int)$id]);
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM USUARIO WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}