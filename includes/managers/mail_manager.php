<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Código de verificación: $codigo - Feel BiG";
        
        // Diseño optimizado con los colores de la marca
        $mensaje = "
            <div style='background-color: #F0F2F5; padding: 40px 20px; font-family: sans-serif;'>
                <div style='max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>
                    <div style='background-color: #159BD7; padding: 30px; text-align: center;'>
                        <img src='https://bigformacion.es/feelbig/assets/img/logo.png' alt='Feel BiG' style='width: 140px; filter: brightness(0) invert(1);'>
                    </div>
                    <div style='padding: 40px 30px; text-align: center;'>
                        <h2 style='color: #1C1E21; margin-bottom: 10px;'>¡Hola, " . htmlspecialchars($nombre) . "!</h2>
                        <p style='color: #606770; font-size: 16px; line-height: 1.5;'>Gracias por unirte a nuestra plataforma. Para verificar tu identidad, utiliza el siguiente código:</p>
                        
                        <div style='margin: 30px 0; background: #F0F2F5; border: 2px dashed #159BD7; padding: 20px; border-radius: 8px;'>
                            <span style='font-size: 36px; font-weight: bold; letter-spacing: 10px; color: #027BC4;'>$codigo</span>
                        </div>
                        
                        <p style='color: #606770; font-size: 14px;'>Este código caducará en breve. Si no has sido tú, puedes ignorar este mensaje con total seguridad.</p>
                    </div>
                    <div style='background: #F0F2F5; padding: 20px; text-align: center; color: #90949c; font-size: 12px;'>
                        &copy; " . date('Y') . " Feel BiG - Formación de Alto Impacto
                    </div>
                </div>
            </div>
        ";
        
        return MailUtil::enviar($correo, $asunto, $mensaje);
    }
}