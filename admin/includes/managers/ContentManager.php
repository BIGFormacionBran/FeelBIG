<?php
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminContentManager {
    /** @var AdminContentDao */
    private $adminDao;
    /** @var ContentDao */
    private $publicDao;

    public function __construct() {
        $this->adminDao = new AdminContentDao();
        $this->publicDao = new ContentDao();
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

    public function listAllCategoriesOrdered() {
        $all = $this->publicDao->getAllCategories();
        return $this->buildTree($all);
    }

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

    public function listAllContents() {
        return $this->adminDao->getAllContents();
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

    public function clearReferences($path) {
        return $this->adminDao->clearFileReferences($path);
    }

    public function saveContent(array $postData) {
        if (!empty($postData['id'])) {
            return $this->updateContent((int)$postData['id'], $postData);
        }
        return $this->createContent($postData);
    }
}