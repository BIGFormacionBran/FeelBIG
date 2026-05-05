<?php
// feelbig\includes\utils\mail_util.php

// Cargamos los archivos de la librería directamente desde la ruta que definiste
require_once __DIR__ . '/../../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/logger_util.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailUtil {
    public static function enviar($destinatario, $asunto, $contenidoHtml) {
        $mail = new PHPMailer(true);

        try {
            Logger::info("MailUtil: Iniciando PHPMailer (SMTP) para [$destinatario]");

            // --- CONFIGURACIÓN SERVIDOR (GMAIL EJEMPLO) ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tu-correo@gmail.com'; // <--- CAMBIA ESTO
            $mail->Password   = 'tu-clave-de-aplicacion'; // <--- CAMBIA ESTO (16 letras)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // --- DESTINATARIOS ---
            $mail->setFrom('no-reply@feelbig.com', 'Feel BiG');
            $mail->addAddress($destinatario);

            // --- CONTENIDO ---
            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $diseno_inicio = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee;'>
                <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                    <img src='https://tusitio.com/assets/img/logo.png' alt='Feel BiG' style='width: 150px;'>
                </div>
                <div style='padding: 30px; line-height: 1.6; color: #333;'>";

            $diseno_fin = "
                </div>
                <div style='background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888;'>
                    <p>© " . date('Y') . " Feel BiG. Todos los derechos reservados.</p>
                </div>
            </div>";

            $mail->Body = "<html><body>" . $diseno_inicio . $contenidoHtml . $diseno_fin . "</body></html>";

            $mail->send();
            Logger::info("MailUtil: ¡Correo enviado con éxito a [$destinatario]!");
            return true;

        } catch (Exception $e) {
            Logger::error("MailUtil: PHPMailer falló. Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}