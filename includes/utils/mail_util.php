<?php
class MailUtil {
    private static $primaryColor = "#e91e63";

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
                <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            </div>
        </div>";

        $fullHtml = "<html><body>" . $header . $contenidoHtml . $footer . "</body></html>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Feel BiG <no-reply@feelbig.com>" . "\r\n";

        // --- SISTEMA DE LOGS ---
        $resultado = mail($destinatario, $asunto, $fullHtml, $headers);
        
        if (!$resultado) {
            $error_info = error_get_last();
            $log_msg = "[" . date('Y-m-d H:i:s') . "] FALLO ENVÍO MAIL a: $destinatario. Error PHP: " . ($error_info['message'] ?? 'Sin mensaje de error específico.') . PHP_EOL;
            file_put_contents(__DIR__ . '/../../error_log.txt', $log_msg, FILE_APPEND);
        } else {
            $log_msg = "[" . date('Y-m-d H:i:s') . "] ÉXITO ENVÍO MAIL a: $destinatario" . PHP_EOL;
            file_put_contents(__DIR__ . '/../../error_log.txt', $log_msg, FILE_APPEND);
        }

        return $resultado;
    }
}