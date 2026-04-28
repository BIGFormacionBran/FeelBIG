<?php
session_start();
require_once 'includes/daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];

    try {
        $dao = new UsuarioDAO();
        $user = $dao->login($usuario, $pass);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            header("Location: index.php?page=home");
        } else {
            header("Location: index.php?page=login&error=1");
        }
    } catch (Exception $e) {
        // Si el DAO lanza excepción de base de datos
        header("Location: index.php?page=login&error=db");
    }
    exit();
}