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
                WHERE C.id_padre IS NULL
                ORDER BY C.id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_subcategorias($id_padre) {
        if (!$id_padre) return [];
        $stmt = $this->db->prepare("SELECT * FROM CATEGORIA WHERE id_padre = ?");
        $stmt->execute([$id_padre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_contenidos_by_categoria($id_categoria) {
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_contenido_por_nombre($nombre) {
        $nombre_real = str_replace('-', ' ', $nombre);
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE LOWER(nombre) = ? LIMIT 1");
        $stmt->execute([strtolower($nombre_real)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_categoria_por_slug($slug) {
        $nombre_real = str_replace('-', ' ', $slug);
        $stmt = $this->db->prepare("SELECT * FROM CATEGORIA WHERE LOWER(nombre) = ? LIMIT 1");
        $stmt->execute([strtolower($nombre_real)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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