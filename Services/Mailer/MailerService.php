<?php

namespace Services\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    private $mail;
    private $config;

    public function __construct($config = null)
    {
        if ($config === null) {
            $config = require __DIR__ . '/mailer.config.php';
        }
        
        $this->config = $config;
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }

    /**
     * Configure SMTP.
     */
    private function setupSMTP()
    {
        $this->mail->isSMTP();
        $this->mail->Host = $this->config['smtp']['host'];
        $this->mail->SMTPAuth = $this->config['smtp']['auth'];
        $this->mail->Username = $this->config['smtp']['username'];
        $this->mail->Password = $this->config['smtp']['password'];
        $this->mail->SMTPSecure = $this->config['smtp']['encryption'];
        $this->mail->Port = $this->config['smtp']['port'];
        $this->mail->CharSet = $this->config['charset'];
        $this->mail->Timeout = $this->config['timeout'];
        $this->mail->SMTPDebug = $this->config['debug'];
        
        // Default sender
        $this->mail->setFrom(
            $this->config['from']['email'],
            $this->config['from']['name']
        );
    }

    /**
     * Send a simple email.
     *
     * @param string $to Recipient email
     * @param string $subject Subject
     * @param string $body HTML body
     * @param string|null $toName Recipient name
     * @return array Result
     */
    public function send($to, $subject, $body, $toName = null)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($to, $toName ?? $to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            $this->mail->send();
            
            return [
                'success' => true,
                'message' => 'تم إرسال البريد بنجاح'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل الإرسال',
                'error' => $this->mail->ErrorInfo
            ];
        }
    }

    /**
     * Send an email using an HTML template.
     *
     * @param string $to Recipient email
     * @param string $subject Subject
     * @param string $content Main content
     * @param array $options Extra options
     * @return array Result
     */
    public function sendWithTemplate($to, $subject, $content, $options = [])
    {
        $toName = $options['to_name'] ?? null;
        $header = $options['header'] ?? '';
        $footer = $options['footer'] ?? $this->config['templates']['footer'];
        
        $body = "
        <!DOCTYPE html>
        <html lang='ar' dir='rtl'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; }
                .content { padding: 30px; background: #ffffff; }
            </style>
        </head>
        <body>
            <div class='container'>
                {$header}
                <div class='content'>{$content}</div>
                {$footer}
            </div>
        </body>
        </html>
        ";
        
        return $this->send($to, $subject, $body, $toName);
    }

    /**
     * Send an email to multiple recipients.
     *
     * @param array $recipients Recipients as ['email' => 'name']
     * @param string $subject Subject
     * @param string $body Body
     * @return array Result
     */
    public function sendToMultiple($recipients, $subject, $body)
    {
        try {
            $this->mail->clearAddresses();
            
            foreach ($recipients as $email => $name) {
                if (is_numeric($email)) {
                    $this->mail->addAddress($name);
                } else {
                    $this->mail->addAddress($email, $name);
                }
            }
            
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            $this->mail->send();
            
            return [
                'success' => true,
                'message' => 'تم إرسال البريد لجميع المستلمين'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل الإرسال',
                'error' => $this->mail->ErrorInfo
            ];
        }
    }

    /**
     * Send an email with attachments.
     *
     * @param string $to Recipient email
     * @param string $subject Subject
     * @param string $body Body
     * @param array $attachments Attachment paths
     * @return array Result
     */
    public function sendWithAttachments($to, $subject, $body, $attachments = [])
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            // Add attachments
            foreach ($attachments as $attachment) {
                $this->mail->addAttachment($attachment);
            }
            
            $this->mail->send();
            
            return [
                'success' => true,
                'message' => 'تم إرسال البريد مع المرفقات'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل الإرسال',
                'error' => $this->mail->ErrorInfo
            ];
        }
    }

    /**
     * Set a custom sender.
     *
     * @param string $email Email
     * @param string $name Name
     */
    public function setFrom($email, $name = '')
    {
        $this->mail->setFrom($email, $name);
    }

    /**
     * Set the reply-to address.
     *
     * @param string $email Email
     * @param string $name Name
     */
    public function setReplyTo($email, $name = '')
    {
        $this->mail->addReplyTo($email, $name);
    }

    /**
     * Get the PHPMailer instance for advanced control.
     *
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->mail;
    }
}