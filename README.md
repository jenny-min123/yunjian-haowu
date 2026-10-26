# 云间好物 · 汉服电商平台

一个基于原生 PHP + MySQL 的中文服饰电商网站，包含**用户**与**商家**双角色，覆盖从浏览、收藏、下单到商家商品管理的完整流程。

不使用任何框架，适合作为 PHP 与数据库课程设计的参考实现。

---

## 功能

**用户端**

- 注册 / 登录（bcrypt 密码存储，会话隔离）
- 商品浏览，按服饰、鞋包、水果生鲜、日用百货分类
- 关键词搜索、商品详情
- 收藏商品、关注店铺、浏览记录
- 购物车与下单（订单落库）
- 个人资料修改、头像上传

**商家端**

- 商家注册 / 登录（店铺名称与登录账号分离）
- 店铺主页与商家详情页
- 商品增删改查
- 商品图片上传与更换

---

## 技术栈

| 层 | 选型 |
|---|---|
| 后端 | PHP 8.x（兼容 7.3+），原生 mysqli，无框架 |
| 数据库 | MySQL 8.x / 5.7（两库：`info` 用户库、`product_db` 商品库） |
| 前端 | 原生 HTML / CSS / JavaScript |
| Web 服务器 | Nginx 或 Apache + PHP-FPM |

---

## 目录结构

```
.
├── shopping/                 # 站点根目录
│   ├── login.php             # 登录 / 注册（用户与商家双入口）
│   ├── index.php             # 用户首页（商品列表、搜索）
│   ├── seller.php            # 店铺主页
│   ├── sellerdetail.php      # 商家详情
│   ├── sellerindex.php       # 商家后台（商品管理）
│   ├── api_browse.php        # 浏览记录接口
│   ├── api_favorite.php      # 收藏接口
│   ├── api_follow.php        # 关注店铺接口
│   ├── db_info.php           # 用户库连接
│   ├── db_conn.php           # 商品库连接
│   ├── config.example.php    # 数据库配置模板
│   ├── css/  js/  img/       # 静态资源与商品图
│   └── uploads/              # 用户上传（头像等）
├── info.sql                  # 用户库：结构 + 脱敏演示数据
└── product_db.sql            # 商品库：结构 + 脱敏演示数据
```

---

## 快速开始

### 环境要求

- PHP >= 7.3（推荐 8.x），需启用 `mysqli`、`mbstring`、`gd` 扩展
- MySQL >= 5.7
- Nginx 或 Apache

### 1. 导入数据库

两个 SQL 文件都自带 `CREATE DATABASE IF NOT EXISTS`，直接执行即可：

```bash
mysql -u root -p < info.sql
mysql -u root -p < product_db.sql
```

> 导入时建议加 `--default-character-set=utf8mb4`，避免中文乱码。

### 2. 配置数据库连接

```bash
cd shopping
cp config.example.php config.php
```

然后编辑 `config.php`，填入自己的数据库账号：

```php
return [
    'host'      => '127.0.0.1',
    'port'      => 3306,
    'info_user' => 'root',
    'info_pass' => '你的密码',
    'shop_user' => 'root',
    'shop_pass' => '你的密码',
];
```

如果**不创建** `config.php`，程序会走代码里的默认值（`localhost:3307`，用户 `root` / 密码 `123456`），
适合本地快速调试。生产环境请务必用 `config.php` 覆盖。

### 3. 启动

最快的方式是用 PHP 内置服务器：

```bash
cd shopping
php -S 127.0.0.1:8000 -t .
```

打开 http://127.0.0.1:8000/login.php

用 Nginx 时，站点根目录直接指向 `shopping`，`index` 设为 `login.php`：

```nginx
server {
    listen 80;
    root /var/www/shopping;
    index login.php index.php;

    location / { try_files $uri $uri/ =404; }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php-fpm.sock;
    }
}
```

> 注意不要写成 `location = / { try_files /login.php =404; }`。`try_files` 命中 `.php`
> 文件时会由静态文件处理器直接返回，浏览器会变成"下载 PHP 源码"。用 `index` 指令或
> `rewrite ^ /login.php last;` 才会交给 PHP 执行。

### 4. 目录权限

以下两个目录需要 Web 进程可写：

- `shopping/img/` —— 商家上传的商品图
- `shopping/uploads/` —— 用户头像

```bash
chown -R www-data:www-data shopping
chmod -R 775 shopping/img shopping/uploads
```

---

## 演示账号

| 角色 | 账号 | 密码 |
|---|---|---|
| 普通用户 | `demo` | `123456` |
| 商家 | `云间汉服馆` | `123456` |

商家登录时，角色选「商家」，账号填**登录账号**（不是店铺名称）。

---

## 关于数据

仓库中的 SQL 已做脱敏处理：

- 商家手机号 → `13800000001` 起递增
- 用户邮箱 → `user1@example.com` 形式
- 密码哈希 → 统一替换为 `123456` 的 bcrypt 值，因此所有演示账号密码都是 `123456`

商品、店铺、订单等业务数据保持原样，导入后即可看到完整页面效果。

---

## 安全措施

- **密码存储**：`password_hash()` / `password_verify()`，不存明文
- **SQL 注入防护**：表名走白名单校验，所有外部输入经 `mysqli_real_escape_string()` 转义
- **上传防护**：扩展名白名单 + 文件名格式校验，阻断 `../` 路径穿越
- **凭据隔离**：数据库账号密码放在 `config.php`，已被 `.gitignore` 排除，不进版本库
- **会员隔离**：用户与商家分别使用 `$_SESSION['user']` 和 `$_SESSION['seller']`，互不串用

---

## 后续可改进

- `api_browse.php`、`api_favorite.php`、`api_follow.php` 中的查询仍为字符串拼接，
  可迁移到 `mysqli` 预处理语句
- 购物车目前基于前端存储，可改为服务端持久化
- 未引入自动化测试与前端构建工具

---

## License

MIT
