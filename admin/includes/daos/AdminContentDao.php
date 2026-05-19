<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';

class AdminContentDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    /* --- MÉTODOS DE CATEGORÍAS (FALTABAN AQUÍ) --- */

    public function checkExists($nombre) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM CATEGORIA WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetchColumn() > 0;
    }

    public function insertCategory($nombre, $id_padre) {
        $stmt = $this->db->prepare("INSERT INTO CATEGORIA (nombre, id_padre) VALUES (?, ?)");
        return $stmt->execute([$nombre, $id_padre]);
    }

    public function updateCategory($id, $nombre, $id_padre) {
        $stmt = $this->db->prepare("UPDATE CATEGORIA SET nombre = ?, id_padre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id_padre, (int)$id]);
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM CATEGORIA WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }

    /* --- MÉTODOS DE CONTENIDO --- */

    public function insertContent($datos) {
        $sql = "INSERT INTO CONTENIDO (
                    clasificacion, descripcion_breve, enlace_externo, 
                    fecha_publicacion, id_categoria, imagen, nombre, video
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['clasificacion'] ?? null,
            $datos['descripcion_breve'] ?? null,
            $datos['enlace_externo'] ?? null,
            $datos['fecha_publicacion'] ?? null,
            (int)$datos['id_categoria'],
            $datos['imagen'] ?? null,
            $datos['nombre'],
            $datos['video'] ?? null
        ]);
    }

    public function updateContent($id, $datos) {
        $sql = "UPDATE CONTENIDO SET 
                    clasificacion = ?, 
                    descripcion_breve = ?, 
                    enlace_externo = ?, 
                    fecha_publicacion = ?, 
                    id_categoria = ?, 
                    imagen = ?, 
                    nombre = ?, 
                    video = ? 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['clasificacion'] ?? null,
            $datos['descripcion_breve'] ?? null,
            $datos['enlace_externo'] ?? null,
            $datos['fecha_publicacion'] ?? null,
            (int)$datos['id_categoria'],
            $datos['imagen'] ?? null,
            $datos['nombre'],
            $datos['video'] ?? null,
            (int)$id
        ]);
    }

    public function getAllContents() {
        $sql = "SELECT c.*, cat.nombre as categoria_nombre 
                FROM CONTENIDO c 
                JOIN CATEGORIA cat ON c.id_categoria = cat.id 
                ORDER BY c.id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteContent($id) {
        $stmt = $this->db->prepare("DELETE FROM CONTENIDO WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}