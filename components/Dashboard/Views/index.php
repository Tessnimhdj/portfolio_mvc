<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
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

    <h1>لوحة التحكم</h1>
    <p>مرحباً <?= htmlspecialchars($admin['name'] ?? 'Admin') ?></p>

    <p>الرسائل: <?= (int) $stats['total_messages'] ?> (<?= (int) $stats['unread_messages'] ?> غير مقروءة)</p>
    <p>المشاريع: <?= (int) $stats['total_projects'] ?></p>
    <p>المهارات: <?= (int) $stats['total_skills'] ?></p>
    <p>السيرة الذاتية: <?= !empty($stats['has_cv']) ? 'مرفوعة' : 'غير مرفوعة' ?></p>

    <h2>آخر الرسائل</h2>
    <?php if (empty($stats['recent_messages'])): ?>
        <p>لا توجد رسائل حتى الآن</p>
    <?php else: ?>
        <ul>
            <?php foreach ($stats['recent_messages'] as $msg): ?>
                <li>
                    <?= htmlspecialchars($msg['name'] ?? '') ?>
                    — <?= htmlspecialchars(substr($msg['message'] ?? '', 0, 80)) ?>
                    <a href="<?= BASE_URL ?>/Messages/show?id=<?= $msg['id'] ?>">عرض</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p><a href="<?= BASE_URL ?>/Messages/index">كل الرسائل</a></p>
    <?php endif; ?>
</body>
</html>
