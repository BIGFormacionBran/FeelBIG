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

            $logoSvg = "
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='175 415 760 275'>
                    <path fill='#020202' d='M692.89 550.5v-53.36c0-.69 2.04-3.94 2.96-4.5 4.58-2.83 56.69-2.71 59.9.54 2.82 2.85.28 9.28 3.91 10.08 14.21-47.95 65.24-75.86 114.25-68.22v68.23c-26.75-31.56-65.78 2.93-42.91 34.84 2.84 3.97 34.68 34.21 37.15 34.89 10.78 2.96 23.82-18.39 26.92-26.8 1.92-5.21 3.32-20.03 8.13-21.65 2.88-.97 42.91-1.07 47.17-.29s5.32 4.07 6.03 7.99l5.38 135.47c-2.94 3.04-27-17.59-34.26-17.59-9.41 0-23.83 16.45-34.27 17.6v-40.24c-46.33 15.57-95.63 1.21-123.06-39.32l-12.26-21.91v60.36c0 .84-2.62 4.12-3.52 5.25-2.43 1.47-52.43 1.79-56.76.52-8.54-2.5-2.67-17.78-5.62-24.13-6.96 11.25-21.97 22.51-35.31 24.33-18.39 2.51-79.82 2.56-98.34.23-2.46-.31-4.56-.41-6.24-2.54l.61-194.83c4.05-1.74 8.31-1.98 12.67-2.25 19.27-1.2 71.25-.7 88 5.27 27.92 9.94 38.58 47.61 25.46 72.71-3.74 7.16-10.1 12.28-14.1 19.15l28.12 20.18Z'/>
                    <path fill='#179bd6' d='M225.43 417.53v57.74c-9.08-3.85-12.81-8.24-23.75-7.05-16.7 1.82-21.62 23.83-16.23 37.23 3.47 8.61 24.12 10.15 31.7 6.2 4.31-2.25 6.01-7.71 9.99-11.05 20.55-17.19 54.15-30.96 78.24-13.08 20.35 15.1 20.08 47.4-.99 62-2.94 2.04-15.56 7.98-18.34 7.98h-32.51c-.76 29.9 31.85 33.9 53.92 23.06 3.94-1.94 15.62-10.82 17.23-14.34 2.31-5.06.3-13.78 1.13-19.87 4.79-35.4 35.88-66.19 72.64-67.65 34.6-1.37 56.87 32.6 36.87 61.2-3.5 5.01-16.97 15.86-22.74 15.86h-35.15c-6.02 0-.18 13.14 1.51 15.99 11.28 18.94 40.01 17.11 54.72 3.25 27.12-25.55 13.53-74.01 16.62-107.68.25-2.76.25-4.65 1.41-7.35 1.86-4.32 25.08-17.11 30.89-19.98 4.94-2.44 9.39-5.75 15.23-4.95v135.6c0 1.37 4.6 6.69 6.34 7.68 15.11 8.59 21.93-7.81 34.08-10.3v55.11c-14.93 11.28-32.39 14.63-49.77 5.81-9.28-4.71-14.01-13.9-22.86-18.68-7.03 3.49-11.25 9.38-18.22 13.41-31.87 18.4-77.98 13.6-101.8-15.29-31.33 24.94-66.45 37.38-103.78 15.93-26.83-15.42-38.78-37.75-39.26-68.53-6.91-3.03-19.32 10.07-19.32 14.87v59.49l-2.64 2.62h-38.66l-2.64-2.62v-66.49c0-7.39-22.83-2.07-25.59-6.01l.88-43.09c36.07 3.43 21.88-29.54 24.87-52.23 4.49-34.07 55.5-53.82 85.96-44.76Z'/>
                    <path fill='#209fd8' d='M697.29 475.26c-7.01-1.96-5.24-33.15-2.2-39.82 3.47-2.98 42.65-2.89 49.73-2.26 5.43.48 11.82 1.55 13.06 7.99 1.51 7.87 1.5 27.94-4.36 34.08h-56.24Z'/>
                </svg>
            ";

            $htmlHeader = "
            <div style='background-color: #f8f9fa; padding: 40px 10px; font-family: sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #eeeeee; overflow: hidden;'>
                    <div style='background-color: #1a1a1a; padding: 30px; text-align: center;'>
                        $logoSvg
                    </div>
                    <div style='padding: 40px 30px; color: #fff; background-color: #010101; text-align: center;'>
                        <div style='font-size: 20px; font-weight: bold; margin-bottom: 20px; text-align: center;'>¡Hola, " . htmlspecialchars($nombreUsuario) . "!</div>
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

            $mail->Body = $htmlHeader . $cuerpoVariable . $htmlFooter;
            return $mail->send();
        } catch (Exception $e) {
            Logger::error("MailUtil: " . $e->getMessage());
            return false;
        }
    }
}