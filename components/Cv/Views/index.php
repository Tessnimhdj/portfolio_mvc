<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>السيرة الذاتية</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/CSS/admin.css">
</head>
<body>
    <nav>
        <a href="<?= BASE_URL ?>/Dashboard/index">لوحة التحكم</a>
        <a href="<?= BASE_URL ?>/Projects/index">المشاريع</a>
        <a href="<?= BASE_URL ?>/Skills/index">المهارات</a>
        <a href="<?= BASE_URL ?>/Cv/index">السيرة الذاتية</a>
        <a href="<?= BASE_URL ?>/Messages/index">الرسائل</a>
        <a href="<?= BASE_URL ?>/">الموقع</a>
        <a href="<?= BASE_URL ?>/Auth/logout">خروج</a>
    </nav>

    <h1>السيرة الذاتية</h1>

    <?php $errors = $errors ?? []; ?>
    <?php if (!empty($errors)): ?>
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($cv)): ?>
        <p>الملف الحالي: <?= htmlspecialchars($cv['original_name'] ?? $cv['file_name'] ?? 'CV.pdf') ?></p>
        <p>
            <a href="<?= htmlspecialchars($cv['file_url']) ?>" target="_blank" rel="noopener noreferrer">عرض PDF</a>
            |
            <a href="<?= BASE_URL ?>/Cv/delete" onclick="return confirm('حذف ملف السيرة الذاتية؟');">حذف</a>
        </p>
        <p class="note">رفع ملف جديد يستبدل الملف الحالي</p>
    <?php else: ?>
        <p>لا يوجد ملف سيرة ذاتية بعد</p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/Cv/store" enctype="multipart/form-data">
        <label for="cv_file"><?= !empty($cv) ? 'استبدال الملف' : 'رفع ملف PDF' ?></label>
        <input type="file" id="cv_file" name="cv_file" accept="application/pdf">
        <button type="submit">حفظ</button>
    </form>
</body>
</html>
