<?php
namespace App\Core;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send(string $to, string $subject, string $message): void
    {
        $mail = new PHPMailer(true);

        try {
            //server settings
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USERNAME');
            $mail->Password   = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('SMTP_PORT');

            //recipients
            $mail->setFrom(getenv('SMTP_USERNAME'), 'New Mexico');
            $mail->addAddress($to);
            $mail->addReplyTo('noreply@newmexico.com');

            //mail content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            throw new \Exception("Mail n'a pas été envoyé. Erreur Mailer: {$mail->ErrorInfo}");
        }
    }
}