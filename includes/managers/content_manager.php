<?php
require_once __DIR__ . '/../daos/ContenidoDAO.php';
require_once __DIR__ . '/../utils/logger_util.php';

class ContentManager {
    public $contenidoDao;

    public function __construct() {
        try {
            $this->contenidoDao = new ContenidoDAO();
            Logger::info("ContentManager: DAO de contenido instanciado correctamente.");
        } catch (Exception $e) {
            Logger::error("ContentManager: Error al instanciar ContenidoDAO: " . $e->getMessage());
        }
    }

    public function get_main_menu() {
        $categorias = $this->get_home_structure();
        return array_map(function($cat) {
            return [
                'id'    => $cat['id'],
                'slug'  => str_replace(' ', '-', strtolower($cat['nombre'])),
                'title' => $cat['nombre']
            ];
        }, $categorias);
    }

    public function get_home_structure() {
        return $this->contenidoDao->get_home_structure();
    }

    public function get_items_by_category_slug($slug) {
        $cat = $this->contenidoDao->get_categoria_por_slug($slug);
        return $cat ? $this->get_category_content($cat['id']) : [];
    }

    public function get_category_content($catId) {
        $subcats = $this->contenidoDao->get_subcategorias($catId);
        if (!empty($subcats)) {
            return array_map([$this, 'map_category_to_card'], $subcats);
        }
        return $this->get_items_by_category_id($catId);
    }

    public function get_items_by_category_id($id) {
        $items = $this->contenidoDao->get_contenidos_by_categoria($id);
        return array_map([$this, 'map_to_card'], $items);
    }

    public function get_item_by_name($name) {
        $row = $this->contenidoDao->get_contenido_por_nombre($name);
        return $row ? $this->map_to_card($row) : null;
    }

    public function get_category_by_item_id($itemId) {
        return $this->contenidoDao->get_categoria_por_item_id($itemId);
    }

    private function map_category_to_card($cat) {
        return [
            'id'    => $cat['id'],
            'name'  => $cat['nombre'],
            'type'  => 'category',
            'img'   => 'default_category.png', 
            'badge' => 'Sección',
            'slug'  => str_replace(' ', '-', strtolower($cat['nombre']))
        ];
    }

    public function map_to_card($row) {
        return [
            'id'          => $row['id'],
            'name'        => $row['nombre'],
            'type'        => 'contenido',
            'img'         => $row['imagen'],
            'badge'       => $row['clasificacion'],
            'description' => $row['descripcion_breve'],
            'fecha'       => $row['fecha_publicacion']
        ];
    }
}