<?php
// feelbig\process_confirmacion.php
require_once 'includes/managers/main_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_SESSION['temp_email'] ?? '';
    
    // --- NUEVA LÓGICA: VERIFICACIÓN SEGURA POR GOOGLE ---
    if (isset($_POST['google_token'])) {
        $token = $_POST['google_token'];
        // Llamada a la API de Google para validar el token
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
        $response = @file_get_contents($url);
        $payload = json_decode($response, true);

        if ($payload && isset($payload['email']) && $payload['email'] === $correo) {
            $manager = new MainManager();
            $db = Database::getConnection();
            
            // Buscamos el código que el usuario tiene asignado en la base de datos
            $stmt = $db->prepare("SELECT codigo FROM REGISTRO_PENDIENTE WHERE correo = ? LIMIT 1");
            $stmt->execute([$correo]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res && $manager->confirmar_registro($correo, $res['codigo'])) {
                // Si la confirmación es exitosa, logueamos
                $stmt = $db->prepare("SELECT id, nombre, id_tipo_cuenta FROM USUARIO WHERE correo = ?");
                $stmt->execute([$correo]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
                    unset($_SESSION['temp_email'], $_SESSION['temp_name']);
                    
                    echo json_encode(['success' => true]);
                    exit();
                }
            }
        }
        echo json_encode(['success' => false]);
        exit();
    }

    // --- FLUJO MANUAL (POR CÓDIGO) ---
    $codigo = $_POST['codigo'] ?? '';

    if (empty($correo) || empty($codigo)) {
        header("Location: /register?error=codigo");
        exit();
    }

    $manager = new MainManager();
    if ($manager->confirmar_registro($correo, $codigo)) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, nombre, id_tipo_cuenta FROM USUARIO WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'] ?? 3;
            unset($_SESSION['temp_email'], $_SESSION['temp_name']);
            header("Location: /home");
            exit();
        }
    }
    
    header("Location: /register-confirm?error=codigo");
    exit();
}