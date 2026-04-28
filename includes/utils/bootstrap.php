<?php
session_start();

// 1. Cargar CAPA UTILS
require_once 'includes/utils/assets_util.php';
require_once 'includes/utils/render_util.php';
require_once 'includes/utils/individual_render_util.php';

// 2. Cargar CAPA MANAGERS
require_once 'includes/managers/router_manager.php';
require_once 'includes/managers/navigation_manager.php';

// 3. Lógica de Enrutamiento Amigable
// Capturamos 'route' (definida en .htaccess). Si no existe, es 'home'.
$rawRoute = $_GET['route'] ?? 'home';
$cleanRoute = trim($rawRoute, '/');

// Separamos por si hay subrutas (ej: minijuegos/poker)
$routeParts = explode('/', $cleanRoute);
$page = (empty($routeParts[0])) ? 'home' : $routeParts[0];

$auth_pages = ['login', 'registro'];

// 4. Lógica de Sesión
if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
    // Redirigimos a la nueva URL amigable
    header("Location: /login");
    exit();
}

$pageConfig = get_page_config_manager($page);
$main_css = get_minified_css_util();