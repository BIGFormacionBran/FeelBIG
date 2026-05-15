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
        $ordered = [];
        
        // 1. Separar raíces de hijas
        $roots = array_filter($all, function($c) { return $c['id_padre'] === null; });
        
        // 2. Por cada raíz, buscar sus hijas e insertarlas justo debajo
        foreach ($roots as $root) {
            $ordered[] = $root;
            foreach ($all as $child) {
                if ($child['id_padre'] == $root['id']) {
                    $ordered[] = $child;
                }
            }
        }
        return $ordered;
    }
}