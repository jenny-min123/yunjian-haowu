<?php
// 开启会话，保存登录状态
session_start();
if(file_exists("visit.txt")){
    // 只读模式打开计数文件
    $file = fopen("visit.txt", "r");
    // 读取访问数量并转为整型
    $visit = (int)fgets($file);
    // 关闭文件释放占用
    fclose($file);
    // 访问量自增1
    $visit++;
    // 写入模式打开文件，覆盖旧内容
    $file = fopen("visit.txt", "w");
    // 写入最新访问数
    fwrite($file, $visit);
    // 关闭文件
    fclose($file);
}
else{
    // 文件不存在，初始化访问数为1
    $file = fopen("visit.txt", "w");
    fwrite($file, 1);
    fclose($file);
    $visit = 1;
}

if (isset($_COOKIE['user_auto']) && !isset($_SESSION['user'])) {
    // 解析cookie中的用户信息
    $user = json_decode($_COOKIE['user_auto'], true);
    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    }
}


include 'db_info.php';
$error = "";
$success = "";

// 获取错误提示并清空session，防止重复弹出
if (isset($_SESSION['msg_error'])) {
    $error = $_SESSION['msg_error'];
    unset($_SESSION['msg_error']);
}
// 获取成功提示并清空session
if (isset($_SESSION['msg_success'])) {
    $success = $_SESSION['msg_success'];
    unset($_SESSION['msg_success']);
}


if (isset($_POST['login'])) {
    // 获取表单提交数据
    $role = $_POST['role'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    // 账号密码非空校验
    if (empty($username) || empty($password)) {
        $_SESSION['msg_error'] = "账号密码不能为空";
        header("Location: login.php");
        exit;
    }

    // ========== 普通用户登录 ==========
    if ($role === 'user') {
        // 预处理SQL防止注入
        $stmt = $conn->prepare("SELECT id,username,password FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        // 验证账号和密码
        if ($row && password_verify($password, $row['password'])) {
            unset($row['password']);   // 密码哈希不写进 session / cookie
            $_SESSION['user'] = $row;
            // 记住密码，设置5天有效期cookie
            if ($remember) {
                setcookie("user_auto", json_encode($row), time() + 86400 * 5, "/");
            }
            // 弹窗提示 + 确定后跳转 index.php 【修复强制跳转】
            echo <<<JS
            <script>
                alert("欢迎你：{$username}\\n你是本站第 {$visit} 位访问者");
                window.location.href="index.php";
            </script>
JS;
            exit();
        }
    }

    // ========== 商家登录 ==========
    // 用「登录账号」匹配（username），店铺名 seller_name 只用于页面展示
    if ($role === 'seller') {
        $stmt = $conn->prepare("SELECT id, username, seller_name, password FROM seller WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        if ($row && !empty($row['password']) && password_verify($password, $row['password'])) {
            unset($row['password']);   // 密码哈希不写进 session
            $_SESSION['seller'] = $row;
            $shop = $row['seller_name'];
            // 弹窗提示 + 确定后跳转 sellerindex.php
            echo <<<JS
            <script>
                alert("欢迎商家：{$shop}\\n你是本站第 {$visit} 位访问者");
                window.location.href="sellerindex.php";
            </script>
JS;
            exit();
        }
    }

    // 登录失败统一提示
    $_SESSION['msg_error'] = "账号或密码错误";
    header("Location: login.php");
    exit;
}


if (isset($_POST['register'])) {
    $role = $_POST['role'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $seller_name = trim($_POST['seller_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // 基础必填项校验
    if (empty($username) || empty($password) || empty($email)) {
        $_SESSION['msg_error'] = "账号、密码、邮箱不能为空";
        header("Location: login.php#reg");
        exit;
    }

    // 密码加密存储
    $pwdHash = password_hash($password, PASSWORD_DEFAULT);

    // 普通用户注册
    if ($role == 'user') {
        $stmt = $conn->prepare("INSERT INTO user (username,password,email) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $pwdHash, $email);
        if ($stmt->execute()) {
            $_SESSION['msg_success'] = "用户注册成功！请登录";
        } else {
            $_SESSION['msg_error'] = "注册失败：账号或邮箱已存在";
        }
        header("Location: login.php#login");
        exit;
    }

    // 商家注册
    // 店铺名(seller_name) 只做展示；登录用 账号名(username) + 密码(password)
    if ($role == 'seller') {
        if (empty($seller_name) || empty($phone) || empty($address)) {
            $_SESSION['msg_error'] = "店铺信息必须完整";
            header("Location: login.php#reg");
            exit;
        }

        // 登录账号唯一性预检（给明确提示，而不是靠唯一索引报错）
        $chk = $conn->prepare("SELECT id FROM seller WHERE username=? LIMIT 1");
        $chk->bind_param("s", $username);
        $chk->execute();
        if ($chk->get_result()->fetch_assoc()) {
            $_SESSION['msg_error'] = "注册失败：登录账号「{$username}」已被占用，请换一个";
            header("Location: login.php#reg");
            exit;
        }

        // 店铺名唯一性预检
        $chk = $conn->prepare("SELECT id FROM seller WHERE seller_name=? LIMIT 1");
        $chk->bind_param("s", $seller_name);
        $chk->execute();
        if ($chk->get_result()->fetch_assoc()) {
            $_SESSION['msg_error'] = "注册失败：店铺名称「{$seller_name}」已存在";
            header("Location: login.php#reg");
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO seller (username, password, email, seller_name, address, phone) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $username, $pwdHash, $email, $seller_name, $address, $phone);
        if ($stmt->execute()) {
            $_SESSION['msg_success'] = "商家注册成功！请用登录账号「{$username}」登录";
        } else {
            $_SESSION['msg_error'] = "注册失败：账号或店铺名称已存在";
        }
        header("Location: login.php#login");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>登录注册</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="box">
    <!-- 登录/注册切换标签 -->
    <div class="tab">
        <button class="tab-btn active" data-target="login">登录</button>
        <button class="tab-btn" data-target="reg">注册</button>
    </div>

    <!-- 错误提示展示 -->
    <?php if($error): ?>
        <div class="msg error"><?=$error?></div>
    <?php endif; ?>
    <!-- 成功提示展示 -->
    <?php if($success): ?>
        <div class="msg success"><?=$success?></div>
    <?php endif; ?>

    <!-- 登录表单区域 -->
    <div id="login" class="form active">
        <form method="post">
            <select name="role" required>
                <option value="user">普通用户</option>
                <option value="seller">商家</option>
            </select>
            <input type="text" name="username" placeholder="账号" required>
            <input type="password" name="password" placeholder="密码" required>
            <div class="remember">
                <input type="checkbox" name="remember">
                <label>记住密码（仅用户）</label>
            </div>
            <div style="font-size:12px;color:#999;margin:-4px 0 10px;line-height:1.5;">
                商家登录请填注册时设置的<strong>登录账号</strong>，不是店铺名称
            </div>
            <button class="btn" name="login">登录</button>
        </form>
    </div>

    <!-- 注册表单区域 -->
    <div id="reg" class="form">
        <form method="post">
            <select name="role" id="role">
                <option value="user">普通用户</option>
                <option value="seller">商家</option>
            </select>
            <div id="shop_info" style="display:none">
                <input type="text" name="seller_name" placeholder="店铺名称（必填，仅页面展示）">
                <input type="text" name="phone" placeholder="联系电话（必填）">
                <input type="text" name="address" placeholder="店铺地址（必填）">
            </div>
            <input type="text" name="username" placeholder="登录账号（必填，登录用这个）" required>
            <input type="email" name="email" placeholder="邮箱（必填）" required>
            <input type="password" name="password" placeholder="密码（必填）" required>
            <button class="btn" name="register">注册</button>
        </form>
    </div>
</div>
<script src="js/login.js"></script>
</body>
</html>