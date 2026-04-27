<?php
function getDynamicMenu() {
    // Carpeta específica para los links de la cabecera
    $path = __DIR__ . '/../pages/main_nav';
    
    if (!is_dir($path)) return [];

    $files = scandir($path);
    $menu = [];

    foreach ($files as $file) {
        // Solo archivos .php y que no sean carpetas ocultas
        if (strpos($file, '.php') !== false) {
            $slug = str_replace('.php', '', $file);
            
            // Formatear título: "actividad-fisica" -> "Actividad Física"
            $title = str_replace('-', ' ', $slug);
            $title = ucwords($title);

            $menu[] = [
                'slug' => $slug,
                'title' => $title
            ];
        }
    }
    return $menu;
}