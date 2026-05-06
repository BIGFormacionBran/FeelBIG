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

            // --- LOGO ---
            $rutaLogo = dirname(__DIR__, 2) . '/assets/img/logo.png';
            if (file_exists($rutaLogo)) {
                $mail->addEmbeddedImage($rutaLogo, 'logo_feelbig');
                $imgSrc = 'cid:logo_feelbig';
            } else {
                $imgSrc = 'https://bigformacion.es/feelbig/assets/img/logo.png';
            }

            $htmlHeader = "
            <div style='background-color: #f8f9fa; padding: 40px 10px; font-family: sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #eeeeee; overflow: hidden;'>
                    <div style='background-color: #1a1a1a; padding: 30px; text-align: center;'>
                        <img src='$imgSrc' alt='Feel BiG' style='width: 150px; height: auto;'>
                    </div>
                    <div style='padding: 40px 30px; color: #333333;'>
                        <div style='font-size: 20px; font-weight: bold; margin-bottom: 20px; text-align: center;'>¡Hola, " . htmlspecialchars($nombreUsuario) . "!</div>
            ";

            $htmlFooter = "
                        <div style='margin-top: 40px; padding-top: 25px; border-top: 1px solid #f0f0f0; text-align: center;'>
                            <div style='font-weight: bold; color: #1a1a1a; font-size: 16px;'>Equipo de Feel BiG</div>
                            <div style='color: #159BD7; font-size: 13px; margin-top: 5px; text-transform: uppercase;'>Formación de Alto Impacto</div>
                            <div style='margin-top: 25px; color: #999999; font-size: 11px;'>
                                &copy; " . date('Y') . " Academia trinidad S.L. Todos los derechos reservados.
                            </div>
                        </div>
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