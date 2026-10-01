<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/CSS/admin.css">
</head>
<body>
    <h1>تسجيل الدخول</h1>

    <?php if (isset($error) && $error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/Auth/login">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" required autofocus>

        <label>كلمة المرور</label>
        <input type="password" name="password" required>

        <button type="submit">دخول</button>
    </form>
</body>
</html>
