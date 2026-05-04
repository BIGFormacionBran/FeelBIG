<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    $manager = new MainManager();
    
    if ($manager->registrar($nombre, $correo, $pass)) {
        $user = $manager->login($correo, $pass); 
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['form_data']);
            header("Location: /home");
            exit();
        }
    }
    
    // Guardamos los datos en sesión para no perderlos
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'usuario' => $correo
    ];
    
    header("Location: /registro?error=1");
    exit();
}