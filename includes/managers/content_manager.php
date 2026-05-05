<?php
require_once __DIR__ . '/../daos/ContenidoDAO.php';
require_once __DIR__ . '/../utils/logger_util.php';

class ContentManager {
    private $contenidoDao;

    public function __construct() {
        try {
            $this->contenidoDao = new ContenidoDAO();
            Logger::info("ContentManager: DAO de contenido instanciado correctamente.");
        } catch (Exception $e) {
            Logger::error("ContentManager: Error al instanciar ContenidoDAO: " . $e->getMessage());
        }
    }

    public function get_home_structure() {
        Logger::info("ContentManager: Solicitando estructura de home.");
        $res = $this->contenidoDao->get_home_structure();
        Logger::info("ContentManager: Se obtuvieron " . count($res) . " categorías.");
        return $res;
    }

    public function get_items_by_category_id($id) {
        Logger::info("ContentManager: Obteniendo items para ID categoría: $id");
        $items = $this->contenidoDao->get_contenidos_by_categoria($id);
        $mapped = array_map([$this, 'map_to_card'], $items);
        Logger::info("ContentManager: Mapeados " . count($mapped) . " items para categoría $id.");
        return $mapped;
    }

    public function get_items_by_category_name($catName) {
        Logger::info("ContentManager: Buscando items por nombre de categoría: '$catName'");
        $categorias = $this->get_home_structure();
        $targetId = null;

        foreach ($categorias as $cat) {
            if (strtolower($cat['nombre']) === strtolower($catName)) {
                $targetId = $cat['id'];
                break;
            }
        }

        if (!$targetId) {
            Logger::error("ContentManager: No se encontró la categoría '$catName'.");
            return [];
        }

        return $this->get_items_by_category_id($targetId);
    }

    public function get_item_by_name($name) {
        Logger::info("ContentManager: Buscando item por nombre: '$name'");
        $row = $this->contenidoDao->get_contenido_por_nombre($name);
        if (!$row) {
            Logger::error("ContentManager: Item '$name' no encontrado.");
            return null;
        }
        return $this->map_to_card($row);
    }

    public function get_category_by_item_id($itemId) {
        Logger::info("ContentManager: Buscando categoría del item ID: $itemId");
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