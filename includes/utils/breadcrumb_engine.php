<?php
function getBreadcrumbs($currentPage) {
    // No mostrar nada en home ni en páginas de auth
    $exclude = ['home', 'login', 'registro'];
    if (in_array($currentPage, $exclude)) return null;

    $crumbs = [];
    
    // El primer nivel siempre es Home
    $crumbs[] = ['title' => 'Home', 'link' => 'index.php?page=home'];

    // Determinar el título de la página actual
    // "actividad-fisica" -> "Actividad Física"
    $cleanTitle = str_replace('-', ' ', $currentPage);
    $cleanTitle = ucwords($cleanTitle);

    // Añadir la página actual como el último eslabón
    $crumbs[] = ['title' => $cleanTitle, 'link' => null];

    return $crumbs;
}