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

    public function updateCategory($id, $nombre, $id_padre) {
        $stmt = $this->db->prepare("UPDATE CATEGORIA SET nombre = ?, id_padre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id_padre, $id]);
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM CATEGORIA WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function insertContent($titulo, $subtitulo, $descripcion, $imagen, $id_categoria) {
        $stmt = $this->db->prepare("INSERT INTO CONTENIDO (nombre, subtitulo, descripcion, imagen, id_categoria) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$titulo, $subtitulo, $descripcion, $imagen, $id_categoria]);
    }

    public function updateContent($id, $titulo, $subtitulo, $descripcion, $imagen, $id_categoria) {
        $stmt = $this->db->prepare("UPDATE CONTENIDO SET nombre = ?, subtitulo = ?, descripcion = ?, imagen = ?, id_categoria = ? WHERE id = ?");
        return $stmt->execute([$titulo, $subtitulo, $descripcion, $imagen, $id_categoria, $id]);
    }

    public function deleteContent($id) {
        $stmt = $this->db->prepare("DELETE FROM CONTENIDO WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllContents() {
        $sql = "SELECT c.*, cat.nombre as categoria_nombre 
                FROM CONTENIDO c 
                JOIN CATEGORIA cat ON c.id_categoria = cat.id 
                ORDER BY c.id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}