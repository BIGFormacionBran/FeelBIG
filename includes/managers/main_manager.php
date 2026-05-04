<?php
require_once __DIR__ . '/../daos/ContenidoDAO.php';
require_once __DIR__ . '/../daos/UsuarioDAO.php';

class MainManager {
    private $contenidoDao;
    private $usuarioDao;

    public function __construct() {
        $this->contenidoDao = new ContenidoDAO();
        $this->usuarioDao = new UsuarioDAO();
    }

    // --- CONTENIDO & HOME ---
    public function get_home_data() {
        $secciones = $this->contenidoDao->get_home_structure();
        $final_data = [];
        foreach ($secciones as $seccion) {
            $items = $this->contenidoDao->get_contenidos_by_categoria($seccion['id']);
            if ($items) {
                $final_data[] = [
                    'title' => $seccion['nombre'],
                    'slug'  => str_replace(' ', '-', strtolower($seccion['nombre'])),
                    'items' => array_map([$this, 'map_to_card'], $items)
                ];
            }
        }
        return $final_data;
    }

    public function get_item_by_name($name) {
        $row = $this->contenidoDao->get_contenido_por_nombre($name);
        return $row ? $this->map_to_card($row) : null;
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

        $items = $this->contenidoDao->get_contenidos_by_categoria($targetId);
        return array_map([$this, 'map_to_card'], $items);
    }

    // NUEVO MÉTODO AÑADIDO:
    public function get_category_by_item_id($itemId) {
        // Obtenemos la categoría a la que pertenece un item
        return $this->contenidoDao->get_categoria_por_item_id($itemId);
    }

    private function map_to_card($row) {
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

    // --- NAVEGACIÓN ---
    public function get_main_menu() {
        $categorias = $this->contenidoDao->get_home_structure();
        return array_map(function($cat) {
            return [
                'id'          => $cat['id'],
                'slug'        => str_replace(' ', '-', strtolower($cat['nombre'])),
                'title'       => $cat['nombre'],
                'descripcion' => $cat['descripcion'] 
            ];
        }, $categorias);
    }

    public function get_breadcrumbs($currentPage, $routeParts) {
        // No mostrar en páginas raíz de sistema
        if (in_array($currentPage, ['home', 'login', 'registro', 'configuracion'])) return null;

        $breadcrumbs = [['title' => 'Home', 'link' => '/home']];

        // Caso Vista Individual: /categoria-slug/nombre-contenido
        if ($currentPage === 'individual_view' && isset($routeParts[1])) {
            $categorySlug = $routeParts[0]; // ej: minijuegos
            $itemSlug = $routeParts[1];     // ej: Salud-Canarias-Gaming

            // Intentamos obtener el nombre real de la categoría para el título
            $categoryTitle = ucwords(str_replace('-', ' ', $categorySlug));
            
            // 1. Añadimos el nivel de la categoría (ej: Minijuegos)
            $breadcrumbs[] = [
                'title' => $categoryTitle, 
                'link' => '/' . $categorySlug
            ];

            // 2. Añadimos el nivel del contenido actual (ej: Salud Canarias Gaming)
            $breadcrumbs[] = [
                'title' => str_replace('-', ' ', $itemSlug), 
                'link' => null
            ];
        } 
        // Caso Vista de Categoría o Páginas Simples: /alimentacion
        else {
            $breadcrumbs[] = [
                'title' => ucwords(str_replace('-', ' ', $currentPage)), 
                'link' => null
            ];
        }

        return $breadcrumbs;
    }

    // --- USUARIOS ---
    public function login($correo, $pass) {
        return $this->usuarioDao->login($correo, $pass);
    }

    public function registrar($nombre, $correo, $pass) {
        return $this->usuarioDao->registrar($nombre, $correo, $pass);
    }

    public function get_user_by_id($id) {
        return $this->usuarioDao->getById($id);
    }
}