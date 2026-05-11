<?php
require_once __DIR__ . '/content_manager.php';

function get_page_config_manager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';
    $errorCode = $GLOBALS['routeParts'][1] ?? '404';

    if ($page === 'error') {
        return [
            'path'       => 'includes/pages/error.php',
            'title'      => 'Error ' . $errorCode,
            'is_root'    => false,
            'error_code' => $errorCode
        ];
    }
    
    // 1. ESCANEO AUTOMÁTICO DE ARCHIVOS
    
    $fileToLoad = null;

    if (file_exists($baseDir . $page . '.php')) {
        $fileToLoad = 'includes/pages/' . $page . '.php';
    } 
    // Manejo de las 3 páginas que usan auth_view de forma automática
    elseif (in_array($page, ['login', 'registro', 'confirmacion'])) {
        $fileToLoad = 'includes/pages/auth_view.php';
    }
    // Si entras a la raíz o pones home
    elseif ($page === 'home' || empty($page)) {
        $fileToLoad = 'includes/pages/home.php';
    }

    // Si encontramos el archivo físicamente, lo devolvemos
    if ($fileToLoad) {
        return [
            'path'    => $fileToLoad,
            'title'   => ucwords(str_replace(['-', '_'], ' ', $page)),
            'is_root' => ($page === 'home' || empty($page))
        ];
    }

    // 2. BUSQUEDA AUTOMÁTICA EN BASE DE DATOS (Categorías)
    $manager = new ContentManager();
    $menu = $manager->get_main_menu();

    foreach ($menu as $cat) {
        if ($cat['slug'] === $page) {
            return [
                'path'    => 'includes/pages/main_nav/category_view.php',
                'title'   => $cat['title'],
                'is_root' => false
            ];
        }
    }

    // 3. AUTO-FALLBACK
    if ($page !== 'error') {
        header("Location: /error/404");
        exit();
    }

    return [
        'path'       => 'includes/pages/error.php',
        'title'      => 'Error 404',
        'is_root'    => false,
        'error_code' => '404'
    ];
}