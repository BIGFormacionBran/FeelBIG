<?php
require_once __DIR__ . '/../utils/db_util.php';

class ContenidoDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function get_home_structure() {
        $sql = "SELECT DISTINCT C.id, C.nombre FROM CATEGORIA C
                INNER JOIN CONTENIDO CONT ON C.id = CONT.id_categoria
                ORDER BY C.id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_contenidos_by_categoria($id_categoria) {
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_contenido_por_nombre($nombre) {
        $nombre_real = str_replace('-', ' ', $nombre);
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE nombre = ? LIMIT 1");
        $stmt->execute([$nombre_real]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NUEVO MÉTODO AÑADIDO:
    public function get_categoria_por_item_id($itemId) {
        $stmt = $this->db->prepare("
            SELECT C.* FROM CATEGORIA C 
            INNER JOIN CONTENIDO CONT ON CONT.id_categoria = C.id 
            WHERE CONT.id = ? LIMIT 1
        ");
        $stmt->execute([$itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}