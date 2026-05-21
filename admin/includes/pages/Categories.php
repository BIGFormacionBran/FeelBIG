<?php
require_once __DIR__ . '/../managers/AdminManager.php';
$admin = new AdminManager();

// ... Lógica de control (POST) ...

$categorias = $admin->contents->listAllCategoriesOrdered();
$options = ["null" => "-- Categoría Principal --"];
foreach($categorias as $c) { $options[$c['id']] = $c['nombre']; }

$config = [
    'title'  => 'Gestión de Categorías',
    'entity' => 'Categoría',
    'data'   => $categorias,
    'fields' => [
        'id'       => ['label' => 'ID', 'type' => 'hidden', 'list' => true],
        'nombre'   => ['label' => 'Nombre', 'type' => 'text', 'list' => true, 'required' => true],
        'id_padre' => ['label' => 'Superior', 'type' => 'select', 'options' => $options, 'list' => false],
        'imagen'   => ['label' => 'Imagen', 'type' => 'media', 'list' => false]
    ]
];

include __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();
?>