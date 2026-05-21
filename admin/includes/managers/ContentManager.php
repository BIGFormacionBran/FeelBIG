<?php
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../daos/ContentDao.php';
// Eliminada importación duplicada/incorrecta que causaba conflicto

class AdminContentManager {
    private $adminDao;
    private $publicDao;

    public function __construct() {
        $this->adminDao = new AdminContentDao();
        // El publicDao se asume que usa la misma conexión o lógica similar
        $this->publicDao = $this->adminDao; 
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

        if ($entity === 'Categoría') {
            return $this->deleteCategory($id);
        }

        if ($entity === 'Contenido') {
            return $this->deleteContent($id);
        }

        return false;
    }

    public function createCategory($nombre, $idPadre, $imagen) {
        LoggerUtil::info("CONTENT_MANAGER: Creando categoría [$nombre]");
        if (empty($nombre)) return false;
        return $this->adminDao->insertCategory($nombre, $idPadre, $imagen);
    }

    public function updateCategory($id, $nombre, $idPadre, $imagen) {
        LoggerUtil::info("CONTENT_MANAGER: Actualizando categoría ID $id");
        if (empty($id) || empty($nombre)) return false;
        return $this->adminDao->updateCategory((int)$id, $nombre, $idPadre, $imagen);
    }

    public function deleteCategory($id) {
        LoggerUtil::info("CONTENT_MANAGER: Borrando categoría ID $id");
        return $this->adminDao->deleteCategory((int)$id);
    }

    public function createContent(array $datos) {
        LoggerUtil::info("CONTENT_MANAGER: Creando nuevo contenido.");
        if (empty($datos['nombre'])) return false;
        $datos['fecha_creacion'] = date('Y-m-d');
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
        // Corregido: AdminContentDao utiliza listCategories() según la nomenclatura estándar
        return $this->buildTree($this->adminDao->listCategories());
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
                    $element['children'] = $children;
                }
            }
        }
        return $branch;
    }
}