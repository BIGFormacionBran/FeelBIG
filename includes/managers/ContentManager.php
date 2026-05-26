<?php
require_once __DIR__ . '/../daos/ContentDao.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';

class ContentManager {
    public $contentDao;

    public function __construct() {
        try {
            $this->contentDao = new ContentDao();
        } catch (Exception $e) {
            LoggerUtil::error("ContentManager: Error instantiating ContentDao: " . $e->getMessage());
        }
    }

    public function getMainMenu() {
        $categories = $this->getHomeStructure();
        return array_map(function($cat) {
            return [
                'id'    => $cat['id'],
                'slug'  => str_replace(' ', '-', strtolower($cat['nombre'])),
                'title' => $cat['nombre']
            ];
        }, $categories);
    }

    public function getHomeStructure() {
        return $this->contentDao->getHomeStructure();
    }

    public function getCategoryContent($categoryId) {
        $results = [];
        $subcategories = $this->contentDao->getSubcategories($categoryId);
        if (!empty($subcategories)) {
            $mappedSubcategories = array_map([$this, 'mapCategoryToCard'], $subcategories);
            $results = array_merge($results, $mappedSubcategories);
        }

        $items = $this->getItemsByCategoryId($categoryId);
        if (!empty($items)) {
            $results = array_merge($results, $items);
        }

        return $results;
    }

    public function getItemsByCategoryId($id) {
        $items = $this->contentDao->getContentsByCategory($id);
        return array_map([$this, 'mapToCard'], $items);
    }

    public function getItemsByCategoryName($categoryNameOrSlug) {
        $category = $this->contentDao->getCategoryBySlug($categoryNameOrSlug);
        return $category ? $this->getCategoryContent($category['id']) : [];
    }

    public function getItemByName($name) {
        $row = $this->contentDao->getContentByName($name);
        return $row ? $this->mapToCard($row) : null;
    }

    public function getCategoryByItemId($itemId) {
        return $this->contentDao->getCategoryByItemId($itemId);
    }

    private function mapCategoryToCard($category) {
        return [
            'id'    => $category['id'],
            'name'  => $category['nombre'],
            'type'  => 'category',
            'img'   => !empty($category['imagen']) ? $category['imagen'] : 'assets/img/default_category.png', 
            'badge' => 'Sección',
            'slug'  => str_replace(' ', '-', strtolower($category['nombre']))
        ];
    }

    public function mapToCard($row) {
        return [
            'id'          => $row['id'],
            'name'        => $row['nombre'],
            'type'        => 'contenido',
            'img'         => $row['imagen'],
            'badge'       => $row['clasificacion'],
            'description' => $row['descripcion_breve'],
            'date'        => $row['fecha_publicacion']
        ];
    }
}