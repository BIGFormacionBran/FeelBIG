<?php

function get_main_menu_manager() {
    $path = __DIR__ . '/../pages/main_nav';
    if (!is_dir($path)) return [];
    
    $menu = [];
    foreach (array_diff(scandir($path), ['.', '..']) as $file) {
        if (str_ends_with($file, '.php')) {
            $slug = str_replace('.php', '', $file);
            $menu[] = [
                'slug'  => $slug,
                'title' => ucwords(str_replace('-', ' ', $slug))
            ];
        }
    }
    return $menu;
}

function get_breadcrumbs_manager($currentPage) {
    global $routeParts;

    if (in_array($currentPage, ['home', 'login', 'registro'])) return null;

    $breadcrumbs = [['title' => 'Home', 'link' => '/home']];

    if ($currentPage === 'individual_view' && isset($routeParts[1])) {
        // Nivel categoría (ej: Minijuegos)
        $breadcrumbs[] = [
            'title' => ucwords(str_replace('-', ' ', $routeParts[0])),
            'link'  => '/' . $routeParts[0]
        ];
        // Nivel detalle (ej: Salud Canarias Gaming)
        $breadcrumbs[] = [
            'title' => str_replace('-', ' ', $routeParts[1]),
            'link'  => null
        ];
    } else {
        // Página estática normal
        $breadcrumbs[] = [
            'title' => ucwords(str_replace('-', ' ', $currentPage)),
            'link'  => null
        ];
    }

    return $breadcrumbs;
}