# متجر التطبيقات (PHP)

نشر على Render:
1. ارفع المجلد إلى مستودع GitHub.
2. Render ← New + ← Web Service ← اختر المستودع ← Language: Docker ← Free.
3. Environment Variables:
   - `ADMIN_PASSWORD` = كلمة سر قوية (إجباري)
   - `SITE_TITLE` = اسم الموقع (اختياري)
4. الإدارة: `/admin.php`

ملاحظة: على الخطة المجانية تُمسح تعديلات `data/apps.json` عند كل Redeploy.
