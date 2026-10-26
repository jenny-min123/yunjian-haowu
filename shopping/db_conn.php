<?php
// ==========================================================
// 数据库连接 —— 商品库 product_db
// 本地开发：不存在 config.php 时，走下面的默认值（3307 / tjm）
// 线上部署：放一个 config.php 即可覆盖，无需改动本文件
// ==========================================================
$__cfg = is_file(__DIR__ . '/config.php') ? require __DIR__ . '/config.php' : [];

$db_host = $__cfg['host'] ?? 'localhost';
$db_port = $__cfg['port'] ?? 3307;
$db_user = $__cfg['shop_user'] ?? 'tjm';
$db_pass = $__cfg['shop_pass'] ?? '123456';

// PHP 8.1 起 mysqli 默认在出错时抛异常，会让原有的 if/else 判断全部失效
// （典型症状：注册邮箱重复直接 500，而不是提示"已存在"）
// 这里关掉异常模式，恢复成"返回 false"的老行为
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($db_host, $db_user, $db_pass, 'product_db', $db_port);
if (!$conn) {
    die("数据库连接失败");
}
mysqli_set_charset($conn, "utf8");
