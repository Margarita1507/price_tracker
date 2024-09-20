<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendEmail($to, $price): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';          // Укажите SMTP сервер
            $mail->SMTPAuth = true;                  // Включить SMTP аутентификацию
            $mail->Username = 'mslobodziana@gmail.com'; // SMTP логин
            $mail->Password = 'gayy nssm uzab ruwa';       // SMTP пароль
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Включить шифрование TLS
            $mail->Port = 587;

            $mail->setFrom('pricetracker@example.com', 'Olx Price Tracker');
            $mail->addAddress($to);
            $mail->Subject = 'Price Change Notification';
            $mail->Body = "The price has changed to $price.";
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
