<?php
require_once __DIR__ . '/../../../includes/utils/DbUtil.php';

class AdminContentDao {
    private $db;

    public function __construct() {
        $this->db = DbUtil::getConnection();
    }

    /* --- MÉTODOS DE CATEGORÍAS --- */
    public function checkExists($nombre) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM CATEGORIA WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetchColumn() > 0;
    }

    public function insertCategory($nombre, $id_padre, $imagen = null) {
        $stmt = $this->db->prepare("INSERT INTO CATEGORIA (nombre, id_padre, imagen) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $id_padre, $imagen]);
    }

    public function updateCategory($id, $nombre, $id_padre, $imagen = null) {
        $stmt = $this->db->prepare("UPDATE CATEGORIA SET nombre = ?, id_padre = ?, imagen = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id_padre, $imagen, (int)$id]);
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM CATEGORIA WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }

    public function listCategories() {
        $sql = "SELECT * FROM CATEGORIA ORDER BY id_padre ASC, nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* --- MÉTODOS DE CONTENIDO --- */
    public function getAllContents() {
        $sql = "SELECT c.*, cat.nombre as categoria_nombre 
                FROM CONTENIDO c JOIN CATEGORIA cat ON c.id_categoria = cat.id 
                ORDER BY c.id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertContent($datos) {
        $sql = "INSERT INTO CONTENIDO (
                    clasificacion, descripcion_breve, enlace_externo, 
                    fecha_publicacion, id_categoria, imagen, nombre, video
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        $id_cat = (isset($datos['id_categoria']) && is_numeric($datos['id_categoria'])) ? (int)$datos['id_categoria'] : null;

        return $stmt->execute([
            $datos['clasificacion'] ?? null,
            $datos['descripcion_breve'] ?? null,
            $datos['enlace_externo'] ?? null,
            $datos['fecha_publicacion'] ?? date('Y-m-d'),
            $id_cat,
            $datos['imagen'] ?? null,
            $datos['nombre'] ?? null,
            $datos['video'] ?? null
        ]);
    }

    public function updateContent($id, $datos) {
        $sql = "UPDATE CONTENIDO SET 
                    clasificacion = ?, 
                    descripcion_breve = ?, 
                    enlace_externo = ?, 
                    id_categoria = ?, 
                    imagen = ?, 
                    nombre = ?, 
                    video = ?,
                    fecha_publicacion = ? 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        
        $id_cat = (isset($datos['id_categoria']) && is_numeric($datos['id_categoria'])) ? (int)$datos['id_categoria'] : null;

        return $stmt->execute([
            $datos['clasificacion'] ?? null,
            $datos['descripcion_breve'] ?? null,
            $datos['enlace_externo'] ?? null,
            $id_cat,
            $datos['imagen'] ?? null,
            $datos['nombre'] ?? null,
            $datos['video'] ?? null,
            $datos['fecha_publicacion'] ?? date('Y-m-d'),
            (int)$id
        ]);
    }

    public function deleteContent($id) {
        $stmt = $this->db->prepare("DELETE FROM CONTENIDO WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
    
    /* --- LIMPIEZA DE REFERENCIAS DE ARCHIVOS --- */
    public function clearFileReferences($path) {
        $stmt = $this->db->prepare("UPDATE CONTENIDO SET imagen = NULL WHERE imagen = ?");
        $stmt->execute([$path]);
        $stmt = $this->db->prepare("UPDATE CONTENIDO SET video = NULL WHERE video = ?");
        $stmt->execute([$path]);
        
        $stmt = $this->db->prepare("UPDATE CATEGORIA SET imagen = NULL WHERE imagen = ?");
        return $stmt->execute([$path]);
    }
}