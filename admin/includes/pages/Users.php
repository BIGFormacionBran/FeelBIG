<?php
require_once __DIR__ . '/../managers/AdminManager.php';

$admin = new AdminManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $admin->handleRequest($_POST);
}

// Capturamos filtros
$filters = [
    'search' => $_GET['search'] ?? '',
    'order'  => $_GET['order'] ?? 'ASC'
];

$usuarios = $admin->users->listAllUsers($filters);

$role_options = [
    "1" => "ADMIN",
    "2" => "DEVELOPER",
    "3" => "USER"
];

$config = [
    'title'      => 'Gestión de Usuarios',
    'entity'     => 'Usuario',
    'data'       => $usuarios,
    'can_delete' => false,
    'fields'     => [
        'id'                 => ['label' => 'ID',           'type' => 'hidden', 'list' => true],
        'nombre'             => ['label' => 'Nombre',       'type' => 'none',   'list' => true],
        'correo'             => ['label' => 'Email',        'type' => 'none',   'list' => true],
        'tipo_cuenta_nombre' => ['label' => 'Tipo Actual',  'type' => 'none',   'list' => true],
        'id_tipo_cuenta'     => ['label' => 'Cambiar Tipo', 'type' => 'select', 'options' => $role_options, 'list' => false, 'required' => true]
    ]
];

require_once __DIR__ . '/../components/ListItems.php';
?>