<?php

namespace Services\Recaptcha;

class RecaptchaHelper
{
    private $siteKey;
    private $version;
    private $theme;
    private $size;

    public function __construct($config = null)
    {
        if ($config === null) {
            $config = require __DIR__ . '/recaptcha.config.php';
        }
        
        $this->siteKey = $config['site_key'];
        $this->version = $config['version'] ?? 'v2';
        $this->theme = $config['theme'] ?? 'light';
        $this->size = $config['size'] ?? 'normal';
    }

    /**
     * عرض سكريبت reCAPTCHA
     * 
     * @param string|null $language اللغة (ar, en, fr, إلخ)
     * @return string
     */
    public function renderScript($language = null)
    {
        if ($this->version === 'v3') {
            $url = "https://www.google.com/recaptcha/api.js?render={$this->siteKey}";
        } else {
            $url = "https://www.google.com/recaptcha/api.js";
            if ($language) {
                $url .= "?hl={$language}";
            }
        }

        return "<script src=\"{$url}\" async defer></script>";
    }

    /**
     * عرض الـ div الخاص بـ reCAPTCHA v2
     * 
     * @param array $options خيارات إضافية
     * @return string
     */
    public function renderV2($options = [])
    {
        $theme = $options['theme'] ?? $this->theme;
        $size = $options['size'] ?? $this->size;
        $callback = $options['callback'] ?? '';
        $expiredCallback = $options['expired-callback'] ?? '';
        $errorCallback = $options['error-callback'] ?? '';

        $attributes = [
            'class' => 'g-recaptcha',
            'data-sitekey' => $this->siteKey,
            'data-theme' => $theme,
            'data-size' => $size
        ];

        if ($callback) {
            $attributes['data-callback'] = $callback;
        }
        if ($expiredCallback) {
            $attributes['data-expired-callback'] = $expiredCallback;
        }
        if ($errorCallback) {
            $attributes['data-error-callback'] = $errorCallback;
        }

        $html = '<div';
        foreach ($attributes as $key => $value) {
            $html .= " {$key}=\"{$value}\"";
        }
        $html .= '></div>';

        return $html;
    }

    /**
     * عرض سكريبت reCAPTCHA v3
     * 
     * @param string $action اسم الإجراء (login, register, contact, إلخ)
     * @param string $formId معرف النموذج
     * @return string
     */
    public function renderV3($action, $formId)
    {
        return "
<script>
    grecaptcha.ready(function() {
        document.getElementById('{$formId}').addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute('{$this->siteKey}', {action: '{$action}'}).then(function(token) {
                // إضافة التوكن إلى النموذج
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'g-recaptcha-response';
                input.value = token;
                document.getElementById('{$formId}').appendChild(input);
                
                // إرسال النموذج
                document.getElementById('{$formId}').submit();
            });
        });
    });
</script>";
    }

    /**
     * عرض reCAPTCHA الكامل مع السكريبت
     * 
     * @param array $options
     * @return string
     */
    public function render($options = [])
    {
        $language = $options['language'] ?? null;
        $html = $this->renderScript($language) . "\n";

        if ($this->version === 'v3') {
            $action = $options['action'] ?? 'submit';
            $formId = $options['form_id'] ?? 'recaptcha-form';
            $html .= $this->renderV3($action, $formId);
        } else {
            $html .= $this->renderV2($options);
        }

        return $html;
    }

    /**
     * عرض Invisible reCAPTCHA
     * 
     * @param string $buttonId معرف زر الإرسال
     * @param array $options
     * @return string
     */
    public function renderInvisible($buttonId, $options = [])
    {
        $callback = $options['callback'] ?? 'onSubmit';
        
        $html = $this->renderScript($options['language'] ?? null) . "\n";
        $html .= "<button id=\"{$buttonId}\" class=\"g-recaptcha\" data-sitekey=\"{$this->siteKey}\" data-callback=\"{$callback}\" data-size=\"invisible\">";
        $html .= $options['button_text'] ?? 'إرسال';
        $html .= "</button>\n";
        $html .= "<script>
function {$callback}(token) {
    document.getElementById('" . ($options['form_id'] ?? 'recaptcha-form') . "').submit();
}
</script>";

        return $html;
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
     * الحصول على الإصدار
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}