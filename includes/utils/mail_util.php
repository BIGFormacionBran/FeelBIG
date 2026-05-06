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
    public static function enviar($destinatario, $asunto, $contenidoHtml) {
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURACIÓN DE LOGS DETALLADOS ---
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
            // Redirigir el debug de SMTP a nuestro Logger
            $mail->Debugoutput = function($str, $level) {
                Logger::info("PHPMailer DEBUG [$level]: " . trim($str));
            };

            $user = ConfigUtil::get('SMTP_USER');
            $pass = ConfigUtil::get('SMTP_PASS');
            $host = ConfigUtil::get('SMTP_HOST');
            $port = ConfigUtil::get('SMTP_PORT', 587);

            Logger::info("MailUtil: Intentando conexión SMTP -> Host: $host, Port: $port, User: $user");
            
            // Verificación de seguridad rápida en el log (sin mostrar toda la pass)
            if (empty($pass)) {
                Logger::error("MailUtil: La contraseña SMTP está VACÍA. Revisa el .env");
            } else {
                Logger::info("MailUtil: Password cargada (Longitud: " . strlen($pass) . " caracteres)");
            }

            // --- CONFIGURACIÓN ---
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $port;
            $mail->CharSet    = 'UTF-8';

            // --- REMITENTE Y DESTINATARIO ---
            $mail->setFrom($user, ConfigUtil::get('SMTP_FROM_NAME', 'Feel BiG'));
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $header = "<div style='font-family: Arial; max-width: 600px; margin: auto; border: 1px solid #eee;'>
                        <div style='background: #1a1a1a; padding: 20px; text-align: center;'>
                            <img src='https://tusitio.com/assets/img/logo.png' alt='Feel BiG' style='width: 150px;'>
                        </div>
                        <div style='padding: 30px; color: #333;'>";
            $footer = "</div></div>";

            $mail->Body = "<html><body>" . $header . $contenidoHtml . $footer . "</body></html>";

            $mail->send();
            Logger::info("MailUtil: Envío exitoso a [$destinatario]");
            return true;

        } catch (Exception $e) {
            Logger::error("MailUtil: EXCEPCIÓN PHPMailer: " . $e->getMessage());
            Logger::error("MailUtil: Detalle del ErrorInfo: " . $mail->ErrorInfo);
            return false;
        } catch (\Throwable $t) {
            Logger::error("MailUtil: ERROR CRÍTICO DE PHP: " . $t->getMessage());
            return false;
        }
    }
}