<?php
require_once __DIR__ . '/../utils/DbUtil.php';

class ContentDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    public function getHomeStructure() {
        $sql = "SELECT id, nombre FROM CATEGORIA 
                WHERE id_padre IS NULL 
                ORDER BY id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubcategories($parentId) {
        $stmt = $this->db->prepare("SELECT * FROM CATEGORIA WHERE id_padre = ?");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentsByCategory($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE id_categoria = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentByName($name) {
        $realName = str_replace('-', ' ', $name);
        $stmt = $this->db->prepare("SELECT * FROM CONTENIDO WHERE LOWER(nombre) = ? LIMIT 1");
        $stmt->execute([strtolower($realName)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategoryBySlug($slug) {
        $realName = str_replace('-', ' ', $slug);
        $stmt = $this->db->prepare("SELECT * FROM CATEGORIA WHERE LOWER(nombre) = ? LIMIT 1");
        $stmt->execute([strtolower($realName)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $stmt = $this->db->prepare("SELECT * FROM CATEGORIA WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategoryByItemId($itemId) {
        $stmt = $this->db->prepare("SELECT id_categoria FROM CONTENIDO WHERE id = ? LIMIT 1");
        $stmt->execute([$itemId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $this->getCategoryById($res['id_categoria']) : null;
    }
}