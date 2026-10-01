<?php

namespace Services\Mailer;

require_once __DIR__ . '/MailerService.php';


class MailerHelper
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new MailerService();
    }

    /**
     * Send a welcome email.
     *
     * @param string $to Email
     * @param string $name User name
     * @return array Result
     */
    public function sendWelcomeEmail($to, $name)
    {
        $subject = "مرحباً بك في موقعنا!";
        
        $content = "
        <div style='text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>
            <h1 style='color: white; margin: 0;'>مرحباً {$name}!</h1>
        </div>
        <div style='padding: 30px;'>
            <p style='font-size: 18px; color: #333;'>
                نحن سعداء بانضمامك إلينا!
            </p>
            <p style='color: #555; line-height: 1.8;'>
                يمكنك الآن الاستمتاع بجميع خدماتنا. إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.
            </p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='#' style='background: #667eea; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                    ابدأ الآن
                </a>
            </div>
        </div>
        ";
        
        return $this->mailer->sendWithTemplate($to, $subject, $content, [
            'to_name' => $name
        ]);
    }

    /**
     * Send a thank-you email.
     *
     * @param string $to Email
     * @param string $name Name
     * @param string $message Copy of the user's message
     * @return array Result
     */
    public function sendThankYouEmail($to, $name, $message = '')
    {
        $subject = "شكراً لتواصلك معنا";
        
        $messageSection = '';
        if (!empty($message)) {
            $messageSection = "
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;'>
                <h3 style='color: #667eea; margin-top: 0;'>نسخة من رسالتك:</h3>
                <p style='color: #666; white-space: pre-wrap;'>" . htmlspecialchars($message) . "</p>
            </div>
            ";
        }
        
        $content = "
        <div style='text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);'>
            <h1 style='color: white; margin: 0;'>تم استلام رسالتك</h1>
        </div>
        <div style='padding: 30px;'>
            <p style='font-size: 18px; color: #333;'>
                مرحباً <strong>{$name}</strong>،
            </p>
            <p style='color: #555; line-height: 1.8;'>
                شكراً لتواصلك معنا! تم استلام رسالتك بنجاح وسنقوم بالرد عليك في أقرب وقت ممكن.
            </p>
            {$messageSection}
            <p style='color: #555;'>
                إذا كان لديك أي استفسارات إضافية، لا تتردد في التواصل معنا مرة أخرى.
            </p>
        </div>
        ";
        
        return $this->mailer->sendWithTemplate($to, $subject, $content, [
            'to_name' => $name
        ]);
    }

    /**
     * Send a notification to the admin.
     *
     * @param string $adminEmail Admin email
     * @param array $data Form data
     * @return array Result
     */
    public function sendAdminNotification($adminEmail, $data)
    {
        $name = $data['name'] ?? 'مجهول';
        $email = $data['email'] ?? 'غير متوفر';
        $message = $data['message'] ?? '';
        
        $subject = "رسالة جديدة من: {$name}";
        
        $content = "
        <div style='padding: 30px;'>
            <h2 style='color: #667eea;'>رسالة جديدة من موقعك</h2>
            <hr style='border: 1px solid #ddd;'>
            
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 10px; background: #f8f9fa; font-weight: bold; width: 30%;'>الاسم:</td>
                    <td style='padding: 10px;'>{$name}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; background: #f8f9fa; font-weight: bold;'>البريد:</td>
                    <td style='padding: 10px;'><a href='mailto:{$email}'>{$email}</a></td>
                </tr>
                <tr>
                    <td style='padding: 10px; background: #f8f9fa; font-weight: bold;'>التاريخ:</td>
                    <td style='padding: 10px;'>" . date('Y-m-d H:i:s') . "</td>
                </tr>
            </table>
            
            <hr style='border: 1px solid #ddd;'>
            
            <h3 style='color: #333; margin-top: 20px;'>الرسالة:</h3>
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;'>
                " . nl2br(htmlspecialchars($message)) . "
            </div>
            
            <p style='color: #666; font-size: 12px; margin-top: 20px;'>
                يمكنك الرد مباشرة على هذا البريد للتواصل مع {$name}
            </p>
        </div>
        ";
        
        $this->mailer->setReplyTo($email, $name);
        
        return $this->mailer->sendWithTemplate($adminEmail, $subject, $content);
    }

    /**
     * Send a verification code (OTP).
     *
     * @param string $to Email
     * @param string $code Verification code
     * @return array Result
     */
    public function sendVerificationCode($to, $code)
    {
        $subject = "رمز التحقق الخاص بك";
        
        $content = "
        <div style='text-align: center; padding: 40px 20px;'>
            <h1 style='color: #333;'>رمز التحقق</h1>
            <p style='color: #666; font-size: 16px;'>استخدم الرمز التالي للتحقق من حسابك:</p>
            
            <div style='background: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 10px;'>
                <div style='font-size: 48px; font-weight: bold; color: #667eea; letter-spacing: 10px;'>
                    {$code}
                </div>
            </div>
            
            <p style='color: #999; font-size: 14px;'>
                هذا الرمز صالح لمدة 10 دقائق فقط
            </p>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px; border-left: 4px solid #ffc107;'>
                <p style='color: #856404; margin: 0; font-size: 14px;'>
                    إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة
                </p>
            </div>
        </div>
        ";
        
        return $this->mailer->sendWithTemplate($to, $subject, $content);
    }

    /**
     * Send a password reset notification.
     *
     * @param string $to Email
     * @param string $resetLink Reset link
     * @return array Result
     */
    public function sendPasswordReset($to, $resetLink)
    {
        $subject = "إعادة تعيين كلمة المرور";
        
        $content = "
        <div style='padding: 30px;'>
            <h2 style='color: #333;'>إعادة تعيين كلمة المرور</h2>
            <p style='color: #555; line-height: 1.8;'>
                تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك.
            </p>
            <p style='color: #555; line-height: 1.8;'>
                إذا كنت أنت من طلب ذلك، اضغط على الزر أدناه:
            </p>
            
            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}' style='background: #dc3545; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>
                    إعادة تعيين كلمة المرور
                </a>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>
                <p style='color: #856404; margin: 0;'>
                    <strong>تحذير:</strong> إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة وتأكد من أمان حسابك.
                </p>
            </div>
            
            <p style='color: #999; font-size: 12px; margin-top: 20px;'>
                هذا الرابط صالح لمدة ساعة واحدة فقط
            </p>
        </div>
        ";
        
        return $this->mailer->sendWithTemplate($to, $subject, $content);
    }
}