<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';

class AdminContentDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function checkExists($nombre) {
        $stmt = $this->db->prepare("SELECT id FROM CATEGORIA WHERE LOWER(nombre) = ?");
        $stmt->execute([strtolower($nombre)]);
        return $stmt->fetch() !== false;
    }

    public function insertCategory($nombre, $id_padre = null) {
        $stmt = $this->db->prepare("INSERT INTO CATEGORIA (nombre, id_padre) VALUES (?, ?)");
        return $stmt->execute([$nombre, $id_padre]);
    }
}