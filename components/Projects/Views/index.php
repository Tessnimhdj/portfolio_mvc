<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إدارة المشاريع</title>
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

<h1>المشاريع</h1>
<p><a href="<?= BASE_URL ?>/Projects/create">إضافة مشروع</a></p>

<?php if (empty($projects)): ?>
<p>لا توجد مشاريع بعد</p>
<?php else: ?>
<div class="cards">
<?php foreach ($projects as $project): ?>
<div class="card">
<?php if (!empty($project['cover_image'])): ?>
<img src="<?= htmlspecialchars($project['cover_image']) ?>" alt="">
<?php endif; ?>
<h3><?= htmlspecialchars($project['title'] ?? '') ?></h3>
<p><?= htmlspecialchars(substr($project['description'] ?? '', 0, 100)) ?></p>
<a href="<?= BASE_URL ?>/Projects/edit?id=<?= $project['id'] ?>">تعديل</a>
|
<a href="<?= BASE_URL ?>/Projects/delete?id=<?= $project['id'] ?>"
onclick="return confirm('حذف هذا المشروع؟');">حذف</a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>
