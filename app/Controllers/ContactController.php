<?php

namespace app\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class ContactController
{

    public function sendEmail()
    {
        // ✅ قراءة البيانات من JSON بدلاً من $_POST
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $mail = new PHPMailer(true);

        $from = 'tessnimhdj@gmail.com';
        $from_name = 'Tessnim Hadjredjem';
        $to = $data['email'];        // ✅ استخدم $data بدلاً من $_POST
        $to_name = $data['name'];    // ✅
        $subject = 'Test Email';
        $body = $data['message'];    // ✅

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tessnimhdj@gmail.com';
            $mail->Password = 'fdnh spht qujh dlhr';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($from, $from_name);
            $mail->addAddress($to, $to_name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'تم إرسال الرسالة بنجاح']);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "فشل الإرسال: {$mail->ErrorInfo}"]);
        }
    }
}