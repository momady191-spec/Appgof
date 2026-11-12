<?php
require __DIR__ . '/lib.php';
$id = (int)($_GET['id'] ?? 0);
$app = null;
foreach (load_apps() as $a) if ((int)$a['id'] === $id) $app = $a;
if (!$app) { http_response_code(404); echo 'التطبيق غير موجود'; exit; }
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($app['name']) ?> - <?= e(site_title()) ?></title>
<style>
body{margin:0;font-family:system-ui,Tahoma,sans-serif;background:#0f1220;color:#eef0fa}
.wrap{max-width:640px;margin:0 auto;padding:24px 16px}
.box{background:#181c30;border-radius:18px;padding:24px;text-align:center}
.ic{font-size:72px}
.btn{display:inline-block;margin-top:16px;padding:12px 24px;border-radius:12px;background:#5b8cff;color:#fff;text-decoration:none}
a.back{color:#9aa0bd;text-decoration:none}
p{color:#c5c9e0;line-height:1.8}
</style>
</head>
<body><div class="wrap">
<p><a class="back" href="/">← الرجوع</a></p>
<div class="box">
  <div class="ic"><?= e($app['icon']) ?></div>
  <h1><?= e($app['name']) ?></h1>
  <small><?= e($app['category']) ?> · ⭐ <?= e($app['rating']) ?></small>
  <p><?= e($app['description']) ?></p>
  <a class="btn" href="<?= e(safe_url($app['link'])) ?>" rel="noopener">تحميل / فتح الرابط الرسمي</a>
</div></div></body></html>
