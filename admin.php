<?php
require __DIR__ . '/lib.php';

$pass = getenv('ADMIN_PASSWORD');
$msg = '';

if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }

if (!$pass) {
    echo '<p dir="rtl" style="font-family:sans-serif;padding:24px">حدد المتغير ADMIN_PASSWORD في إعدادات Render أولا.</p>';
    exit;
}

if (isset($_POST['login'])) {
    if (check_csrf() && hash_equals($pass, (string)($_POST['password'] ?? ''))) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
    } else {
        $msg = 'كلمة السر غير صحيحة';
    }
}
$in = !empty($_SESSION['admin']);

if ($in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act'])) {
    if (!check_csrf()) { http_response_code(400); exit('CSRF'); }
    $apps = load_apps();
    if ($_POST['act'] === 'delete') {
        $apps = array_filter($apps, fn($a) => (int)$a['id'] !== (int)$_POST['id']);
        $msg = 'تم الحذف';
    } elseif ($_POST['act'] === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $row = [
            'id' => $id,
            'name' => trim($_POST['name'] ?? ''),
            'category' => ($_POST['category'] ?? '') === 'ألعاب' ? 'ألعاب' : 'تطبيقات',
            'icon' => trim($_POST['icon'] ?? '📱'),
            'rating' => max(0, min(5, (float)($_POST['rating'] ?? 4))),
            'description' => trim($_POST['description'] ?? ''),
            'link' => safe_url($_POST['link'] ?? ''),
        ];
        if ($row['name'] === '') { $msg = 'الاسم مطلوب'; }
        else {
            if ($id) {
                foreach ($apps as &$a) if ((int)$a['id'] === $id) $a = $row;
                unset($a);
            } else {
                $row['id'] = 1 + max(array_merge([0], array_column($apps, 'id')));
                $apps[] = $row;
            }
            $msg = 'تم الحفظ';
        }
    }
    if (!save_apps($apps)) $msg = 'فشل الحفظ: تحقق من صلاحيات مجلد data';
}
$apps = $in ? load_apps() : [];
$edit = null;
if ($in && isset($_GET['edit'])) foreach ($apps as $a) if ((int)$a['id'] === (int)$_GET['edit']) $edit = $a;
?><!doctype html>
<html lang="ar" dir="rtl"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>الإدارة</title>
<style>
body{margin:0;font-family:system-ui,Tahoma,sans-serif;background:#0f1220;color:#eef0fa}
.wrap{max-width:760px;margin:0 auto;padding:20px 16px}
input,select,textarea{width:100%;padding:10px;margin:4px 0 10px;border-radius:8px;border:1px solid #2a3050;background:#181c30;color:#eef0fa}
button,.b{padding:8px 14px;border:0;border-radius:8px;background:#5b8cff;color:#fff;cursor:pointer;text-decoration:none;font-size:14px}
.r{background:#b33}
table{width:100%;border-collapse:collapse}td,th{padding:8px;border-bottom:1px solid #2a3050;text-align:right}
.m{background:#243;padding:8px 12px;border-radius:8px;margin-bottom:12px}
</style></head><body><div class="wrap">
<?php if ($msg): ?><div class="m"><?= e($msg) ?></div><?php endif; ?>
<?php if (!$in): ?>
<h2>دخول الإدارة</h2>
<form method="post">
<input type="hidden" name="t" value="<?= e(csrf()) ?>">
<input type="password" name="password" placeholder="كلمة السر" required>
<button name="login" value="1">دخول</button>
</form>
<?php else: ?>
<p><a class="b" href="/">الموقع</a> <a class="b r" href="?logout=1">خروج</a></p>
<h2><?= $edit ? 'تعديل' : 'إضافة' ?> تطبيق</h2>
<form method="post">
<input type="hidden" name="t" value="<?= e(csrf()) ?>">
<input type="hidden" name="act" value="save">
<input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
<input name="name" placeholder="الاسم" value="<?= e($edit['name'] ?? '') ?>" required>
<select name="category"><?php foreach (['تطبيقات', 'ألعاب'] as $c): ?><option <?= ($edit['category'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option><?php endforeach; ?></select>
<input name="icon" placeholder="أيقونة (إيموجي)" value="<?= e($edit['icon'] ?? '📱') ?>">
<input name="rating" type="number" step="0.1" min="0" max="5" value="<?= e($edit['rating'] ?? 4.5) ?>">
<textarea name="description" rows="3" placeholder="الوصف"><?= e($edit['description'] ?? '') ?></textarea>
<input name="link" placeholder="الرابط الرسمي (https://...)" value="<?= e($edit['link'] ?? '') ?>">
<button>حفظ</button>
</form>
<h2>القائمة</h2>
<table>
<?php foreach ($apps as $a): ?>
<tr><td><?= e($a['icon']) ?> <?= e($a['name']) ?></td><td><?= e($a['category']) ?></td>
<td><a class="b" href="?edit=<?= (int)$a['id'] ?>">تعديل</a></td>
<td><form method="post" onsubmit="return confirm('حذف؟')" style="margin:0">
<input type="hidden" name="t" value="<?= e(csrf()) ?>"><input type="hidden" name="act" value="delete"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
<button class="r">حذف</button></form></td></tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div></body></html>
