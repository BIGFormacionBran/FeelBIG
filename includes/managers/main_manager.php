<?php
require_once __DIR__ . '/../daos/UsuarioDAO.php';
require_once __DIR__ . '/../daos/RegistroPendienteDAO.php';
require_once __DIR__ . '/mail_manager.php';
require_once __DIR__ . '/../utils/logger_util.php';

class MainManager {
    private $usuarioDao;
    private $registroPendienteDao; 
    private $mailManager;
    
    public function __construct() {
        Logger::info("MainManager: Inicializando manager principal.");
        $this->usuarioDao = new UsuarioDAO();
        $this->registroPendienteDao = new RegistroPendienteDAO(); 
        $this->mailManager = new MailManager();
    }

    public function iniciar_registro($nombre, $correo, $pass) {
        Logger::info("MainManager: Iniciando registro para $correo");
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        if ($this->registroPendienteDao->crear_temporal($nombre, $correo, $pass, $codigo)) {
            return $this->mailManager->enviarConfirmacionRegistro($correo, $nombre, $codigo);
        }
        Logger::error("MainManager: Error al crear registro temporal para $correo");
        return false;
    }

    public function confirmar_registro($correo, $codigo) {
        Logger::info("MainManager: Confirmando registro para $correo");
        $datos = $this->registroPendienteDao->obtener_y_validar($correo, $codigo);
        if ($datos) {
            if ($this->usuarioDao->registrar_con_hash($datos['nombre'], $datos['correo'], $datos['password'])) {
                $this->registroPendienteDao->borrar_temporal($correo);
                Logger::info("MainManager: Registro confirmado exitosamente.");
                return true;
            }
        }
        Logger::error("MainManager: Fallo en la confirmación de registro.");
        return false;
    }

    public function get_breadcrumbs($currentPage, $routeParts) {
        Logger::info("MainManager: Generando breadcrumbs para $currentPage");
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

    public function login($correo, $pass) {
        Logger::info("MainManager: Intento de login para $correo");
        return $this->usuarioDao->login($correo, $pass);
    }

    public function get_user_by_id($id) {
        return $this->usuarioDao->getById($id);
    }

    public function update_user_profile($id, $nombre, $correo, $password) {
        Logger::info("MainManager: Actualizando perfil de usuario ID $id");
        $user = $this->get_user_by_id($id);
        if (!$user) return "Error: Usuario no encontrado.";
        $finalNombre = !empty($nombre) ? $nombre : $user['nombre'];
        $finalCorreo = !empty($correo) ? $correo : $user['correo'];
        $finalPass = !empty($password) ? $password : null;
        return $this->usuarioDao->actualizarPerfil($id, $finalNombre, $finalCorreo, $finalPass) 
               ? "Perfil actualizado correctamente." : "Error al actualizar.";
    }
}