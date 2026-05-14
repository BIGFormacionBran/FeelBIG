<?php
require_once 'includes/managers/MainManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['nombre'] ?? '';
    $email = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    $manager = new MainManager();
    
    $_SESSION['formData'] = [
        'nombre' => $name,
        'usuario' => $email,
        'password' => $password
    ];

    $result = $manager->startRegistration($name, $email, $password);

    if ($result === true) {
        $_SESSION['temp_email'] = $email;
        $_SESSION['temp_name'] = $name;
        unset($_SESSION['formData']);
        header("Location: /register-confirm");
        exit();
    } else {
        header("Location: /register?error=mail"); 
        exit();
    }
}