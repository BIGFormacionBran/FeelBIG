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

    public function createCategory($nombre, $id_padre = null) {
        $nombre = trim((string)$nombre);
        if (empty($nombre)) return false;
        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;

        if ($this->adminDao->checkExists($nombre)) {
            LoggerUtil::error("Manager: Categoría duplicada: $nombre");
            return false;
        }

        return $this->adminDao->insertCategory($nombre, $parentId);
    }

    public function listAllCategoriesOrdered() {
        $all = $this->publicDao->getAllCategories();
        return $this->buildTree($all);
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

    public function updateCategory($id, $nombre, $id_padre) {
        $id = (int)$id;
        $nombre = trim((string)$nombre);
        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;
        if ($id === $parentId) return false;

        return $this->adminDao->updateCategory($id, $nombre, $parentId);
    }

    public function deleteCategory($id) {
        return $this->adminDao->deleteCategory((int)$id);
    }

    public function listAllContents() {
        return $this->adminDao->getAllContents();
    }

    public function createContent(array $datos) {
        if (empty($datos['nombre']) || empty($datos['id_categoria'])) return false;
        // Insertamos la fecha actual automáticamente
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

    public function saveContent(array $postData) {
    if (!empty($postData['id'])) {
        return $this->updateContent((int)$postData['id'], $postData);
    }
    return $this->createContent($postData);
}
}