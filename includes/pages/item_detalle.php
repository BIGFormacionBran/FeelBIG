<?php
require_once 'includes/utils/individual_render_util.php';

// 1. Capturamos lo que viene por URL
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

// 2. Simulación de "Base de Datos" o búsqueda de datos
// En el futuro, aquí harías un SELECT * FROM $type WHERE id = $id
$datos_para_renderizar = [];

if ($type === 'minijuego') {
    // Ejemplo de cómo transformar datos específicos a la estructura que entiende el renderizador
    $datos_para_renderizar = [
        "title"       => "Nombre del Minijuego " . $id,
        "img"         => "game1.jpg",
        "badge"       => "Interactivo",
        "description" => "<p>Esta es la descripción detallada cargada automáticamente.</p>",
        "extra_info"  => [
            "Dificultad" => "Media",
            "Tiempo"     => "5 min",
            "Categoría"  => "Salud"
        ]
    ];
}

// 3. Pasamos los datos al renderizador universal
render_individual_view_util($datos_para_renderizar);