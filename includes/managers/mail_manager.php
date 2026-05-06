<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Código de verificación: $codigo - Feel BiG";
        
        // Solo definimos el cuerpo específico de este correo
        $mensaje = "
            <div style='font-size: 16px; line-height: 1.6; color: #555555;'>
                Gracias por unirte a nuestra plataforma. Para completar tu registro y verificar tu identidad, por favor utiliza el siguiente código:
            </div>
            <div style='margin: 30px 0; text-align: center;'>
                <div style='display: inline-block; background-color: #f0f8ff; border: 2px dashed #159BD7; padding: 15px 30px; border-radius: 5px;'>
                    <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #159BD7;'>$codigo</span>
                </div>
            </div>
            <div style='font-size: 14px; color: #888888; text-align: center;'>
                Este código caducará pronto por motivos de seguridad.
            </div>
        ";
        
        // Pasamos el nombre para que el Saludo de MailUtil sea personalizado
        return MailUtil::enviar($correo, $asunto, $mensaje, $nombre);
    }
}