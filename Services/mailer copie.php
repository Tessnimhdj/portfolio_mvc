<?php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Services\Recaptcha\RecaptchaService;

// ✅ تحميل المكتبات المطلوبة
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Recaptcha/RecaptchaService.php';

// ✅ جعل الملف قابل للاستدعاء مباشرة
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $controller = new ContactController();
    $controller->sendEmail();
    exit;
}

class ContactController
{
    /**
     * إرسال البريد الإلكتروني مع التحقق من reCAPTCHA
     * يتم استدعاؤها من JavaScript عبر fetch
     */
    public function sendEmail()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // ============================================
        // 🔒 التحقق من reCAPTCHA أولاً
        // ============================================
        $recaptchaService = new RecaptchaService();
        $recaptchaToken = $data['recaptcha_token'] ?? '';
        
        // التحقق من التوكن
        $recaptchaResult = $recaptchaService->verify($recaptchaToken, $_SERVER['REMOTE_ADDR']);
        
        // إذا فشل التحقق، أرسل خطأ
        if (!$recaptchaResult['success']) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false, 
                'message' => $recaptchaResult['error']
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // ============================================
        // ✅ التحقق نجح - إرسال البريدين
        // ============================================
        
        $visitorName = $data['name'] ?? '';
        $visitorEmail = $data['email'] ?? '';
        $visitorMessage = $data['message'] ?? '';
        
        // التحقق من البيانات
        if (empty($visitorName) || empty($visitorEmail) || empty($visitorMessage)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'message' => 'الرجاء ملء جميع الحقول'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            // ============================================
            // 📧 البريد الأول: إرسال لك (المالك)
            // ============================================
            $mail = new PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tessnimhdj@gmail.com';
            $mail->Password = 'fdnh spht qujh dlhr';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // إرسال لك (المالك)
            $mail->setFrom('tessnimhdj@gmail.com', 'موقعك الإلكتروني');
            $mail->addAddress('tessnimhdj@gmail.com', 'Tessnim Hadjredjem'); // بريدك
            $mail->addReplyTo($visitorEmail, $visitorName); // للرد مباشرة
            
            $mail->isHTML(true);
            $mail->Subject = "رسالة جديدة من: {$visitorName}";
            
            // محتوى البريد الأول
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; direction: rtl; text-align: right;'>
                    <h2 style='color: #667eea;'>📧 رسالة جديدة من موقعك</h2>
                    <hr style='border: 1px solid #ddd;'>
                    
                    <p><strong>👤 الاسم:</strong> {$visitorName}</p>
                    <p><strong>📧 البريد الإلكتروني:</strong> {$visitorEmail}</p>
                    <p><strong>⏰ التاريخ:</strong> " . date('Y-m-d H:i:s') . "</p>
                    
                    <hr style='border: 1px solid #ddd;'>
                    
                    <h3 style='color: #333;'>💬 الرسالة:</h3>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;'>
                        " . nl2br(htmlspecialchars($visitorMessage)) . "
                    </div>
                    
                    <hr style='border: 1px solid #ddd;'>
                    <p style='color: #666; font-size: 12px;'>
                        يمكنك الرد مباشرة على هذا البريد للتواصل مع {$visitorName}
                    </p>
                </div>
            ";

            // إرسال البريد الأول
            $mail->send();
            
            // ============================================
            // 📧 البريد الثاني: رسالة شكر للزائر
            // ============================================
            $mail->clearAddresses(); // مسح العناوين السابقة
            $mail->clearReplyTos();
            
            // إرسال للزائر
            $mail->setFrom('tessnimhdj@gmail.com', 'Tessnim Hadjredjem');
            $mail->addAddress($visitorEmail, $visitorName);
            
            $mail->Subject = "شكراً لتواصلك معنا 🙏";
            
            // محتوى رسالة الشكر
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; direction: rtl; text-align: right;'>
                    <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                        <h1 style='color: white; margin: 0;'>✅ تم استلام رسالتك بنجاح!</h1>
                    </div>
                    
                    <div style='padding: 30px; background: #f8f9fa;'>
                        <p style='font-size: 18px; color: #333;'>مرحباً <strong>{$visitorName}</strong>،</p>
                        
                        <p style='color: #555; line-height: 1.8;'>
                            شكراً لتواصلك معنا! تم استلام رسالتك بنجاح وسنقوم بالرد عليك في أقرب وقت ممكن.
                        </p>
                        
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;'>
                            <h3 style='color: #667eea; margin-top: 0;'>📝 نسخة من رسالتك:</h3>
                            <p style='color: #666; font-style: italic;'>
                                " . nl2br(htmlspecialchars($visitorMessage)) . "
                            </p>
                        </div>
                        
                        <p style='color: #555;'>
                            إذا كان لديك أي استفسارات إضافية، لا تتردد في التواصل معنا مرة أخرى.
                        </p>
                        
                        <div style='text-align: center; margin-top: 30px;'>
                            <p style='color: #888; font-size: 14px;'>
                                مع أطيب التحيات،<br>
                                <strong>فريق Tessnim Hadjredjem</strong>
                            </p>
                        </div>
                    </div>
                    
                    <div style='background: #333; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;'>
                        <p style='color: #aaa; font-size: 12px; margin: 0;'>
                            هذه رسالة تلقائية، الرجاء عدم الرد عليها
                        </p>
                    </div>
                </div>
            ";

            // إرسال البريد الثاني
            $mail->send();

            // ✅ نجح إرسال البريدين
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => true, 
                'message' => 'تم إرسال الرسالة بنجاح ✅'
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false, 
                'message' => "فشل الإرسال: {$mail->ErrorInfo}"
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}