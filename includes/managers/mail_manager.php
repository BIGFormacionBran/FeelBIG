<?php
require_once __DIR__ . '/../utils/mail_util.php';

class MailManager {
    public function enviarConfirmacionRegistro($correo, $nombre, $codigo) {
        $asunto = "Código de verificación: $codigo - Feel BiG";

        $iconoAlertaBase64 = "PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHZpZXdCb3g9JzAgMCAyNCAyNCc+PHBhdGggZmlsbD0nIzg1NjQwNCcgZD0nTTEyIDJMMSAyMWgyMkwxMiAyem0wIDMuOTlMMTkuNTMgMTlINC40N0wxMiA1Ljk5ek0xMSAxNmgydjJoLTJ6bTAtNmgydjRoLTInLz48L3N2Zz4=";
        
        $mensaje = "
            <div style='font-size: 16px; line-height: 1.6;'>
                Gracias por unirte a nuestra plataforma. Para completar tu registro, introduce el siguiente código de seguridad en la ventana de verificación:
            </div>
            <div style='margin: 35px 0; text-align: center;'>
                <div style='background-color: #f0f7ff; border: 2px solid #159BD7; padding: 20px; border-radius: 10px; display: inline-block;'>
                    <div style='font-size: 36px; font-weight: 800; letter-spacing: 6px; color: #159BD7; font-family: monospace;'>$codigo</div>
                </div>
            </div>
            <table role='presentation' width='100%' cellspacing='0' cellpadding='0' border='0' style='background-color: #fff9e6; border-radius: 6px;'>
                <tr>
                    <td style='padding: 10px; text-align: center; vertical-align: middle;'>
                        <img src='data:image/svg+xml;base64,$iconoAlertaBase64' width='20' height='20' style='vertical-align: middle; margin-right: 5px;'>
                        <span style='font-size: 14px; color: #856404; vertical-align: middle;'>Este código es válido únicamente durante <b>1 hora</b>.</span>
                    </td>
                </tr>
            </table>
        ";
        
        return MailUtil::enviar($correo, $asunto, $mensaje, $nombre);
    }
}