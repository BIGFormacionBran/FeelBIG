<?php
require_once __DIR__ . '/../utils/mail_util.php';
require_once __DIR__ . '/../utils/logger_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        Logger::info("MailManager: Intentando enviar correo de confirmación a $correo");
        
        $asunto = "Confirma tu cuenta en Feel BiG";
        $mensaje = "<h2>¡Hola, $nombre!</h2>
                    <p>Gracias por registrarte. Tu código de verificación es:</p>
                    <h1 style='background: #f4f4f4; padding: 10px; text-align: center; letter-spacing: 5px;'>$codigo</h1>
                    <p>Introduce este código en la web para activar tu cuenta.</p>";
        
        $resultado = MailUtil::enviar($correo, $asunto, $mensaje);

        if ($resultado) {
            Logger::info("MailManager: Correo enviado correctamente a $correo");
        } else {
            Logger::error("MailManager: Fallo el envío de correo a $correo");
        }

        return $resultado;
    }
}