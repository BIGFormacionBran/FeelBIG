<?php
require_once __DIR__ . '/../managers/AdminManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

$admin = new AdminManager();
$status = null;
$message = null;

LoggerUtil::info("VIEW_LOAD: Cargando Contents.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LoggerUtil::info("VIEW_POST (Contents): Datos recibidos: " . json_encode($_POST));

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

    $result = $admin->handleRequest($_POST);
    $status  = $result ? 'success' : 'error';
    $message = $result ? null : 'No se pudo completar la operación en contenidos.';
}

$contenidos = $admin->contents->listAllContents();
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
        'categoria_nombre'  => ['label' => 'Categoría',   'type' => 'none',     'list' => true],
        'id_categoria'      => ['label' => 'Asignar Cat.', 'type' => 'select',   'options' => $cat_options, 'list' => false, 'required' => true],
        'clasificacion'     => ['label' => 'Clasificación','type' => 'text',     'list' => true],
        'imagen'            => ['label' => 'Portada',      'type' => 'media',    'list' => false],
        'video'             => ['label' => 'Video',        'type' => 'media',    'list' => false],
        'descripcion_breve' => ['label' => 'Descripción', 'type' => 'textarea', 'list' => false]
    ]
];

include __DIR__ . '/../components/ListItems.php';
$admin->renderFileManager();