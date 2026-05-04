<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Confirma tu cuenta en Feel BiG";
        $mensaje = "<h2>¡Hola, $nombre!</h2>
                    <p>Gracias por registrarte. Tu código de verificación es:</p>
                    <h1 style='background: #f4f4f4; padding: 10px; text-align: center; letter-spacing: 5px;'>$codigo</h1>
                    <p>Introduce este código en la web para activar tu cuenta.</p>";
        
        return MailUtil::enviar($correo, $asunto, $mensaje);
    }
}