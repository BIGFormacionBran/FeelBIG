<?php
session_start();
require_once 'includes/managers/main_manager.php';
require_once 'includes/utils/logger_util.php'; // Asegúrate de incluirlo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];

    try {
        Logger::info("auth.php: Intento de login iniciado para usuario: $usuario");
        $manager = new MainManager();
        $user = $manager->login($usuario, $pass);

        if ($user) {
            Logger::info("auth.php: Login exitoso para ID: " . $user['id']);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['form_data']);
            header("Location: /home");
        } else {
            Logger::error("auth.php: Login fallido (Credenciales incorrectas) para: $usuario");
            $_SESSION['form_data'] = ['usuario' => $usuario, 'password' => $pass];
            header("Location: /login?error=1");
        }
    } catch (Exception $e) {
        Logger::error("auth.php: EXCEPCIÓN CRÍTICA: " . $e->getMessage());
        header("Location: /login?error=db");
    }
    exit();
}