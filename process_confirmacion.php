<?php
session_start();
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $correo = $_SESSION['temp_email'] ?? '';

    if (empty($correo) || empty($codigo)) {
        header("Location: /registro");
        exit();
    }

    $manager = new MainManager();
    
    if ($manager->confirmar_registro($correo, $codigo)) {
        // Al confirmar, hacemos login automático (opcional, igual que tenías antes)
        // Como registrar() en UsuarioDAO ahora se llamó desde confirmar_registro,
        // simplemente recuperamos el usuario para la sesión.
        
        // Usamos una contraseña dummy o buscamos por correo ya que el password está hasheado
        // Mejor buscamos los datos del usuario directamente:
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM USUARIO WHERE correo = ?");
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
    
    header("Location: /confirmacion?error=codigo");
    exit();
}