<?php

namespace Services\Recaptcha;

class RecaptchaService
{
    private $secretKey;
    private $siteKey;
    private $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct($config = null)
    {
        if ($config === null) {
            $config = require __DIR__ . '/recaptcha.config.php';
        }
        
        $this->secretKey = $config['secret_key'];
        $this->siteKey = $config['site_key'];
    }

    /**
     * التحقق من استجابة reCAPTCHA
     * 
     * @param string $response الرمز المرسل من النموذج
     * @param string|null $remoteIp عنوان IP الخاص بالمستخدم (اختياري)
     * @return array نتيجة التحقق
     */
    public function verify($response, $remoteIp = null)
    {
        if (empty($response)) {
            return [
                'success' => false,
                'error' => 'يرجى إكمال التحقق من reCAPTCHA'
            ];
        }

        $data = [
            'secret' => $this->secretKey,
            'response' => $response
        ];

        if ($remoteIp !== null) {
            $data['remoteip'] = $remoteIp;
        }

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($this->verifyUrl, false, $context);
        
        if ($result === false) {
            return [
                'success' => false,
                'error' => 'فشل الاتصال بخادم Google reCAPTCHA'
            ];
        }

        $resultJson = json_decode($result, true);

        if (!isset($resultJson['success'])) {
            return [
                'success' => false,
                'error' => 'استجابة غير صالحة من خادم reCAPTCHA'
            ];
        }

        if ($resultJson['success']) {
            return [
                'success' => true,
                'score' => $resultJson['score'] ?? null,
                'action' => $resultJson['action'] ?? null,
                'challenge_ts' => $resultJson['challenge_ts'] ?? null,
                'hostname' => $resultJson['hostname'] ?? null
            ];
        }

        return [
            'success' => false,
            'error' => 'فشل التحقق من reCAPTCHA',
            'error_codes' => $resultJson['error-codes'] ?? []
        ];
    }

    /**
     * التحقق السريع - يرجع true أو false فقط
     * 
     * @param string $response
     * @param string|null $remoteIp
     * @return bool
     */
    public function isValid($response, $remoteIp = null)
    {
        $result = $this->verify($response, $remoteIp);
        return $result['success'] === true;
    }

    /**
     * الحصول على Site Key
     * 
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * التحقق مع حد أدنى للنقاط (لـ reCAPTCHA v3)
     * 
     * @param string $response
     * @param float $minScore الحد الأدنى المقبول (0.0 إلى 1.0)
     * @param string|null $remoteIp
     * @return array
     */
    public function verifyWithScore($response, $minScore = 0.5, $remoteIp = null)
    {
        $result = $this->verify($response, $remoteIp);
        
        if (!$result['success']) {
            return $result;
        }

        $score = $result['score'] ?? 0;
        
        if ($score < $minScore) {
            return [
                'success' => false,
                'error' => 'النقاط منخفضة جداً - قد تكون روبوت',
                'score' => $score
            ];
        }

        return $result;
    }
}