<?php
// =========================================================
// CONFIGURACIÓN DE ENVÍO DE MAIL (Gmail SMTP + PHPMailer)
// =========================================================
//
// 1) Instalá PHPMailer con Composer (una sola vez, en la carpeta del proyecto):
//      composer require phpmailer/phpmailer
//
// 2) En tu cuenta de Gmail activá la verificación en 2 pasos y generá una
//    "contraseña de aplicación" (Google Account > Seguridad > Contraseñas de aplicaciones).
//    NO uses tu contraseña normal de Gmail acá.
//
// 3) Completá los datos abajo.
// =========================================================

define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_USER", "tu_correo@gmail.com");        // tu Gmail
define("MAIL_PASS", "xxxx xxxx xxxx xxxx");         // contraseña de aplicación (16 caracteres)
define("MAIL_FROM_NAME", "McPapas");
define("MAIL_PORT", 587);

require "vendor/autoload.php"; // Generado por Composer al instalar PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCodigoRecuperacion(string $destinatario, string $codigo): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = "UTF-8";

        $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = "Tu código para recuperar la contraseña - McPapas";
        $mail->Body    = "
            <div style='font-family:Arial,sans-serif;max-width:420px;margin:auto'>
                <h2 style='color:#d5003b'>McPapas</h2>
                <p>Usá este código para recuperar tu contraseña:</p>
                <p style='font-size:32px;font-weight:bold;letter-spacing:6px;color:#222'>$codigo</p>
                <p style='color:#888;font-size:13px'>Este código vence en 15 minutos. Si no fuiste vos, ignorá este mensaje.</p>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error enviando mail: " . $mail->ErrorInfo);
        return false;
    }
}