<?php
// feelbig\includes\utils\mail_util.php

require_once __DIR__ . '/../../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/config_util.php';
require_once __DIR__ . '/logger_util.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailUtil {
    public static function enviar($destinatario, $asunto, $contenidoHtml) {
        $mail = new PHPMailer(true);

        try {
            Logger::info("MailUtil: Iniciando PHPMailer para [$destinatario]");

            // --- CONFIGURACIÓN DESDE CONFIGUTIL ---
            $mail->isSMTP();
            $mail->Host       = ConfigUtil::get('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = ConfigUtil::get('SMTP_USER');
            $mail->Password   = ConfigUtil::get('SMTP_PASS'); 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = ConfigUtil::get('SMTP_PORT', 587);
            $mail->CharSet    = 'UTF-8';

            // --- REMITENTE Y DESTINATARIO ---
            $mail->setFrom(ConfigUtil::get('SMTP_USER'), ConfigUtil::get('SMTP_FROM_NAME', 'Feel BiG'));
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;

            // Diseño estándar
            $header = "<div style='font-family: Arial; max-width: 600px; margin: auto; border: 1px solid #eee;'>
                        <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                            <img src='https://tusitio.com/assets/img/logo.png' alt='Feel BiG' style='width: 150px;'>
                        </div>
                        <div style='padding: 30px; color: #333;'>";
            $footer = "</div></div>";

            $mail->Body = "<html><body>" . $header . $contenidoHtml . $footer . "</body></html>";

            $mail->send();
            Logger::info("MailUtil: Correo enviado exitosamente.");
            return true;

        } catch (Exception $e) {
            Logger::error("MailUtil: PHPMailer falló. Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}