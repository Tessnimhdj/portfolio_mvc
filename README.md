# portfolio_mvc













# Google reCAPTCHA - دليل الاستخدام

## 📁 هيكل الملفات

```
services/
└─ Recaptcha/
   ├─ RecaptchaService.php      # التحقق من جانب الخادم
   ├─ RecaptchaHelper.php       # عرض reCAPTCHA في الواجهة
   └─ recaptcha.config.php      # الإعدادات
```

## ⚙️ الإعداد الأولي

### 1. الحصول على مفاتيح Google reCAPTCHA

قم بزيارة [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin) وسجل موقعك للحصول على:
- **Site Key** (للواجهة الأمامية)
- **Secret Key** (للخادم)

### 2. تعديل ملف الإعدادات

افتح `recaptcha.config.php` وأدخل المفاتيح:

```php
return [
    'site_key' => 'مفتاح_الموقع_هنا',
    'secret_key' => 'المفتاح_السري_هنا',
    'version' => 'v2',  // أو 'v3'
    'theme' => 'light',
    'language' => 'ar',
];
```

## 📝 أمثلة الاستخدام

### مثال 1: reCAPTCHA v2 (Checkbox) - الطريقة الكلاسيكية

#### في صفحة HTML (form.php):

```php
<?php
require_once 'services/Recaptcha/RecaptchaHelper.php';

use Services\Recaptcha\RecaptchaHelper;

$recaptcha = new RecaptchaHelper();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نموذج اتصال</title>
</head>
<body>
    <form method="POST" action="process.php">
        <label>الاسم:</label>
        <input type="text" name="name" required>
        
        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" required>
        
        <label>الرسالة:</label>
        <textarea name="message" required></textarea>
        
        <!-- عرض reCAPTCHA -->
        <?php echo $recaptcha->render(['language' => 'ar']); ?>
        
        <button type="submit">إرسال</button>
    </form>
</body>
</html>
```

#### في صفحة المعالجة (process.php):

```php
<?php
require_once 'services/Recaptcha/RecaptchaService.php';

use Services\Recaptcha\RecaptchaService;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha = new RecaptchaService();
    
    // الحصول على استجابة reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    // التحقق
    $result = $recaptcha->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);
    
    if ($result['success']) {
        // التحقق نجح - معالجة البيانات
        echo "تم التحقق بنجاح!";
        // حفظ البيانات في قاعدة البيانات...
    } else {
        // التحقق فشل
        echo "خطأ: " . $result['error'];
    }
}
?>
```

### مثال 2: reCAPTCHA v3 (بدون تفاعل)

#### تعديل الإعدادات:

```php
// في recaptcha.config.php
return [
    'version' => 'v3',
    'min_score' => 0.5,
];
```

#### في صفحة HTML:

```php
<?php
require_once 'services/Recaptcha/RecaptchaHelper.php';

use Services\Recaptcha\RecaptchaHelper;

$recaptcha = new RecaptchaHelper();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
</head>
<body>
    <form id="login-form" method="POST" action="login.php">
        <input type="text" name="username" placeholder="اسم المستخدم" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
    
    <!-- عرض reCAPTCHA v3 -->
    <?php 
    echo $recaptcha->render([
        'action' => 'login',
        'form_id' => 'login-form'
    ]); 
    ?>
</body>
</html>
```

#### في صفحة المعالجة:

```php
<?php
require_once 'services/Recaptcha/RecaptchaService.php';

use Services\Recaptcha\RecaptchaService;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha = new RecaptchaService();
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    // التحقق مع النقاط
    $result = $recaptcha->verifyWithScore($recaptchaResponse, 0.5);
    
    if ($result['success']) {
        echo "النقاط: " . $result['score'];
        // متابعة عملية تسجيل الدخول
    } else {
        echo "فشل التحقق: " . $result['error'];
    }
}
?>
```

### مثال 3: Invisible reCAPTCHA

```php
<?php
$recaptcha = new RecaptchaHelper([
    'version' => 'invisible'
]);
?>

<form id="contact-form" method="POST" action="submit.php">
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    
    <?php 
    echo $recaptcha->renderInvisible('submit-btn', [
        'form_id' => 'contact-form',
        'button_text' => 'إرسال الرسالة'
    ]); 
    ?>
</form>
```

## 🎨 تخصيص المظهر

### تغيير المظهر (Theme)

```php
echo $recaptcha->renderV2([
    'theme' => 'dark',  // أو 'light'
    'size' => 'compact' // أو 'normal'
]);
```

### تغيير اللغة

```php
echo $recaptcha->renderScript('ar'); // العربية
echo $recaptcha->renderScript('en'); // الإنجليزية
echo $recaptcha->renderScript('fr'); // الفرنسية
```

## 🔧 الدوال المتاحة

### RecaptchaService

| الدالة | الوصف | المثال |
|--------|-------|--------|
| `verify($response, $ip)` | التحقق الكامل | `$recaptcha->verify($token)` |
| `isValid($response, $ip)` | التحقق السريع (true/false) | `if ($recaptcha->isValid($token))` |
| `verifyWithScore($response, $minScore, $ip)` | التحقق مع النقاط (v3) | `$recaptcha->verifyWithScore($token, 0.5)` |
| `getSiteKey()` | الحصول على Site Key | `$recaptcha->getSiteKey()` |

### RecaptchaHelper

| الدالة | الوصف | المثال |
|--------|-------|--------|
| `render($options)` | عرض reCAPTCHA كامل | `$helper->render()` |
| `renderScript($lang)` | عرض السكريبت فقط | `$helper->renderScript('ar')` |
| `renderV2($options)` | عرض v2 | `$helper->renderV2(['theme' => 'dark'])` |
| `renderV3($action, $formId)` | عرض v3 | `$helper->renderV3('login', 'form1')` |
| `renderInvisible($btnId, $options)` | عرض Invisible | `$helper->renderInvisible('btn')` |

## 🚀 الاستخدام في مشاريع متعددة

لإعادة استخدام هذا المكون في مشاريع أخرى:

1. **انسخ المجلد بالكامل**:
   ```
   cp -r services/Recaptcha /path/to/new-project/services/
   ```

2. **عدل الإعدادات** حسب المشروع الجديد

3. **استخدمه مباشرة**:
   ```php
   require_once 'services/Recaptcha/RecaptchaService.php';
   use Services\Recaptcha\RecaptchaService;
   
   $recaptcha = new RecaptchaService();
   ```

## 🔒 نصائح الأمان

1. **لا تشارك Secret Key** مطلقاً في الواجهة الأمامية
2. **استخدم HTTPS** دائماً في الإنتاج
3. **تحقق من IP** للحماية الإضافية:
   ```php
   $result = $recaptcha->verify($token, $_SERVER['REMOTE_ADDR']);
   ```
4. **اختبر في بيئة التطوير** باستخدام `test_mode => true`

## 🐛 استكشاف الأخطاء

### خطأ: "missing-input-secret"
المفتاح السري غير صحيح أو غير موجود في `recaptcha.config.php`

### خطأ: "invalid-input-response"
التوكن غير صحيح أو منتهي الصلاحية (يجب استخدام التوكن خلال دقيقتين)

### خطأ: "timeout-or-duplicate"
تم استخدام نفس التوكن مرتين أو انتهت صلاحيته

## 📚 موارد إضافية

- [وثائق Google reCAPTCHA](https://developers.google.com/recaptcha/docs/display)
- [أفضل الممارسات](https://developers.google.com/recaptcha/docs/faq)
- [مقارنة بين v2 و v3](https://developers.google.com/recaptcha/docs/versions)