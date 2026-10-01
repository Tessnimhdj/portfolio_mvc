<?php
namespace Services;

use Services\Recaptcha\RecaptchaService;
use Services\Mailer\MailerHelper;

// Load required libraries
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Recaptcha/RecaptchaService.php';
require __DIR__ . '/Mailer/MailerHelper.php';
require_once __DIR__ . '/Storage/JsonStorage.php';
require_once __DIR__ . '/../components/Messages/Models/MessageModel.php';

// Allow this file to be called directly
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $controller = new ContactController();
    $controller->sendEmail();
    exit;
}

class ContactController
{
    /**
     * Send email after reCAPTCHA verification.
     * Called from JavaScript via fetch.
     */
    public function sendEmail()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Verify reCAPTCHA first
        $recaptchaService = new RecaptchaService();
        $recaptchaToken = $data['recaptcha_token'] ?? '';
        
        // Verify the token
        $recaptchaResult = $recaptchaService->verify($recaptchaToken, $_SERVER['REMOTE_ADDR']);
        
        // Return an error if verification failed
        if (!$recaptchaResult['success']) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false, 
                'message' => $recaptchaResult['error']
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // Verification succeeded: send both emails using the mailer helper
        
        $visitorName = $data['name'] ?? '';
        $visitorEmail = $data['email'] ?? '';
        $visitorMessage = $data['message'] ?? '';
        
        // Validate form data
        if (empty($visitorName) || empty($visitorEmail) || empty($visitorMessage)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'message' => 'الرجاء ملء جميع الحقول'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $messageModel = new \components\Messages\Models\MessageModel();
            $messageModel->create([
                'name' => $visitorName,
                'email' => $visitorEmail,
                'message' => $visitorMessage,
                'status' => 'unread',
            ]);

            $mailerHelper = new MailerHelper();
            
            // First email: notify the site admin
            $result1 = $mailerHelper->sendAdminNotification(
                'tessnimhdj@gmail.com',
                [
                    'name' => $visitorName,
                    'email' => $visitorEmail,
                    'message' => $visitorMessage
                ]
            );
            
            // Second email: thank-you message to the visitor
            $result2 = $mailerHelper->sendThankYouEmail(
                $visitorEmail,
                $visitorName,
                $visitorMessage
            );
            
            // Check that both emails were sent
            if ($result1['success'] && $result2['success']) {
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'success' => true, 
                    'message' => 'تم إرسال الرسالة بنجاح'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $errorMsg = !$result1['success'] ? $result1['message'] : $result2['message'];
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'success' => false, 
                    'message' => "فشل الإرسال: {$errorMsg}"
                ], JSON_UNESCAPED_UNICODE);
            }
            
        } catch (\Exception $e) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false, 
                'message' => "حدث خطأ: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}