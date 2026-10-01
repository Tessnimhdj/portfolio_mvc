<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>عرض الرسالة</title>
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

<h1>رسالة من <?= htmlspecialchars($message['name'] ?? '') ?></h1>
<p>البريد: <a href="mailto:<?= htmlspecialchars($message['email'] ?? '') ?>"><?= htmlspecialchars($message['email'] ?? '') ?></a></p>
<p>التاريخ: <?= htmlspecialchars($message['created_at'] ?? '') ?></p>
<p>الحالة: <?= (($message['status'] ?? '') === 'unread') ? 'غير مقروءة' : 'مقروءة' ?></p>
<hr>
<p><?= nl2br(htmlspecialchars($message['message'] ?? '')) ?></p>
<p>
    <a href="<?= BASE_URL ?>/Messages/index">رجوع</a>
    |
    <a href="<?= BASE_URL ?>/Messages/delete?id=<?= $message['id'] ?>" onclick="return confirm('حذف هذه الرسالة؟');">حذف</a>
</p>
</body>
</html>
