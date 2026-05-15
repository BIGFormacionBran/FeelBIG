<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminContentDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function insertCategory($nombre, $id_padre = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO CATEGORIA (nombre, id_padre) VALUES (?, ?)");
            $result = $stmt->execute([$nombre, $id_padre]);
            
            if ($result) {
                LoggerUtil::info("Categoría creada exitosamente: '$nombre' (Padre ID: " . ($id_padre ?? 'Raíz') . ")");
            }
            
            return $result;
        } catch (PDOException $e) {
            LoggerUtil::error("Error en AdminContentDao (insertCategory): " . $e->getMessage());
            return false;
        }
    }
}