<?php
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../daos/ContentDao.php';
require_once __DIR__ . '/../../../includes/daos/ContentDao.php';

class AdminContentManager {
    private $adminDao;
    private $publicDao;

    public function __construct() {
        $this->adminDao = new AdminContentDao();
        $this->publicDao = new ContentDao();
    }

    public function add(array $postData) {
        $entity = $postData['entity_type'] ?? '';
        LoggerUtil::info("CONTENT_MANAGER: Iniciando proceso de 'add' para [$entity]");

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

        LoggerUtil::error("CONTENT_MANAGER: Tipo de entidad desconocido para añadir: $entity");
        return false;
    }

    public function save(array $postData) {
        $entity = $postData['entity_type'] ?? '';
        $id = $postData['id'] ?? null;
        LoggerUtil::info("CONTENT_MANAGER: Iniciando proceso de 'save' (ID: $id) para [$entity]");

        if (!$id) {
            LoggerUtil::error("CONTENT_MANAGER: Error - Intento de edición sin ID.");
            return false;
        }

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

    public function remove(array $postData) {
        $entity = $postData['entity_type'] ?? '';
        $id = $postData['id'] ?? null;
        LoggerUtil::info("CONTENT_MANAGER: Iniciando proceso de 'remove' (ID: $id) para [$entity]");

        if (!$id) {
            LoggerUtil::error("CONTENT_MANAGER: Error - Intento de borrado sin ID.");
            return false;
        }

        return ($entity === 'Categoría') 
            ? $this->deleteCategory($id) 
            : $this->deleteContent($id);
    }

    // --- LÓGICA DE NEGOCIO ---

    public function createCategory($nombre, $id_padre = null, $imagen = null) {
        $nombre = trim((string)$nombre);
        LoggerUtil::info("CONTENT_MANAGER: Creando categoría [$nombre]...");
        
        if (empty($nombre)) {
            LoggerUtil::error("CONTENT_MANAGER: El nombre de la categoría está vacío.");
            return false;
        }

        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;
        
        if ($this->adminDao->checkExists($nombre)) {
            LoggerUtil::error("CONTENT_MANAGER: La categoría ya existe en la DB: $nombre");
            return false;
        }

        return $this->adminDao->insertCategory($nombre, $parentId, $imagen);
    }
    
    public function updateCategory($id, $nombre, $id_padre, $imagen = null) {
        LoggerUtil::info("CONTENT_MANAGER: Actualizando categoría ID $id -> $nombre");
        $parentId = ($id_padre === "null" || empty($id_padre)) ? null : (int)$id_padre;
        return $this->adminDao->updateCategory((int)$id, $nombre, $parentId, $imagen);
    }

    public function deleteCategory($id) {
        LoggerUtil::info("CONTENT_MANAGER: Eliminando categoría ID $id de la DB.");
        return $this->adminDao->deleteCategory((int)$id);
    }

    public function createContent(array $datos) {
        LoggerUtil::info("CONTENT_MANAGER: Creando nuevo contenido: " . ($datos['nombre'] ?? 'S/N'));
        if (empty($datos['nombre']) || empty($datos['id_categoria'])) {
            LoggerUtil::error("CONTENT_MANAGER: Faltan campos obligatorios (nombre/categoría).");
            return false;
        }
        $datos['fecha_publicacion'] = date('Y-m-d');
        return $this->adminDao->insertContent($datos);
    }

    public function updateContent($id, array $datos) {
        LoggerUtil::info("CONTENT_MANAGER: Editando contenido ID $id.");
        if (empty($id) || empty($datos['nombre'])) return false;
        return $this->adminDao->updateContent((int)$id, $datos);
    }

    public function deleteContent($id) {
        LoggerUtil::info("CONTENT_MANAGER: Eliminando contenido ID $id de la DB.");
        return $this->adminDao->deleteContent((int)$id);
    }

    public function listAllCategoriesOrdered() {
        return $this->buildTree($this->publicDao->getAllCategories());
    }

    public function listAllContents() {
        return $this->adminDao->getAllContents();
    }

    public function clearReferences($path) {
        LoggerUtil::info("CONTENT_MANAGER: Limpiando todas las referencias de [$path] en tablas de contenido y categorías.");
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