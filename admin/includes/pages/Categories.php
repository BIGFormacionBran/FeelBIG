<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

ob_start();
$admin = new AdminManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['fm-upload', 'fm-delete-file', 'add', 'edit', 'delete'])) {
    ob_end_clean();
    exit;
}

ob_end_clean();

$categorias = $admin->contents->listAllCategoriesOrdered();
$options = ["null" => "-- Categoría Principal --"];
foreach ($categorias as $c) {
    $options[$c['id']] = $c['nombre'];
}

$config = [
    'title'  => 'Gestión de Categorías',
    'entity' => 'Categoría',
    'data'   => $categorias,
    'fields' => [
        'id'       => ['label' => 'ID',       'type' => 'hidden', 'list' => true],
        'nombre'   => ['label' => 'Nombre',   'type' => 'text',   'list' => true, 'required' => true],
        'id_padre' => ['label' => 'Padre',    'type' => 'select', 'options' => $options, 'list' => false],
        'imagen'   => ['label' => 'Imagen',   'type' => 'media',  'list' => false]
    ]
];

require_once __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();
