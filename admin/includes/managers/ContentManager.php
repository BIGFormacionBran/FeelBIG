<?php
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminContentManager {
    private $adminDao;
    private $publicDao;

    public function __construct() {
        $this->adminDao = new AdminContentDao();
        $this->publicDao = new ContentDao();
    }

    /**
     * DELEGACIÓN AUTOMÁTICA DE ADICIÓN
     */
    public function add(array $postData) {
        $entity = $postData['entity_type'] ?? '';

        if ($entity === 'Categoría') {
            return $this->createCategory(
                $postData['nombre'] ?? '', 
                $postData['id_padre'] ?? null, 
                $postData['imagen'] ?? null
            );
        }

        if ($entity === 'Contenido') {
            return $this->createContent($postData);
        }

        return false;
    }

    /**
     * DELEGACIÓN AUTOMÁTICA DE GUARDADO (EDICIÓN)
     */
    public function save(array $postData) {
        $entity = $postData['entity_type'] ?? '';
        $id = $postData['id'] ?? null;
        if (!$id) return false;

        if ($entity === 'Categoría') {
            return $this->updateCategory(
                $id, 
                $postData['nombre'] ?? '', 
                $postData['id_padre'] ?? null, 
                $postData['imagen'] ?? null
            );
        }

        if ($entity === 'Contenido') {
            return $this->updateContent($id, $postData);
        }

        return false;
    }

    /**
     * DELEGACIÓN AUTOMÁTICA DE ELIMINACIÓN
     */
    public function remove(array $postData) {
        $entity = $postData['entity_type'] ?? '';
        $id = $postData['id'] ?? null;
        if (!$id) return false;

        return ($entity === 'Categoría') 
            ? $this->deleteCategory($id) 
            : $this->deleteContent($id);
    }

    // --- MÉTODOS DE SOPORTE (Lógica de negocio) ---

    public function createCategory($nombre, $id_padre = null, $imagen = null) {
        $nombre = trim((string)$nombre);
        if (empty($nombre)) return false;
        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;
        if ($this->adminDao->checkExists($nombre)) return false;
        return $this->adminDao->insertCategory($nombre, $parentId, $imagen);
    }
    
    public function updateCategory($id, $nombre, $id_padre, $imagen = null) {
        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;
        return $this->adminDao->updateCategory((int)$id, $nombre, $parentId, $imagen);
    }

    public function deleteCategory($id) {
        return $this->adminDao->deleteCategory((int)$id);
    }

    public function createContent(array $datos) {
        if (empty($datos['nombre']) || empty($datos['id_categoria'])) return false;
        $datos['fecha_publicacion'] = date('Y-m-d');
        return $this->adminDao->insertContent($datos);
    }

    public function updateContent($id, array $datos) {
        if (empty($id) || empty($datos['nombre'])) return false;
        return $this->adminDao->updateContent((int)$id, $datos);
    }

    public function deleteContent($id) {
        return $this->adminDao->deleteContent((int)$id);
    }

    public function listAllCategoriesOrdered() {
        $all = $this->publicDao->getAllCategories();
        return $this->buildTree($all);
    }

    public function listAllContents() {
        return $this->adminDao->getAllContents();
    }

    public function clearReferences($path) {
        return $this->adminDao->clearFileReferences($path);
    }

    private function buildTree(array $elements, $parentId = null) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['id_padre'] == $parentId) {
                $branch[] = $element;
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    foreach($children as $child) { $branch[] = $child; }
                }
            }
        }
        return $branch;
    }
}