<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

$admin = new AdminManager();

// Ya no hay IF POST, ni handleRequest, ni exit;
// El AdminManager o un controlador frontal ya debieron procesar los datos antes.

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
        'imagen'   => ['label' => 'Imagen',   'type' => 'image',  'list' => true]
    ]
];

// Corregido: Se incluye ListItems.php en lugar de un archivo inventado
require_once __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();