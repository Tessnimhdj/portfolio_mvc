<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مشروع</title>
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

    <h1>إضافة مشروع</h1>

    <?php $errors = $errors ?? []; ?>
    <?php $old = $old ?? []; ?>

    <?php if (!empty($errors)): ?>
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/Projects/store" enctype="multipart/form-data">
        <label for="title">العنوان</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">

        <label for="description">الوصف المختصر</label>
        <textarea id="description" name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

        <label for="details">التفاصيل الكاملة</label>
        <textarea id="details" name="details"><?= htmlspecialchars($old['details'] ?? '') ?></textarea>

        <label for="cover_image">صورة الغلاف</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">

        <label for="technologies">التقنيات</label>
        <input type="text" id="technologies" name="technologies" placeholder="مثال: PHP, Laravel, JavaScript" value="<?= htmlspecialchars($old['technologies'] ?? '') ?>">

        <label for="demo_url">رابط العرض</label>
        <input type="text" id="demo_url" name="demo_url" value="<?= htmlspecialchars($old['demo_url'] ?? '') ?>">

        <label for="github_url">رابط GitHub</label>
        <input type="text" id="github_url" name="github_url" value="<?= htmlspecialchars($old['github_url'] ?? '') ?>">

        <button type="submit">حفظ</button>
    </form>
</body>
</html>
