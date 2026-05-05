<?php
// feelbig\includes\utils\mail_util.php
require_once __DIR__ . '/logger_util.php';

class MailUtil {
    public static function enviar($destinatario, $asunto, $contenidoHtml) {
        Logger::info("MailUtil: Iniciando envío para [$destinatario]");

        $headerStr = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee;'>
            <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                <img src='https://tusitio.com/assets/img/logo.png' alt='Feel BiG' style='width: 150px;'>
            </div>
            <div style='padding: 30px; line-height: 1.6; color: #333;'>";

        $footerStr = "
            </div>
            <div style='background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888;'>
                <p>© " . date('Y') . " Feel BiG. Todos los derechos reservados.</p>
            </div>
        </div>";

        $fullHtml = "<html><body>" . $headerStr . $contenidoHtml . $footerStr . "</body></html>";

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Feel BiG <no-reply@feelbig.com>',
            'X-Mailer: PHP/' . phpversion()
        ];

        $headersFinal = implode("\r\n", $headers);
        
        // Ejecución de la función nativa
        $exito = @mail($destinatario, $asunto, $fullHtml, $headersFinal);

        if ($exito) {
            Logger::info("MailUtil: La función mail() aceptó el correo para [$destinatario].");
        } else {
            Logger::error("MailUtil: La función mail() falló. Verificar configuración SMTP en php.ini o permisos del servidor.");
        }

        return $exito;
    }
}