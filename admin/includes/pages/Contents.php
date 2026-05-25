<?php
require_once __DIR__ . '/../managers/AdminManager.php';

ob_start();
$admin = new AdminManager();

// Procesar peticiones AJAX locales de admin antes de renderizar HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $admin->handleRequest($_POST);
    // handleRequest hace echo/json y exit cuando corresponde
}

// Bloque de salida manual eliminado para dejar que AdminManager gestione el flujo AJAX
ob_end_clean();

$contenidos = $admin->contents->listAllContents();

// Usar categorías ordenadas en árbol
$categorias = $admin->contents->listAllCategoriesOrdered();

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

require_once __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();