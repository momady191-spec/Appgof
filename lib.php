<?php
session_start();
const DATA_FILE = __DIR__ . '/data/apps.json';

function load_apps() {
    $j = @file_get_contents(DATA_FILE);
    $a = json_decode($j ?: '[]', true);
    return is_array($a) ? $a : [];
}
function save_apps($apps) {
    return file_put_contents(DATA_FILE, json_encode(array_values($apps), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf() {
    if (empty($_SESSION['t'])) $_SESSION['t'] = bin2hex(random_bytes(16));
    return $_SESSION['t'];
}
function check_csrf() {
    return isset($_POST['t']) && hash_equals($_SESSION['t'] ?? '', $_POST['t']);
}
function safe_url($u) {
    $u = trim((string)$u);
    return preg_match('#^https?://#i', $u) ? $u : '#';
}
function site_title() { return getenv('SITE_TITLE') ?: 'متجر التطبيقات'; }
