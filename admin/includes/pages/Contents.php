<?php
require_once __DIR__ . '/../managers/AdminManager.php';

$admin = new AdminManager();

// Obtenemos los datos necesarios para la vista
$contenidos = $admin->contents->listAllContents();
$categorias = $admin->contents->listAllCategoriesOrdered();

// Preparamos las opciones para el selector de categorías
$cat_options = [];
foreach ($categorias as $c) { 
    $cat_options[$c['id']] = $c['nombre']; 
}

$config = [
    'title'  => 'Gestión de Contenidos',
    'entity' => 'Contenido',
    'data'   => $contenidos,
    'fields' => [
        'id'                => ['label' => 'ID',           'type' => 'hidden',   'list' => true],
        'nombre'            => ['label' => 'Título',       'type' => 'text',     'list' => true, 'required' => true],
        'categoria_nombre'  => ['label' => 'Categoría',    'type' => 'none',     'list' => true],
        'id_categoria'      => ['label' => 'Asignar Cat.', 'type' => 'select',   'options' => $cat_options, 'list' => false, 'required' => true],
        'clasificacion'     => ['label' => 'Clasificación','type' => 'text',     'list' => true],
        'imagen'            => ['label' => 'Portada',      'type' => 'media',    'list' => false],
        'video'             => ['label' => 'Video',        'type' => 'media',    'list' => false],
        'descripcion_breve' => ['label' => 'Descripción',  'type' => 'textarea', 'list' => false]
    ]
];

// Solo renderizado de componentes
require_once __DIR__ . '/../components/AdminTable.php';
require_once __DIR__ . '/../components/AdminModal.php';
$admin->renderFileManager();