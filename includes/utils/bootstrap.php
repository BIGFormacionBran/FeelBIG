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
$rawRoute = $_GET['route'] ?? 'home';
$cleanRoute = trim($rawRoute, '/');

// Definimos como global para que individual_render_util pueda leer el ID
global $routeParts;
$routeParts = explode('/', $cleanRoute);
$page = (empty($routeParts[0])) ? 'home' : $routeParts[0];

if (count($routeParts) === 2) {
    $page = 'individual_view';
}

$auth_pages = ['login', 'registro'];

// 4. Lógica de Sesión
if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
    header("Location: /login");
    exit();
}

$pageConfig = get_page_config_manager($page);
$main_css = get_minified_css_util();