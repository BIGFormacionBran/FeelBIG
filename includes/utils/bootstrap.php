<?php
session_start();

// Definimos la raíz del proyecto
$base_path = dirname(__DIR__, 2);

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

// 5. Cargar Configuración de página (Funciones dentro de main_manager o router_manager)
$pageConfig = get_page_config_manager($page);
$main_css = get_minified_css_util();