<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $manager = new MainManager();
    
    // Validamos si el usuario ya existe en la tabla real antes de nada
    // (Opcional, pero recomendado para dar feedback inmediato)
    
    if ($manager->iniciar_registro($nombre, $correo, $pass)) {
        $_SESSION['temp_email'] = $correo;
        unset($_SESSION['form_data']);
        header("Location: /confirmar-cuenta");
        exit();
    }
    
    // Si falla el inicio de registro (ej: error mail o base de datos)
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'usuario' => $correo,
        'password' => $pass
    ];
    
    header("Location: /registro?error=1");
    exit();
}