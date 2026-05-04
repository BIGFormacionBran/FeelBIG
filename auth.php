<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];

    try {
        $manager = new MainManager();
        $user = $manager->login($usuario, $pass);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['form_data']); // Limpiamos si el login es exitoso
            header("Location: /home");
        } else {
            $_SESSION['form_data'] = [
                'usuario' => $usuario,
                'password' => $pass
            ];
            header("Location: /login?error=1");
        }
    } catch (Exception $e) {
        header("Location: /login?error=db");
    }
    exit();
}