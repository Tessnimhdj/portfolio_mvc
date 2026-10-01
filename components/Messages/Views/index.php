<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>الرسائل</title>
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

<h1>الرسائل</h1>

<?php if (empty($messages)): ?>
<p>لا توجد رسائل بعد</p>
<?php else: ?>
<table>
<tr>
    <th>الحالة</th>
    <th>الاسم</th>
    <th>البريد</th>
    <th>معاينة</th>
    <th>التاريخ</th>
    <th></th>
</tr>
<?php foreach ($messages as $msg): ?>
<tr>
    <td><?= (($msg['status'] ?? 'unread') === 'unread') ? 'غير مقروءة' : 'مقروءة' ?></td>
    <td><?= htmlspecialchars($msg['name'] ?? '') ?></td>
    <td><?= htmlspecialchars($msg['email'] ?? '') ?></td>
    <td><?= htmlspecialchars(substr($msg['message'] ?? '', 0, 80)) ?></td>
    <td><?= htmlspecialchars($msg['created_at'] ?? '') ?></td>
    <td>
        <a href="<?= BASE_URL ?>/Messages/show?id=<?= $msg['id'] ?>">عرض</a>
        |
        <a href="<?= BASE_URL ?>/Messages/delete?id=<?= $msg['id'] ?>" onclick="return confirm('حذف هذه الرسالة؟');">حذف</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>
