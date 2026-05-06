<?php
// feelbig\process_confirmacion.php
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $correo = $_SESSION['temp_email'] ?? '';

    if (empty($correo) || empty($codigo)) {
        header("Location: /register?error=codigo");
        exit();
    }

    $manager = new MainManager();
    
    if ($manager->confirmar_registro($correo, $codigo)) {
        // Recuperamos los datos del usuario recién creado para loguearlo
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, nombre, id_tipo_cuenta FROM USUARIO WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['temp_email']);
            header("Location: /home");
            exit();
        }
    }
    
    header("Location: /register-confirm?error=codigo");
    exit();
}