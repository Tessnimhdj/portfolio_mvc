<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مهارة</title>
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

    <h1>إضافة مهارة</h1>

    <?php $errors = $errors ?? []; ?>
    <?php $old = $old ?? []; ?>

    <?php if (!empty($errors)): ?>
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/Skills/store" enctype="multipart/form-data">
        <label for="title">اسم المهارة</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">

        <label for="category">التصنيف</label>
        <input type="text" id="category" name="category" placeholder="مثال: Frontend, Backend, Tools" value="<?= htmlspecialchars($old['category'] ?? '') ?>">

        <label for="description">الوصف المختصر</label>
        <textarea id="description" name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

        <label for="details">التفاصيل</label>
        <textarea id="details" name="details"><?= htmlspecialchars($old['details'] ?? '') ?></textarea>

        <label for="cover_image">أيقونة / صورة (اختياري)</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">

        <button type="submit">حفظ</button>
    </form>
</body>
</html>
