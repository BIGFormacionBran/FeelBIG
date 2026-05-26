<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';

class AdminUserDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function getAllUsers(array $filters = []) {
        $sql = "SELECT u.*, t.nombre as tipo_cuenta_nombre 
                FROM USUARIO u 
                LEFT JOIN TIPO_CUENTA t ON u.id_tipo_cuenta = t.id 
                WHERE 1=1";
        
        $params = [];
        if (!empty($filters['search'])) {
            $sql .= " AND (u.nombre LIKE ? OR u.correo LIKE ?)";
            $params[] = "%".$filters['search']."%";
            $params[] = "%".$filters['search']."%";
        }

        $order = (isset($filters['order']) && $filters['order'] === 'DESC') ? 'DESC' : 'ASC';
        $sql .= " ORDER BY u.id $order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserType($id, $id_tipo_cuenta) {
        $sql = "UPDATE USUARIO SET id_tipo_cuenta = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$id_tipo_cuenta, (int)$id]);
    }
}