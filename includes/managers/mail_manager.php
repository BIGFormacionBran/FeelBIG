<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Código de verificación: $codigo - Feel BiG";
        
        $mensaje = "
            <div style='font-size: 16px; line-height: 1.6; color: #444444;'>
                Gracias por unirte a nuestra plataforma. Para completar tu registro, introduce el siguiente código de seguridad en la ventana de verificación:
            </div>
            <div style='margin: 35px 0; text-align: center;'>
                <div style='background-color: #f0f7ff; border: 2px solid #159BD7; padding: 20px; border-radius: 10px; display: inline-block;'>
                    <div style='font-size: 36px; font-weight: 800; letter-spacing: 6px; color: #159BD7; font-family: monospace;'>$codigo</div>
                </div>
            </div>
            <div style='font-size: 14px; color: #999999; text-align: center; background: #fff9e6; padding: 10px; border-radius: 6px;'>
                ⚠️ Este código es válido únicamente durante <strong>1 hora</strong>.
            </div>
        ";
        
        return MailUtil::enviar($correo, $asunto, $mensaje, $nombre);
    }
}