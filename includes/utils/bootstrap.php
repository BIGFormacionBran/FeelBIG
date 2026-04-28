<?php
session_start();

// 1. Cargar CAPA UTILS
require_once 'includes/utils/assets_util.php';
require_once 'includes/utils/render_util.php';
require_once 'includes/utils/individual_render_util.php';

// 2. Cargar CAPA MANAGERS
require_once 'includes/managers/router_manager.php';
require_once 'includes/managers/navigation_manager.php';

// 3. Lógica de Sesión y Enrutamiento
$page = $_GET['page'] ?? 'home';
$auth_pages = ['login', 'registro'];

if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
    header("Location: index.php?page=login");
    exit();
}

$pageConfig = get_page_config_manager($page);
$main_css = get_minified_css_util();