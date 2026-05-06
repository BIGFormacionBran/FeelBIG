<?php
// feelbig\process_registro.php
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
        $_SESSION['temp_name'] = $nombre; // Guardamos el nombre para el reenvío
        unset($_SESSION['form_data']);
        header("Location: /register-confirm");
        exit();
    } else {
        header("Location: /register?error=mail"); 
        exit();
    }
}