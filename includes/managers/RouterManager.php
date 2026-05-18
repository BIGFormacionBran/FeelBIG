<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';
require_once __DIR__ . '/UserManager.php';

function getPageConfigManager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';
    global $routeParts;
    $subPage = $routeParts[1] ?? '';

    LoggerUtil::info("RouterManager: Iniciando para PAGE: '$page' | SUBPAGE: '$subPage' | URI: " . $_SERVER['REQUEST_URI']);

    if (preg_match('/\.(jpg|jpeg|png|gif|ico|css|js|svg)(\?.*)?$/i', $_SERVER['REQUEST_URI'])) {        
        LoggerUtil::info("RouterManager: Detectado archivo estático en URI: " . $_SERVER['REQUEST_URI']);
        
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $fullAssetPath = realpath(__DIR__ . '/../../' . ltrim($requestPath, '/'));

        if ($fullAssetPath && file_exists($fullAssetPath)) {
            header('Content-Type: ' . ($page === 'js' ? 'application/javascript' : 'text/css'));
            readfile($fullAssetPath);
            exit;
        }

        $userManager = new UserManager();
        $userRole = $_SESSION['user_role'] ?? 0;

        if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false && !$userManager->isAdmin($userRole)) {
            LoggerUtil::error("RouterManager: BLOQUEO ASSET ADMIN - Usuario no es admin (Rol: $userRole)");
            return ['path' => 'includes/pages/Error.php', 'title' => '403', 'isRoot' => false, 'errorCode' => '403'];
        }

        LoggerUtil::info("RouterManager: Ejecutando EXIT para asset estático.");
        exit;
    }

    if ($page === 'admin') {
        $path = empty($subPage) ? 'admin/index.php' : 'admin/includes/pages/' . ucwords($subPage) . '.php';
    } else {
        $path = in_array($page, ['login', 'register', 'register-confirm']) ? 'includes/pages/AuthView.php' : 'includes/pages/' . ucwords($page) . '.php';
    }

    $fullPath = realpath(__DIR__ . '/../../' . $path);
    LoggerUtil::info("RouterManager: Buscando archivo físico en: " . ($fullPath ?: "PATH NO RESUELTO ($path)"));

    if ($fullPath && file_exists($fullPath)) {
        LoggerUtil::info("RouterManager: Archivo encontrado, cargando página.");
        return [
            'path'      => $path,
            'title'     => ($page === 'error') ? "Error " . ($routeParts[1] ?? '404') : ucwords(str_replace(['-', '_'], ' ', $subPage ?: $page)),
            'isRoot'    => in_array($page, ['home', 'admin', '']) && empty($subPage),
            'errorCode' => ($page === 'error') ? ($routeParts[1] ?? '404') : null
        ];
    }

    if (!empty($page) && !in_array($page, ['assets', 'admin', 'includes'])) {
        LoggerUtil::info("RouterManager: No es archivo físico, buscando slug '$page' en DB.");
        $manager = new ContentManager();
        $categoryData = $manager->contentDao->getCategoryBySlug($page);

        if ($categoryData) {
            LoggerUtil::info("RouterManager: Categoría encontrada en DB: " . $categoryData['nombre']);
            return [
                'path'   => (count($routeParts) >= 2) ? 'includes/pages/IndividualView.php' : 'includes/pages/main_nav/CategoryView.php',
                'title'  => $categoryData['nombre'],
                'isRoot' => false
            ];
        }
    }

    LoggerUtil::error("RouterManager: No se pudo rutear nada para '$page'. Devolviendo 404.");
    return [
        'path'      => 'includes/pages/Error.php',
        'title'     => 'Error 404',
        'isRoot'    => false,
        'errorCode' => '404'
    ];
}