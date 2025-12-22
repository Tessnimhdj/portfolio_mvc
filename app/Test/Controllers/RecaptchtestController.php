<?php

namespace App\Test\RecaptchtestController\Controllers;

use Services\Recaptcha\RecaptchaService;

class  RecaptchtestController
{
    public function index()
    {
        $result = null;
        $name = '';
        
        // إذا كان الطلب POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../../services/Recaptcha/RecaptchaService.php';
            
            $name = htmlspecialchars($_POST['name'] ?? 'زائر');
            $recaptcha = new RecaptchaService();
            $token = $_POST['g-recaptcha-response'] ?? '';
            $result = $recaptcha->verify($token, $_SERVER['REMOTE_ADDR']);
        }
        
        // عرض الصفحة
        ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>اختبار reCAPTCHA</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://www.google.com/recaptcha/api.js?hl=ar" async defer></script>
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        
                        <!-- العنوان -->
                        <div class="card shadow-lg mb-4">
                            <div class="card-body text-center bg-primary text-white">
                                <h1 class="display-4">🧪 اختبار reCAPTCHA</h1>
                                <p class="lead mb-0">عرض توضيحي بسيط</p>
                            </div>
                        </div>
                        
                        <?php if ($result !== null): ?>
                            <!-- النتيجة -->
                            <?php if ($result['success']): ?>
                                <!-- نجح -->
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">✅ نجح التحقق!</h4>
                                    <p class="mb-0">مرحباً <strong><?= $name ?></strong>، تم التحقق بنجاح!</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                
                                <div class="card shadow mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">📊 معلومات من Google</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                                <th width="40%">الحالة</th>
                                                <td><span class="badge bg-success">نجح</span></td>
                                            </tr>
                                            <?php if (!empty($result['challenge_ts'])): ?>
                                            <tr>
                                                <th>وقت التحقق</th>
                                                <td><code><?= $result['challenge_ts'] ?></code></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($result['hostname'])): ?>
                                            <tr>
                                                <th>النطاق</th>
                                                <td><code><?= $result['hostname'] ?></code></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th>عنوان IP</th>
                                                <td><code><?= $_SERVER['REMOTE_ADDR'] ?></code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- فشل -->
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">❌ فشل التحقق!</h4>
                                    <p class="mb-0"><strong>السبب:</strong> <?= $result['error'] ?></p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                
                                <div class="card shadow mb-4">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="mb-0">📋 التفاصيل</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                                <th width="40%">الحالة</th>
                                                <td><span class="badge bg-danger">فشل</span></td>
                                            </tr>
                                            <tr>
                                                <th>رسالة الخطأ</th>
                                                <td><code><?= $result['error'] ?></code></td>
                                            </tr>
                                            <tr>
                                                <th>عنوان IP</th>
                                                <td><code><?= $_SERVER['REMOTE_ADDR'] ?></code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- النموذج -->
                        <div class="card shadow">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">📝 نموذج الاختبار</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">أدخل اسمك:</label>
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               id="name" 
                                               name="name" 
                                               placeholder="مثال:تسنيم حاج رجم" 
                                               required>
                                    </div>
                                    
                                    <!-- reCAPTCHA -->
                                    <div class="text-center my-4 p-3 bg-light rounded">
                                        <div class="g-recaptcha d-inline-block" 
                                             data-sitekey="6Le-AiQsAAAAAIxzVQ9HWlMxv35Hqe_GYMiILt_8">
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        🚀 اختبار التحقق
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- التعليمات -->
                        <div class="card shadow mt-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">💡 خطوات الاختبار</h6>
                            </div>
                            <div class="card-body">
                                <ol class="mb-0">
                                    <li class="mb-2"><strong>اختبار 1:</strong> لا تحل الكابتشا واضغط إرسال → سترى خطأ ❌</li>
                                    <li class="mb-0"><strong>اختبار 2:</strong> احل الكابتشا ثم اضغط إرسال → سترى نجاح ✅</li>
                                </ol>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}