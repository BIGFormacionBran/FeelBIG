<?php
session_start();
require_once 'includes/daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $dao = new UsuarioDAO();
    if ($dao->registrar($nombre, $correo, $pass)) {
        // Auto-login tras registro
        $user = $dao->login($correo, $pass);
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php?page=home");
    } else {
        header("Location: index.php?page=registro&error=1");
    }
    exit();
}