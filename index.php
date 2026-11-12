<?php
require __DIR__ . '/lib.php';
$apps = load_apps();
$cat = $_GET['cat'] ?? '';
$q = trim($_GET['q'] ?? '');
$list = array_filter($apps, function ($a) use ($cat, $q) {
    if ($cat && $a['category'] !== $cat) return false;
    if ($q && mb_stripos($a['name'] . ' ' . $a['description'], $q) === false) return false;
    return true;
});
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e(site_title()) ?></title>
<style>
:root{--bg:#0f1220;--card:#181c30;--txt:#eef0fa;--mut:#9aa0bd;--acc:#5b8cff}
*{box-sizing:border-box}
body{margin:0;font-family:system-ui,Tahoma,sans-serif;background:var(--bg);color:var(--txt)}
header{padding:28px 16px;text-align:center}
h1{margin:0 0 6px;font-size:26px}
header p{margin:0;color:var(--mut)}
.bar{display:flex;gap:8px;max-width:760px;margin:0 auto 18px;padding:0 16px;flex-wrap:wrap}
.bar input{flex:1;min-width:160px;padding:10px 12px;border-radius:10px;border:1px solid #2a3050;background:var(--card);color:var(--txt)}
.bar a,.bar button{padding:10px 14px;border-radius:10px;border:0;background:var(--card);color:var(--txt);text-decoration:none;cursor:pointer}
.bar .on{background:var(--acc)}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;max-width:1000px;margin:0 auto;padding:0 16px 40px}
.card{background:var(--card);border-radius:16px;padding:18px;text-decoration:none;color:inherit;display:block}
.ic{font-size:40px}
.card h3{margin:10px 0 4px}
.card small{color:var(--mut)}
footer{text-align:center;color:var(--mut);padding:20px;font-size:13px}
</style>
</head>
<body>
<header><h1><?= e(site_title()) ?></h1><p>اكتشف تطبيقات وألعاب مختارة</p></header>
<form class="bar" method="get">
  <input name="q" value="<?= e($q) ?>" placeholder="ابحث...">
  <a href="/" class="<?= !$cat ? 'on' : '' ?>">الكل</a>
  <a href="?cat=<?= urlencode('تطبيقات') ?>" class="<?= $cat === 'تطبيقات' ? 'on' : '' ?>">تطبيقات</a>
  <a href="?cat=<?= urlencode('ألعاب') ?>" class="<?= $cat === 'ألعاب' ? 'on' : '' ?>">ألعاب</a>
</form>
<div class="grid">
<?php foreach ($list as $a): ?>
  <a class="card" href="app.php?id=<?= (int)$a['id'] ?>">
    <div class="ic"><?= e($a['icon']) ?></div>
    <h3><?= e($a['name']) ?></h3>
    <small><?= e($a['category']) ?> · ⭐ <?= e($a['rating']) ?></small>
  </a>
<?php endforeach; ?>
<?php if (!$list): ?><p style="grid-column:1/-1;text-align:center;color:var(--mut)">لا توجد نتائج.</p><?php endif; ?>
</div>
<footer>© <?= date('Y') ?> <?= e(site_title()) ?> · <a href="admin.php" style="color:var(--mut)">الإدارة</a></footer>
</body>
</html>
