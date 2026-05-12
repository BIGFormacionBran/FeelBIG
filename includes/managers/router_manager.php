<?php
require_once __DIR__ . '/content_manager.php';

function get_page_config_manager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';

    global $routeParts;

    if ($page === 'error') {
        $errorCode = $GLOBALS['routeParts'][1] ?? '404';

        if(is_numeric($errorCode)) {
            http_response_code((int)$errorCode);
        }
        
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
    } elseif (in_array($page, ['login', 'register', 'register-confirm'])) {
        $fileToLoad = 'includes/pages/auth_view.php';
    } elseif ($page === 'home' || empty($page)) {
        $fileToLoad = 'includes/pages/home.php';
    }

    if ($fileToLoad) {
        return [
            'path'    => $fileToLoad,
            'title'   => ucwords(str_replace(['-', '_'], ' ', $page)),
            'is_root' => ($page === 'home' || empty($page))
        ];
    }

    // 2. BUSQUEDA AUTOMÁTICA EN BASE DE DATOS (Categorías)
    $manager = new ContentManager();
    $catData = $manager->contenidoDao->get_categoria_por_slug($page);

    if ($catData) {
        // Si hay una segunda parte en la URL, es un contenido individual dentro de esta categoría
        if (count($routeParts) >= 2) {
            return [
                'path'    => 'includes/pages/individual_view.php',
                'title'   => ucwords(str_replace('-', ' ', urldecode($routeParts[1]))),
                'is_root' => false
            ];
        }
        // Si no hay segunda parte, cargamos la vista de categoría/grid
        return [
            'path'    => 'includes/pages/main_nav/category_view.php',
            'title'   => $catData['nombre'],
            'is_root' => false
        ];
    }

    // 3. AUTO-FALLBACK
    return [
        'path'       => 'includes/pages/error.php',
        'title'      => 'Error 404',
        'is_root'    => false, // Importante para que el layout manager sepa qué hacer
        'error_code' => '404'
    ];
}