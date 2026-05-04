<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $manager = new MainManager();
    
    // Guardamos en sesión por si hay error
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'usuario' => $correo,
        'password' => $pass
    ];

    // Intentamos iniciar registro
    $resultado = $manager->iniciar_registro($nombre, $correo, $pass);

    if ($resultado === true) {
        $_SESSION['temp_email'] = $correo;
        unset($_SESSION['form_data']);
        header("Location: /confirmacion"); // Asegúrate de que el slug sea /confirmacion
        exit();
    } else {
        // Si entra aquí es que falló el Mail o la DB
        header("Location: /registro?error=db"); 
        exit();
    }
}