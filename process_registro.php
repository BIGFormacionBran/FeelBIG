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
            header("Location: /home");
            exit();
        }
    }
    
    header("Location: /registro?error=1");
    exit();
}