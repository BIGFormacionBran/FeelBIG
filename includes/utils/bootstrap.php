<?php
session_start();

// --- GESTIÓN DE ERRORES PRO ---
$base_path = dirname(__DIR__, 2);
$log_file = $base_path . '/logs/php_error.log';

if (!is_dir($base_path . '/logs')) {
    mkdir($base_path . '/logs', 0755, true);
}

ini_set('display_errors', 0); // No mostrar al usuario
ini_set('log_errors', 1);     // Activar log
ini_set('error_log', $log_file); // Ruta del archivo
error_reporting(E_ALL);

// 1. Cargar CAPA UTILS
require_once $base_path . '/includes/utils/assets_util.php';
require_once $base_path . '/includes/utils/render_util.php';
require_once $base_path . '/includes/utils/individual_render_util.php';
require_once $base_path . '/includes/utils/db_util.php';
require_once $base_path . '/includes/utils/card_render_util.php';

// 2. Cargar CAPA MANAGERS
require_once $base_path . '/includes/managers/router_manager.php';
require_once $base_path . '/includes/managers/main_manager.php';

// 3. Lógica de Enrutamiento
$rawRoute = $_GET['route'] ?? 'home';
$cleanRoute = trim($rawRoute, '/');

global $routeParts;
$routeParts = explode('/', $cleanRoute);
$page = (empty($routeParts[0])) ? 'home' : $routeParts[0];

if (count($routeParts) >= 2) {
    $page = 'individual_view';
}

$auth_pages = ['login', 'registro'];

// 4. Lógica de Sesión
if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
    header("Location: /login");
    exit();
}

// 5. Cargar Configuración de página
$pageConfig = get_page_config_manager($page);
$main_css = get_minified_css_util();