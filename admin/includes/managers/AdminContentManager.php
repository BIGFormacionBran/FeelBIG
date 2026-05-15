<?php
require_once __DIR__ . '/../daos/AdminContentDao.php';
require_once __DIR__ . '/../../../includes/daos/ContentDao.php'; // Solo para lectura/listado

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
        
        $parentId = (!empty($id_padre)) ? (int)$id_padre : null;
        return $this->adminDao->insertCategory($nombre, $parentId);
    }

    public function listAllCategories() {
        return $this->publicDao->getAllCategories();
    }
}