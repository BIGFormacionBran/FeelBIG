<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $manager = new MainManager();
    
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'usuario' => $correo,
        'password' => $pass
    ];

    $resultado = $manager->iniciar_registro($nombre, $correo, $pass);

    if ($resultado === true) {
        $_SESSION['temp_email'] = $correo;
        unset($_SESSION['form_data']);
        header("Location: /register-confirm");
        exit();
    } else {
        // Si falla, es probable que sea el SMTP (según tus logs)
        header("Location: /register?error=mail"); 
        exit();
    }
}