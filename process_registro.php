<?php
session_start();
require_once 'includes/daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $dao = new UsuarioDAO();
    if ($dao->registrar($nombre, $correo, $pass)) {
        // Obtenemos el usuario recién creado para tener su ID y Rol
        $user = $dao->login($correo, $pass); 
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'];
            header("Location: index.php?page=home");
            exit();
        }
    }
    header("Location: index.php?page=registro&error=1");
    exit();
}