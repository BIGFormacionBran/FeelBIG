<?php
require_once __DIR__ . '/../managers/AdminManager.php';

ob_start();
$admin = new AdminManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $admin->handleRequest($_POST);
}

ob_end_clean();

$contenidos = $admin->contents->listAllContents();
$categorias = $admin->contents->listAllCategoriesOrdered();
$cat_options = [];

function flatten_categories_for_contents($items, &$out, $depth = 0) {
    foreach ($items as $it) {
        $prefix = $depth > 0 ? str_repeat('&nbsp;&nbsp;', $depth) . '└─ ' : '';
        $out[$it['id']] = $prefix . ($it['nombre'] ?? '');
        if (!empty($it['children'])) {
            flatten_categories_for_contents($it['children'], $out, $depth + 1);
        }
    }
}

flatten_categories_for_contents($categorias, $cat_options);

$config = [
    'title'       => 'Gestión de Contenidos',
    'entity'      => 'Contenido',
    'data'        => $contenidos,
    'can_delete'  => true,
    'show_search' => false,
    'fields'      => [
        'id'                => ['label' => 'ID',           'type' => 'hidden',   'list' => true],
        'nombre'            => ['label' => 'Título',       'type' => 'text',     'list' => true, 'required' => true],
        'categoria_nombre'  => ['label' => 'Categoría',    'type' => 'none',     'list' => true],
        'id_categoria'      => ['label' => 'Asignar Cat.', 'type' => 'select',   'options' => $cat_options, 'list' => false, 'required' => true],
        'clasificacion'     => ['label' => 'Clasificación','type' => 'text',     'list' => true],
        'enlace_externo'    => ['label' => 'Enlace Externo','type' => 'text',    'list' => false],
        'fecha_publicacion' => ['label' => 'Fecha Pub.',   'type' => 'none',     'list' => true],
        'imagen'            => ['label' => 'Portada',      'type' => 'media',    'list' => false],
        'video'             => ['label' => 'Video',        'type' => 'media',    'list' => false],
        'descripcion_breve' => ['label' => 'Descripción',  'type' => 'textarea', 'list' => false]
    ]
];

require_once __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();
?>