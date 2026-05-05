<?php
require_once __DIR__ . '/../daos/ContenidoDAO.php';

class ContentManager {
    private $contenidoDao;

    public function __construct() {
        $this->contenidoDao = new ContenidoDAO();
    }

    public function get_home_structure() {
        return $this->contenidoDao->get_home_structure();
    }

    public function get_items_by_category_id($id) {
        $items = $this->contenidoDao->get_contenidos_by_categoria($id);
        return array_map([$this, 'map_to_card'], $items);
    }

    public function get_items_by_category_name($catName) {
        $categorias = $this->contenidoDao->get_home_structure();
        $targetId = null;

        foreach ($categorias as $cat) {
            if (strtolower($cat['nombre']) === strtolower($catName)) {
                $targetId = $cat['id'];
                break;
            }
        }

        if (!$targetId) return [];

        return $this->get_items_by_category_id($targetId);
    }

    public function get_item_by_name($name) {
        $row = $this->contenidoDao->get_contenido_por_nombre($name);
        return $row ? $this->map_to_card($row) : null;
    }

    public function get_category_by_item_id($itemId) {
        return $this->contenidoDao->get_categoria_por_item_id($itemId);
    }

    public function map_to_card($row) {
        return [
            'id'          => $row['id'],
            'name'        => $row['nombre'],
            'type'        => 'contenido',
            'img'         => $row['imagen'],
            'badge'       => $row['clasificacion'],
            'description' => $row['descripcion_breve'],
            'fecha'       => $row['fecha_publicacion'],
            'extra_info'  => array_filter([
                "Publicado" => $row['fecha_publicacion'],
                "Video"     => !empty($row['video']) ? "Disponible" : null
            ])
        ];
    }
}