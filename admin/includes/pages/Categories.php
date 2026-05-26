<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $admin = new AdminManager();
    $admin->handleRequest($_POST);
}

$admin = new AdminManager();
$categorias = $admin->contents->listAllCategoriesOrdered();
$options = ["null" => "-- Categoría Principal --"];

function flatten_categories_for_select($items, &$out, $depth = 0) {
    foreach ($items as $it) {
        $prefix = $depth > 0 ? str_repeat('&nbsp;&nbsp;', $depth) . '└─ ' : '';
        $out[$it['id']] = $prefix . ($it['nombre'] ?? '');
        if (!empty($it['children'])) {
            flatten_categories_for_select($it['children'], $out, $depth + 1);
        }
    }
}

flatten_categories_for_select($categorias, $options, 0);

$config = [
    'title'       => 'Gestión de Categorías',
    'entity'      => 'Categoría',
    'data'        => $categorias,
    'can_delete'  => true,
    'show_search' => false,
    'fields'      => [
        'id'       => ['label' => 'ID',       'type' => 'hidden', 'list' => true],
        'nombre'   => ['label' => 'Nombre',   'type' => 'text',   'list' => true, 'required' => true],
        'id_padre' => ['label' => 'Padre',    'type' => 'select', 'options' => $options, 'list' => false],
        'imagen'   => ['label' => 'Imagen',   'type' => 'media',  'list' => false]
    ]
];

require_once __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();
?>