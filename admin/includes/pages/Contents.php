<?php
// ... inicialización ...
$contenidos = $admin->contents->listAllContents();

$config = [
    'title'  => 'Gestión de Contenidos',
    'entity' => 'Contenido',
    'data'   => $contenidos,
    'fields' => [
        'id'                => ['label' => 'ID', 'type' => 'hidden', 'list' => true],
        'nombre'            => ['label' => 'Título', 'type' => 'text', 'list' => true, 'required' => true],
        'categoria_nombre'  => ['label' => 'Categoría', 'type' => 'none', 'list' => true],
        'id_categoria'      => ['label' => 'Categoría', 'type' => 'select', 'options' => $cat_options, 'list' => false],
        'clasificacion'     => ['label' => 'Clasificación', 'type' => 'text', 'list' => true],
        'imagen'            => ['label' => 'Portada', 'type' => 'media', 'list' => false],
        'video'             => ['label' => 'Video', 'type' => 'media', 'list' => false],
        'descripcion_breve' => ['label' => 'Descripción', 'type' => 'textarea', 'list' => false]
    ]
];

include __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();
?>