<?php
require_once __DIR__ . '/../daos/AdminContentDao.php';
require_once __DIR__ . '/../../../includes/daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminContentManager {
    private $adminDao;
    private $publicDao;

    public function __construct() {
        $this->adminDao = new AdminContentDao();
        $this->publicDao = new ContentDao();
    }

    public function createCategory($nombre, $id_padre = null) {
        $nombre = trim($nombre);
        if (empty($nombre)) return false;

        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;

        if ($this->adminDao->checkExists($nombre)) {
            LoggerUtil::error("Manager: Intento de crear categoría duplicada: $nombre");
            return false;
        }

        $result = $this->adminDao->insertCategory($nombre, $parentId);
        
        if ($result) {
            LoggerUtil::info("Manager: Categoría '$nombre' creada (Padre: " . ($parentId ?? 'Raíz') . ")");
        }
        return $result;
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
                    foreach($children as $child) {
                        $branch[] = $child;
                    }
                }
            }
        }
        return $branch;
    }

    public function updateCategory($id, $nombre, $id_padre) {
        $id = (int)$id;
        $nombre = trim($nombre);
        if (empty($nombre)) return false;

        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;

        // Evitar que una categoría sea su propio padre
        if ($id === $parentId) return false;

        $result = $this->adminDao->updateCategory($id, $nombre, $parentId);
        if ($result) {
            LoggerUtil::info("Manager: Categoría ID $id actualizada a '$nombre'");
        }
        return $result;
    }

    public function deleteCategory($id) {
        $id = (int)$id;
        $result = $this->adminDao->deleteCategory($id);
        if ($result) {
            LoggerUtil::info("Manager: Categoría ID $id eliminada");
        }
        return $result;
    }
}