<?php
// ==========================================================
// 数据库配置模板
//
// 使用方法：复制为 config.php 并填入自己的账号密码
//   cp config.example.php config.php
//
// 不创建 config.php 时，程序会使用 db_conn.php / db_info.php
// 里的默认值（localhost:3307，用户 root / 123456），适合本地快速调试。
//
// config.php 已被 .gitignore 排除，不会提交到仓库。
// ==========================================================
return [
    'host'      => '127.0.0.1',
    'port'      => 3306,
    'info_user' => 'root',
    'info_pass' => 'your_password',
    'shop_user' => 'root',
    'shop_pass' => 'your_password',
];
