<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

$admin = new AdminManager();
$status = null;
$message = null;

LoggerUtil::info("VIEW_LOAD: Cargando Categories.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LoggerUtil::info("VIEW_POST (Categories): Datos recibidos: " . json_encode($_POST));

    // Manejo de subida AJAX para el File Manager
    if (($_POST['action'] ?? '') === 'fm-upload') {
        while (ob_get_level()) ob_end_clean();
        $result = $admin->handleRequest($_POST);
        header('Content-Type: application/json');
        echo json_encode($result
            ? ['success' => true, 'path' => $result]
            : ['success' => false, 'message' => 'Error al subir el archivo.']
        );
        exit;
    }

    // Procesar acciones estándar (add, edit, delete)
    $result = $admin->handleRequest($_POST);
    $status  = $result ? 'success' : 'error';
    $message = $result ? null : 'No se pudo completar la operación en categorías.';
}

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
        'id_padre' => ['label' => 'Superior', 'type' => 'select', 'options' => $options, 'list' => false],
        'imagen'   => ['label' => 'Imagen',   'type' => 'media',  'list' => false]
    ]
];

include __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();