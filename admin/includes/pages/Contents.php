<?php
require_once __DIR__ . '/../managers/AdminManager.php';
$admin = new AdminManager();

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin->handleRequest($_POST);
}

$contenidos = $admin->contents->listAllContents();
$categorias = $admin->contents->listAllCategoriesOrdered();

// Preparar opciones para el select de categorías
$cat_options = [];
foreach($categorias as $c) { 
    $cat_options[$c['id']] = $c['nombre']; 
}

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