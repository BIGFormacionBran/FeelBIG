<?php
// feelbig\includes\utils\mail_util.php

require_once __DIR__ . '/../../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/config_util.php';
require_once __DIR__ . '/logger_util.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailUtil {
    public static function enviar($destinatario, $asunto, $cuerpoVariable, $nombreUsuario = "Usuario") {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = ConfigUtil::get('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = ConfigUtil::get('SMTP_USER');
            $mail->Password   = ConfigUtil::get('SMTP_PASS'); 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = ConfigUtil::get('SMTP_PORT', 587);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(ConfigUtil::get('SMTP_USER'), ConfigUtil::get('SMTP_FROM_NAME', 'Feel BiG'));
            $mail->addAddress($destinatario);
            $mail->isHTML(true);
            $mail->Subject = $asunto;

            // --- CONFIGURACIÓN DEL LOGO (URL Pública correcta) ---
            $urlLogo = "https://bigformacion.es/feelbig/assets/img/logo.png";

            // --- PLANTILLA MAESTRA (Cabecera, Saludo, Footer y Firma fijos) ---
            $htmlHeader = "
            <div style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #eeeeee;'>
                    <div style='background-color: #1a1a1a; padding: 20px; text-align: center;'>
                        <img src='$urlLogo' alt='Feel BiG' style='width: 150px; display: block; margin: 0 auto;'>
                    </div>
                    <div style='padding: 30px; color: #333333;'>
                        <div style='font-size: 18px; font-weight: bold; margin-bottom: 20px;'>¡Hola, " . htmlspecialchars($nombreUsuario) . "!</div>
            ";

            $htmlFooter = "
                        <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eeeeee; color: #777777; font-size: 14px;'>
                            Atentamente,<br>
                            <strong>Equipo de Feel BiG</strong><br>
                            <em>Formación de Alto Impacto</em>
                        </div>
                    </div>
                    <div style='background-color: #f9f9f9; padding: 15px; text-align: center; color: #999999; font-size: 11px;'>
                        &copy; " . date('Y') . " Feel BiG. Todos los derechos reservados.
                    </div>
                </div>
            </div>";

            $mail->Body = $htmlHeader . $cuerpoVariable . $htmlFooter;

            return $mail->send();
        } catch (Exception $e) {
            Logger::error("MailUtil: " . $e->getMessage());
            return false;
        }
    }
}