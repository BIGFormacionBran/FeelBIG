<?php
require_once 'includes/managers/MainManager.php';
require_once 'includes/utils/LoggerUtil.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $manager = new MainManager();
        $user = $manager->login($identifier, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['formData']);
            header("Location: /home");
            exit();
        } else {
            $_SESSION['formData'] = ['usuario' => $identifier, 'password' => $password];
            header("Location: /login?error=1");
            exit();
        }
    } catch (Exception $e) {
        LoggerUtil::error("Auth.php: CRITICAL EXCEPTION: " . $e->getMessage());
        header("Location: /login?error=db");
        exit();
    }
}