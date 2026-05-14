<?php
require_once __DIR__ . '/../utils/MailUtil.php';

class MailManager {
    public function sendRegistrationConfirmation($email, $name, $code) {
        $subject = "Código de verificación: $code - Feel BiG";

        $message = "
            <div style='font-size: 16px; line-height: 1.6;'>
                Gracias por unirte a nuestra plataforma. Para completar tu registro, introduce el siguiente código de seguridad en la ventana de verificación:
            </div>
            <div style='margin: 35px 0; text-align: center;'>
                <div style='background-color: #f0f7ff; border: 2px solid #159BD7; padding: 20px; border-radius: 10px; display: inline-block;'>
                    <div style='font-size: 36px; font-weight: 800; letter-spacing: 6px; color: #159BD7; font-family: monospace;'>$code</div>
                </div>
            </div>
            <table role='presentation' width='100%' cellspacing='0' cellpadding='0' border='0' style='background-color: #fff9e6; border-radius: 6px;'>
                <tr>
                    <td style='padding: 12px; text-align: center; color: #856404;'>
                        <span style='font-size: 18px; vertical-align: middle; margin-right: 5px;'>⚠️</span>
                        <span style='font-size: 14px; vertical-align: middle;'>Este código es válido únicamente durante <b>1 hora</b>.</span>
                    </td>
                </tr>
            </table>
        ";
        
        return MailUtil::send($email, $subject, $message, $name);
    }
}