<?php
function getPageConfig($page) {
    // Definimos las carpetas donde buscar, en orden de prioridad
    $folders = [
        'includes/pages/main_nav/',
        'includes/pages/'
    ];

    foreach ($folders as $folder) {
        $filePath = $folder . $page . '.php';
        if (file_exists($filePath)) {
            // Generamos un título bonito basado en el nombre del archivo
            $title = ucwords(str_replace('-', ' ', $page));
            
            return [
                'path' => $filePath,
                'title' => $title,
                'is_root' => ($page === 'home')
            ];
        }
    }

    // Si no encuentra nada, devuelve la configuración de Home
    return [
        'path' => 'includes/pages/home.php',
        'title' => 'Home',
        'is_root' => true
    ];
}