<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Confirma tu cuenta en Feel BiG";
        $cuerpo = "
            <h2 style='color: #e91e63;'>¡Hola, $nombre!</h2>
            <p>Gracias por unirte a nuestra comunidad. Para completar tu registro, utiliza el siguiente código de confirmación:</p>
            <div style='background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 8px; margin: 20px 0;'>
                $codigo
            </div>
            <p>Copia y pega este código en la página de registro para activar tu cuenta.</p>
        ";
        return MailUtil::enviar($correo, $asunto, $cuerpo);
    }
}