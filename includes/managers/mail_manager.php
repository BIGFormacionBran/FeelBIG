<?php
// feelbig\includes\managers\mail_manager.php
require_once __DIR__ . '/../utils/mail_util.php';
require_once __DIR__ . '/../utils/logger_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        Logger::info("MailManager: Preparando contenido HTML para $correo");
        
        $asunto = "Confirma tu cuenta en Feel BiG";
        
        // Estructura del mensaje
        $mensaje = "
            <h2 style='color: #1a1a1a;'>¡Hola, " . htmlspecialchars($nombre) . "!</h2>
            <p>Gracias por unirte a <strong>Feel BiG</strong>. Para completar tu registro, utiliza el siguiente código de verificación:</p>
            <div style='background: #f4f4f4; border: 1px solid #ddd; padding: 20px; text-align: center; margin: 20px 0;'>
                <span style='font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #333;'>$codigo</span>
            </div>
            <p>Este código expirará en 1 hora. Si no solicitaste este registro, puedes ignorar este mensaje.</p>
        ";
        
        $resultado = MailUtil::enviar($correo, $asunto, $mensaje);

        if ($resultado) {
            Logger::info("MailManager: MailUtil reportó éxito en el envío a $correo");
        } else {
            Logger::error("MailManager: MailUtil reportó error al intentar enviar a $correo");
        }

        return $resultado;
    }
}