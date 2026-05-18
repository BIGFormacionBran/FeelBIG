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

    public function listAllContents() {
        return $this->adminDao->getAllContents();
    }

    // --- OPERACIONES DE CONTENIDO CON LOGS ---

    public function createContent($titulo, $subtitulo, $descripcion, $imagen, $id_categoria) {
        $titulo = trim($titulo);
        
        LoggerUtil::info("Manager: Intentando crear contenido. Título: '$titulo', Cat: '$id_categoria'");

        if (empty($titulo)) {
            LoggerUtil::error("Manager: Error al crear contenido - El título está vacío.");
            return false;
        }
        if (empty($id_categoria)) {
            LoggerUtil::error("Manager: Error al crear contenido - No se proporcionó ID de categoría.");
            return false;
        }

        $result = $this->adminDao->insertContent($titulo, $subtitulo, $descripcion, $imagen, (int)$id_categoria);

        if ($result) {
            LoggerUtil::info("Manager: Contenido '$titulo' creado con éxito.");
        } else {
            LoggerUtil::error("Manager: El DAO devolvió FALSE al intentar insertar contenido '$titulo'. Revisar AdminContentDao o la DB.");
        }

        return $result;
    }

    public function updateContent($id, $titulo, $subtitulo, $descripcion, $imagen, $id_categoria) {
        $id = (int)$id;
        $titulo = trim($titulo);

        LoggerUtil::info("Manager: Intentando editar contenido ID: $id. Nuevo Título: '$titulo'");

        if (empty($id) || empty($titulo)) {
            LoggerUtil::error("Manager: Error al editar - ID ($id) o Título ('$titulo') faltantes.");
            return false;
        }

        $result = $this->adminDao->updateContent($id, $titulo, $subtitulo, $descripcion, $imagen, (int)$id_categoria);

        if ($result) {
            LoggerUtil::info("Manager: Contenido ID $id actualizado con éxito.");
        } else {
            LoggerUtil::error("Manager: El DAO devolvió FALSE al actualizar contenido ID $id.");
        }

        return $result;
    }

    public function deleteContent($id) {
        $id = (int)$id;
        LoggerUtil::info("Manager: Intentando eliminar contenido ID: $id");

        $result = $this->adminDao->deleteContent($id);

        if ($result) {
            LoggerUtil::info("Manager: Contenido ID $id eliminado correctamente.");
        } else {
            LoggerUtil::error("Manager: Fallo al eliminar contenido ID $id en el DAO.");
        }

        return $result;
    }
}