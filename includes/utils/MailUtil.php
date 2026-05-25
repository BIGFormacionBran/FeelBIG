<?php
require_once __DIR__ . '/../../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/ConfigUtil.php';
require_once __DIR__ . '/LoggerUtil.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailUtil {
    public static function send($recipient, $subject, $variableBody, $userName = "Usuario") {
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
            $mail->addAddress($recipient);
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'];
            $logoUrl = $protocol . $host . "/assets/img/logo.png";

            $htmlHeader = "
            <div style='background-color: #d3d3d3; padding: 40px 10px; font-family: sans-serif; border-radius: 12px;'>
                <div style='max-width: 600px; margin: 0 auto; border-radius: 12px; border: 1px solid #eeeeee; overflow: hidden;'>
                    <div style='background-color: #fff; padding: 30px; text-align: center;'>
                        <img src='$logoUrl' alt='Feel BiG' style='width: 180px; height: auto; display: block; margin: 0 auto; -ms-interpolation-mode: bicubic;'>
                    </div>
                    <div style='padding: 40px 30px; color: #fff; background-color: #010101; text-align: center;'>
                        <div style='font-size: 20px; font-weight: bold; margin-bottom: 20px; text-align: center;'>¡Hola, " . htmlspecialchars($userName) . "!</div>
            ";

            $htmlFooter = "
                        <div style='margin-top: 40px; padding-top: 25px; border-top: 2px solid #159BD7; text-align: center;'>
                            <div style='font-weight: bold; color: #fff; font-size: 16px;'>Equipo de Feel BiG</div>
                            <div style='color: #159BD7; font-size: 13px; margin-top: 5px; text-transform: uppercase;'>Formación de Alto Impacto</div>
                            <div style='margin-top: 25px; color: #999999; font-size: 11px;'>
                                &copy; " . date('Y') . " Academia trinidad S.L. Todos los derechos reservados.
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

            $mail->Body = $htmlHeader . $variableBody . $htmlFooter;
            return $mail->send();
        } catch (Exception $e) {
            LoggerUtil::error("MailUtil: " . $e->getMessage());
            return false;
        }
    }
}