<?php
function get_page_config_manager($page) {
    // 1. Definimos rutas estáticas o especiales primero
    $staticPages = [
        'home'            => 'includes/pages/home.php',
        'individual_view' => 'includes/pages/individual_view.php',
        'login'           => 'includes/pages/auth_view.php',
        'registro'        => 'includes/pages/auth_view.php',
        'configuracion'   => 'includes/pages/user_config.php'
    ];

    if (isset($staticPages[$page])) {
        return [
            'path'    => $staticPages[$page],
            'title'   => ucwords(str_replace('-', ' ', $page)),
            'is_root' => ($page === 'home')
        ];
    }

    // 2. Si no es estática, verificamos si es una CATEGORÍA dinámica
    // Usamos el MainManager para validar contra la base de datos
    require_once 'includes/managers/main_manager.php';
    $manager = new MainManager();
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

    // 3. Fallback: Si nada coincide, al Home
    return [
        'path'    => 'includes/pages/home.php',
        'title'   => 'Home',
        'is_root' => true
    ];
}