<?php
require_once 'includes/managers/MainManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['temp_email'] ?? '';
    
    if (isset($_POST['googleToken'])) {
        $token = $_POST['googleToken'];
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
        $response = @file_get_contents($url);
        $payload = json_decode($response, true);

        if ($payload && isset($payload['email']) && $payload['email'] === $email) {
            $manager = new MainManager();
            $connection = DbUtil::getConnection();
            
            $stmt = $connection->prepare("SELECT codigo FROM REGISTRO_PENDIENTE WHERE correo = ? LIMIT 1");
            $stmt->execute([$email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $manager->confirmRegistration($email, $result['codigo'])) {
                $stmt = $connection->prepare("SELECT id, nombre, id_tipo_cuenta FROM USUARIO WHERE correo = ?");
                $stmt->execute([$email]);
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

    $code = $_POST['codigo'] ?? ''; 

    if (empty($email) || empty($code)) {
        header("Location: /register?error=codigo");
        exit();
    }

    $manager = new MainManager();
    if ($manager->confirmRegistration($email, $code)) {
        $connection = DbUtil::getConnection();
        $stmt = $connection->prepare("SELECT id, nombre, id_tipo_cuenta FROM USUARIO WHERE correo = ?");
        $stmt->execute([$email]);
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