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
     * Verify the reCAPTCHA response.
     *
     * @param string $response Token sent from the form
     * @param string|null $remoteIp User IP address (optional)
     * @return array Verification result
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
     * Quick check: returns true or false only.
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
     * Get the site key.
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * Verify with a minimum score (reCAPTCHA v3).
     *
     * @param string $response
     * @param float $minScore Minimum accepted score (0.0 to 1.0)
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