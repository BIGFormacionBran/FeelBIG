<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

$admin = new AdminManager();

// PRIORIDAD 1: Si es AJAX, procesar y CORTAR ejecución inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'fm-upload' || ($_POST['action'] ?? '') === 'fm-delete-file')) {
    while (ob_get_level()) ob_end_clean(); // Limpiar CUALQUIER espacio en blanco previo
    header('Content-Type: application/json');
    $result = $admin->handleRequest($_POST);
    
    if (($_POST['action'] ?? '') === 'fm-upload') {
        echo json_encode($result ? ['success' => true, 'path' => $result] : ['success' => false, 'message' => 'Error al subir']);
    } else {
        echo json_encode(['success' => (bool)$result]);
    }
    exit; // Detener el script aquí para que no se pegue el HTML abajo
}

// PRIORIDAD 2: Procesar acciones normales (Formularios de guardado)
$status = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $admin->handleRequest($_POST);
    $status = $result ? 'success' : 'error';
}

$categorias = $admin->contents->listAllCategoriesOrdered();
$options = ["null" => "-- Categoría Principal --"];
foreach ($categorias as $c) { $options[$c['id']] = $c['nombre']; }

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