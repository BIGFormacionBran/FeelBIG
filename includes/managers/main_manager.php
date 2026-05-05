<?php
require_once __DIR__ . '/content_manager.php';
require_once __DIR__ . '/../daos/UsuarioDAO.php';
require_once __DIR__ . '/../daos/RegistroPendienteDAO.php';
require_once __DIR__ . '/mail_manager.php';

class MainManager {
    private $contentManager;
    private $usuarioDao;
    private $registroPendienteDao; 
    private $mailManager;
    
    public function __construct() {
        $this->contentManager = new ContentManager();
        $this->usuarioDao = new UsuarioDAO();
        $this->registroPendienteDao = new RegistroPendienteDAO(); 
        $this->mailManager = new MailManager();
    }

    // --- LÓGICA DE REGISTRO TEMPORAL (Responsabilidad de MainManager) ---
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
            if ($this->usuarioDao->registrar_con_hash($datos['nombre'], $datos['correo'], $datos['password'])) {
                $this->registroPendienteDao->borrar_temporal($correo);
                return true;
            }
        }
        return false;
    }

    // --- NAVEGACIÓN Y ESTRUCTURA (Responsabilidad de MainManager) ---
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

    // --- GESTIÓN DE USUARIOS (Responsabilidad de MainManager) ---
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