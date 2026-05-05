<?php
class MailUtil {
    public static function enviar($destinatario, $asunto, $contenidoHtml) {
        $header = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee;'>
            <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                <img src='https://tusitio.com/assets/img/logo.png' alt='Feel BiG' style='width: 150px;'>
            </div>
            <div style='padding: 30px; line-height: 1.6; color: #333;'>";

        $footer = "
            </div>
            <div style='background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888;'>
                <p>© " . date('Y') . " Feel BiG. Todos los derechos reservados.</p>
            </div>
        </div>";

        $fullHtml = "<html><body>" . $header . $contenidoHtml . $footer . "</body></html>";

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Feel BiG <no-reply@feelbig.com>',
            'X-Mailer: PHP/' . phpversion()
        ];

        try {
            return mail($destinatario, $asunto, $fullHtml, implode("\r\n", $headers));
        } catch (Exception $e) {
            return false;
        }
    }
}