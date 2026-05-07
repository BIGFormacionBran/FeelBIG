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

            $logoBase64 = "PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHZpZXdCb3g9JzE3NSA0MTUgNzYwIDI3NSc+PHBhdGggZmlsbD0nIzAyMDIwMicgZD0nTTY5Mi44OSA1NTAuNXYtNTMuMzZjMC0uNjkgMi4wNC0zLjk0IDIuOTYtNC41IDQuNTgtMi44MyA1Ni42OS0yLjcxIDU5LjkuNTQgMi44MiAyLjg1LjI4IDkuMjggMy45MSAxMC4wOCAxNC4yMS00Ny45NSA2NS4yNC03NS44NiAxMTQuMjUtNjguMjJ2NjguMjNjLTI2Ljc1LTMxLjU2LTY1Ljc4IDIuOTMtNDIuOTEgMzQuODQgMi44NCAzLjk3IDM0LjY4IDM0LjIxIDM3LjE1IDM0Ljg5IDEwLjc4IDIuOTYgMjMuODItMTguMzkgMjYuOTItMjYuODAgMS45Mi01LjIxIDMuMzItMjAuMDMgOC4xMy0yMS42NSAyLjg4LS45NyA0Mi45MS0xLjA3IDQ3LjE3LS4yOXM1LjMyIDQuMDcgNi4wMyA3Ljk5bDUuMzggMTM1LjQ3Yy0yLjk0IDMuMDQtMjctMTcuNTktMzQuMjYtMTcuNTktOS40MSAwLTIzLjgzIDE2LjQ1LTM0LjI3IDE3LjZ2LTQwLjI0Yy00Ni4zMyAxNS41Ny05NS42MyAxLjIxLTEyMy4wNi0zOS4zMmwtMTIuMjYtMjEuOTF2NjAuMzZjMCAuODQtMi42MiA0LjEyLTMuNTIgNS4yNS0yLjQzIDEuNDctNTIuNDMgMS43OS01Ni43Ni41Mi04LjU0LTIuNTAtMi42Ny0xNy43OC01LjYyLTI0LjEzLTYuOTYgMTEuMjUtMjEuOTcgMjIuNTEtMzUuMzEgMjQuMzMtMTguMzkgMi41MS03OS44MiAyLjU2LTk4LjM0LjIzLTIuNDYtLjMxLTQuNTYtLjQxLTYuMjQtMi41NGwuNjEtMTk0LjgzYzQuMDUtMS43NCA4LjMxLTEuOTggMTIuNjctMi4yNSAxOS4yNy0xLjIgNzEuMjUtLjcgODggNS4yNyAyNy45MiA5Ljk0IDM4LjU4IDQ3LjYxIDI1LjQ2IDcyLjcxLTMuNzQgNy4xNi0xMC4xIDEyLjI4LTE0LjE0IDE5LjE1bDI4LjEyIDIwLjE4WicvPjxwYXRoIGZpbGw9JyMxNzlidDYnIGQ9J00yMjUuNDMgNDE3LjUzdjU3Ljc0Yy05LjA4LTMuODUtMTIuODEtOC4yNC0yMy43NS03LjA1LTE2LjcwIDEuODItMjEuNjIgMjMuODMtMTYuMjMgMzcuMjMgMy40NyA4LjYxIDI0LjEyIDEwLjE1IDMxLjcwIDYuMjAgNC4zMS0yLjI1IDYuMDEtNy43MSA5Ljk5LTExLjA1IDIwLjU1LTE3LjE5IDU0LjE1LTMwLjk2IDc4LjI0LTEzLjA4IDIwLjM1IDE1LjEwIDIwLjA4IDQ3LjQwLS45OSA2Mi0yLjk0IDIuMDQtMTUuNTYgNy45OC0xOC4zNCA3Ljk4aC0zMi41MWMtLjc2IDI5LjkwIDMxLjg1IDMzLjkwIDUzLjkyIDIzLjA2IDMuOTQtMS45NCAxNS42Mi0xMC44MiAxNy4yMy0xNC4zNCAyLjMxLTUuMDYuMzAtMTMuNzggMS4xMy0xOS44NyA0Ljc5LTM1LjQwIDM1Ljg4LTY2LjE5IDcyLjY0LTY3LjY1IDM0LjYwLTEuMzcgNTYuODcgMzIuNjAgMzYuODcgNjEuMjAtMy41MCA1LjAxLTE2Ljk3IDE1Ljg2LTIyLjc0IDE1Ljg2aC0zNS4xNWMtNi4wMiAwLS4xOCAxMy4xNCAxLjUxIDE1Ljk5IDExLjI4IDE4Ljk0IDQwLjAxIDE3LjExIDU0LjcyIDMuMjUgMjcuMTItMjUuNTUgMTMuNTMtNzQuMDEgMTYuNjItMTA3LjY4LjI1LTIuNzYuMjUtNC42NSAxLjQxLTcuMzUgMS44Ni00LjMyIDI1LjA4LTE3LjExIDMwLjg5LTE5Ljk4IDQuOTQtMi40NCA5LjM5LTUuNzUgMTUuMjMtNC45NXYxMzUuNjBjMCAxLjM3IDQuNiA2LjY5IDYuMzQgNy42OCAxNS4xMSA4LjU5IDIxLjkzLTcuODEgMzQuMDgtMTAuMzB2NTUuMTFjLTE0LjkzIDExLjI4LTMyLjM5IDE0LjYzLTQ5Ljc3IDUuODEtOS4yOC00LjcxLTE0LjAxLTEzLjkwLTIyLjg2LTE4LjY4LTcuMDMgMy40OS0xMS4yNSA5LjM4LTE4LjIyIDEzLjQxLTMxLjg3IDE4LjQwLTc3Ljk4IDEzLjYwLTEwMS44MC0xNS4yOS0zMS4zMyAyNC45NC02Ni40NSAzNy4zOC0xMDMuNzggMTUuOTMtMjYuODMtMTUuNDItMzguNzgtMzcuNzUtMzkuMjYtNjguNTMtNi45MS0zLjAzLTE5LjMyIDEwLjA3LTE5LjMyIDE0Ljg3djU5LjQ5bC0yLjY0IDIuNjJoLTM4LjY2bC0yLjY0LTIuNjJ2LTY2LjQ5YzAtNy4zOS0yMi44My0yLjA3LTI1LjU5LTYuMDFsLjg4LTQzLjA5YzM2LjA3IDMuNDMgMjEuODgtMjkuNTQgMjQuODctNTIuMjMgNC40OS0zNC4wNyA1NS41MC01My44MiA4NS45Ni00NC43NlonLz48cGF0aCBmaWxsPScjMjA5ZmQ4JyBkPSdNNjk3LjI5IDQ3NS4yNmMtNy4wMS0xLjk2LTUuMjQtMzMuMTUtMi4yMC0zOS44MiAzLjQ3LTIuOTggNDIuNjUtMi44OSA0OS43My0yLjI2IDUuNDMuNDggMTEuODIgMS41NSAxMy4wNiA3Ljk5IDEuNTEgNy44NyAxLjUwIDI3Ljk0LTQuMzYgMzQuMDhoLTU2LjI0WicvPjwvc3ZnPg==";

            $htmlHeader = "
            <div style='background-color: #f8f9fa; padding: 40px 10px; font-family: sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #eeeeee; overflow: hidden;'>
                    <div style='background-color: #1a1a1a; padding: 30px; text-align: center;'>
                        <img src='data:image/svg+xml;base64,$logoBase64' alt='Feel BiG' style='width: 180px; height: auto; display: block; margin: 0 auto;'>
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