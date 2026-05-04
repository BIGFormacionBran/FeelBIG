<?php
require_once __DIR__ . '/../daos/ContenidoDAO.php';
require_once __DIR__ . '/../daos/UsuarioDAO.php';
require_once __DIR__ . '/../daos/RegistroPendienteDAO.php'; // NUEVO
require_once __DIR__ . '/mail_manager.php';

class MainManager {
    private $contenidoDao;
    private $usuarioDao;
    private $registroPendienteDao; // NUEVO
    private $mailManager;
    
    public function __construct() {
        $this->contenidoDao = new ContenidoDAO();
        $this->usuarioDao = new UsuarioDAO();
        $this->registroPendienteDao = new RegistroPendienteDAO(); // NUEVO
        $this->mailManager = new MailManager();
    }

    // --- NUEVA LÓGICA DE REGISTRO TEMPORAL ---
    public function iniciar_registro($nombre, $correo, $pass) {
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        if ($this->registroPendienteDao->crear_temporal($nombre, $correo, $pass, $codigo)) {
            return $this->mailManager->enviarConfirmacionRegistro($correo, $nombre, $codigo);
        }

        Logger::error("No se pudo crear el registro temporal en la base de datos para: $correo");
        return false;
    }

    public function confirmar_registro($correo, $codigo) {
        $datos = $this->registroPendienteDao->obtener_y_validar($correo, $codigo);
        if ($datos) {
            // Usamos el password que ya viene encriptado de la tabla temporal
            if ($this->usuarioDao->registrar_con_hash($datos['nombre'], $datos['correo'], $datos['password'])) {
                $this->registroPendienteDao->borrar_temporal($correo);
                return true;
            }
        }
        return false;
    }

    // --- CONTENIDO & HOME (IGUAL QUE ANTES) ---
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

    public function get_category_by_item_id($itemId) {
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

    // --- NAVEGACIÓN (IGUAL QUE ANTES) ---
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
        if (in_array($currentPage, ['home', 'login', 'registro', 'configuracion'])) return null;
        $breadcrumbs = [['title' => 'Home', 'link' => '/home']];
        if ($currentPage === 'individual_view' && isset($routeParts[1])) {
            $categorySlug = $routeParts[0];
            $itemSlug = $routeParts[1];
            $categoryTitle = ucwords(str_replace('-', ' ', $categorySlug));
            $breadcrumbs[] = ['title' => $categoryTitle, 'link' => '/' . $categorySlug];
            $breadcrumbs[] = ['title' => str_replace('-', ' ', $itemSlug), 'link' => null];
        } else {
            $breadcrumbs[] = ['title' => ucwords(str_replace('-', ' ', $currentPage)), 'link' => null];
        }
        return $breadcrumbs;
    }

    // --- USUARIOS (IGUAL QUE ANTES) ---
    public function login($correo, $pass) {
        return $this->usuarioDao->login($correo, $pass);
    }

    public function registrar($nombre, $correo, $pass) {
        return $this->usuarioDao->registrar($nombre, $correo, $pass);
    }

    public function get_user_by_id($id) {
        return $this->usuarioDao->getById($id);
    }

    public function update_user_profile($id, $nombre, $correo, $password) {
        $user = $this->get_user_by_id($id);
        if (!$user) return "Error: Usuario no encontrado.";
        $finalNombre = !empty($nombre) ? $nombre : $user['nombre'];
        $finalCorreo = !empty($correo) ? $correo : $user['correo'];
        $finalPass = !empty($password) ? $password : null;
        $resultado = $this->usuarioDao->actualizarPerfil($id, $finalNombre, $finalCorreo, $finalPass);
        return $resultado ? "Perfil actualizado correctamente." : "Error al actualizar el perfil.";
    }
}