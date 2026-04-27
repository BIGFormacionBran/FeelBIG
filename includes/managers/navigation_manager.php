<?php
function get_main_menu_manager() {
    $path = __DIR__ . '/../pages/main_nav';
    if (!is_dir($path)) return [];
    $files = scandir($path);
    $menu = [];
    foreach ($files as $file) {
        if (strpos($file, '.php') !== false) {
            $slug = str_replace('.php', '', $file);
            $menu[] = [
                'slug' => $slug,
                'title' => ucwords(str_replace('-', ' ', $slug))
            ];
        }
    }
    return $menu;
}

function get_breadcrumbs_manager($currentPage) {
    $exclude = ['home', 'login', 'registro'];
    if (in_array($currentPage, $exclude)) return null;

    return [
        ['title' => 'Home', 'link' => 'index.php?page=home'],
        ['title' => ucwords(str_replace('-', ' ', $currentPage)), 'link' => null]
    ];
}