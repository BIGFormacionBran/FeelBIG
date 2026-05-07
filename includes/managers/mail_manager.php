<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Código de verificación: $codigo - Feel BiG";
        
        $mensaje = "
            <div style='font-size: 16px; line-height: 1.6;'>
                Gracias por unirte a nuestra plataforma. Para completar tu registro, introduce el siguiente código de seguridad en la ventana de verificación:
            </div>
            <div style='margin: 35px 0; text-align: center;'>
                <div style='background-color: #f0f7ff; border: 2px solid #159BD7; padding: 20px; border-radius: 10px; display: inline-block;'>
                    <div style='font-size: 36px; font-weight: 800; letter-spacing: 6px; color: #159BD7; font-family: monospace;'>$codigo</div>
                </div>
            </div>
            <div style='display: flex; flex-flow: row; align-items: center; justify-content: center; font-size: 14px; color: #999999; background: #fff9e6; padding: 10px 0; border-radius: 6px;'>
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' style='width: 30px; height: auto; margin-right: 8px; fill: #856404;'>
                    <path d='M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z'/>
                </svg> Este código es válido únicamente durante <b>1 hora</b>.
            </div>
        ";
        
        return MailUtil::enviar($correo, $asunto, $mensaje, $nombre);
    }
}