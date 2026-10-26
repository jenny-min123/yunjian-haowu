<?php
session_start();
if (!isset($_SESSION['seller'])) {
    header("Location: login.php");
    exit;
}

include "db_conn.php";
$seller = $_SESSION['seller'];
// 注意：商品表 / 订单表的 seller 字段存的是【店铺名】，不是登录账号
// 所以这里必须取 seller_name；username 只是登录用的账号名
$username = $_SESSION['seller']['seller_name'] ?? $_SESSION['seller']['username'] ?? '';
$usernameEsc = mysqli_real_escape_string($conn, $username);

// ==========================================================
// 安全处理
// 1) 表名白名单：$table 全部来自 GET/POST，绝不允许直接拼进 SQL
// 2) 商品编号白名单：同一份 $code 还会拼成图片文件名，防目录穿越
// 3) 所有字符串参数统一 mysqli_real_escape_string 转义
// ==========================================================
$ALLOW_TABLES = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];

function pick_table($name, $allow)
{
    return in_array($name, $allow, true) ? $name : null;
}

function safe_code($code)
{
    return preg_match('/^[A-Za-z0-9_\-]{1,32}$/', (string)$code) ? (string)$code : null;
}

// 编辑商品：获取要修改的数据
$editGoods = null;
if (isset($_GET['edit'])) {
    $table = pick_table($_GET['table'] ?? '', $ALLOW_TABLES);
    $code  = safe_code($_GET['code'] ?? '');
    if ($table === null || $code === null) {
        header("Location: sellerindex.php");
        exit;
    }
    $codeEsc = mysqli_real_escape_string($conn, $code);
    $res = mysqli_query($conn, "SELECT * FROM `$table` WHERE code='$codeEsc' AND seller='$usernameEsc'");
    $editGoods = mysqli_fetch_array($res);
    if($editGoods){
        $editGoods['_table'] = $table; // 关键修复：把分类表名带上
    }
}

// ==========================
// 实时刷新接口
// ==========================
if (isset($_GET['refresh'])) {
    // 今日订单
    $todayOrder = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE seller='$usernameEsc' AND DATE(create_time)=CURDATE()"));
    // 今日收入
    $todayMoney = mysqli_fetch_array(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS mon FROM orders WHERE seller='$usernameEsc' AND DATE(create_time)=CURDATE()"));
    // 总订单
    $totalOrder = mysqli_fetch_array(mysqli_query($conn, "SELECT sum(qty) AS cnt FROM orders WHERE seller='$usernameEsc'"));
    // 总收入
    $totalMoney = mysqli_fetch_array(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS mon FROM orders WHERE seller='$usernameEsc'"));

    echo json_encode([
        'todayOrder' => $todayOrder['cnt'],
        'todayMoney' => $todayMoney['mon'],
        'totalOrder' => $totalOrder['cnt'],
        'totalMoney' => $totalMoney['mon']
    ]);
    exit;
}

// ==========================
// 初始加载数据
// ==========================
// 今日订单
$todayOrder = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE seller='$usernameEsc' AND DATE(create_time)=CURDATE()"))['cnt'];
// 今日收入
$todayMoney = mysqli_fetch_array(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS mon FROM orders WHERE seller='$usernameEsc' AND DATE(create_time)=CURDATE()"))['mon'];
// 总订单
$totalOrder = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE seller='$usernameEsc'"))['cnt'];
// 总收入
$totalMoney = mysqli_fetch_array(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS mon FROM orders WHERE seller='$usernameEsc'"))['mon'];

// ==========================
// 修改商品
// ==========================
if (isset($_POST['editGoods'])) {
    $table = pick_table($_POST['type'] ?? '', $ALLOW_TABLES);
    $code  = safe_code($_POST['code'] ?? '');
    if ($table === null || $code === null) {
        echo "<script>alert('参数不合法');history.back();</script>";
        exit;
    }

    // 统一转义，避免 SQL 注入
    $code                = mysqli_real_escape_string($conn, $code);
    $name                = mysqli_real_escape_string($conn, $_POST['name']);
    $price               = mysqli_real_escape_string($conn, $_POST['price']);
    $description         = mysqli_real_escape_string($conn, $_POST['description']);
    $coupon_info         = mysqli_real_escape_string($conn, $_POST['coupon_info']);
    $service             = mysqli_real_escape_string($conn, $_POST['service']);
    $return_free_shipping = mysqli_real_escape_string($conn, $_POST['return_free_shipping']);

    $sql = "UPDATE `$table` SET
            name='$name',
            price='$price',
            description='$description',
            coupon_info='$coupon_info',
            service='$service',
            return_free_shipping='$return_free_shipping'
            WHERE code='$code' AND seller='$usernameEsc'";

    mysqli_query($conn, $sql);

    // 如果更换图片
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $allow = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allow)) {
            $fileName = $code . '.jpg';
            $path = 'img/' . $fileName;
            // 修复：原写法 $_FILES['tmp_name'] 少了 ['img']，导致换图从来没成功过
            move_uploaded_file($_FILES['img']['tmp_name'], $path);
        }
    }

    header("Location: sellerindex.php");
    exit;
}

// ==========================
// 添加商品
// ==========================
if (isset($_POST['addGoods'])) {
    $table = pick_table($_POST['type'] ?? '', $ALLOW_TABLES);
    $code  = safe_code($_POST['code'] ?? '');
    if ($table === null || $code === null) {
        echo "<script>alert('参数不合法（分类或商品编号）');history.back();</script>";
        exit;
    }

    if (!isset($_FILES['img']) || $_FILES['img']['error'] != 0) {
        echo "<script>alert('请上传商品图片');history.back();</script>";
        exit;
    }

    $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
    $allow = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($ext), $allow)) {
        echo "<script>alert('只允许上传图片');history.back();</script>";
        exit;
    }

    // 统一转义，避免 SQL 注入
    $code                = mysqli_real_escape_string($conn, $code);
    $name                = mysqli_real_escape_string($conn, $_POST['name']);
    $price               = mysqli_real_escape_string($conn, $_POST['price']);
    $description         = mysqli_real_escape_string($conn, $_POST['description']);
    $coupon_info         = mysqli_real_escape_string($conn, $_POST['coupon_info']);
    $service             = mysqli_real_escape_string($conn, $_POST['service']);
    $return_free_shipping = mysqli_real_escape_string($conn, $_POST['return_free_shipping']);

    $fileName = $code . '.jpg';
    $path = 'img/' . $fileName;
    move_uploaded_file($_FILES['img']['tmp_name'], $path);

    $sql = "INSERT INTO `$table`
           (code, name, price, description, type, seller, coupon_info, service, return_free_shipping)
           VALUES ('$code','$name','$price','$description','$table','$usernameEsc','$coupon_info','$service','$return_free_shipping')";

    mysqli_query($conn, $sql);
    header("Location: sellerindex.php");
    exit;
}

// ==========================
// 删除商品
// ==========================
if (isset($_GET['del'])) {
    $table = pick_table($_GET['table'] ?? '', $ALLOW_TABLES);
    $code  = safe_code($_GET['code'] ?? '');
    if ($table !== null && $code !== null) {
        $codeEsc = mysqli_real_escape_string($conn, $code);
        mysqli_query($conn, "DELETE FROM `$table` WHERE code='$codeEsc' AND seller='$usernameEsc'");
        $imgPath = 'img/' . $code . '.jpg';
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    header("Location: sellerindex.php");
    exit;
}

// ==========================
// 获取商品
// ==========================
$myGoods = [];
$tables = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];
foreach ($tables as $t) {
    $res = mysqli_query($conn, "SELECT * FROM $t WHERE seller='$usernameEsc'");
    while ($row = mysqli_fetch_array($res)) {
        $row['_table'] = $t;
        $myGoods[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>商家管理中心</title>
    <link rel="stylesheet" href="css/sellerindex.css">
</head>

<script>
// 实时刷新 4 个数据
function loadStats() {
    fetch("sellerindex.php?refresh=1")
    .then(res => res.json())
    .then(data => {
        document.getElementById("todayOrder").innerText = data.todayOrder;
        document.getElementById("todayMoney").innerText = data.todayMoney;
        document.getElementById("totalOrder").innerText = data.totalOrder;
        document.getElementById("totalMoney").innerText = data.totalMoney;
    });
}
setInterval(loadStats, 1000);
</script>

<body>
<div class="container">
    <div class="header">
        <h2>商家管理中心 - <?= $username ?></h2>
        <div>
            <button class="shop-btn" onclick="window.open('sellerdetail.php?name=<?= $username ?>')">进入店铺主页</button>
            <a href="?logout=1"><button>退出登录</button></a>
        </div>
    </div>

    <!-- 数据面板：今日订单、今日收入、总订单、总收入 -->
    <div class="stat">
        <div class="box">
            <h3 id="todayOrder"><?= $todayOrder ?></h3>
            <p>今日订单数</p>
        </div>
        <div class="box">
            <h3>¥<span id="todayMoney"><?= $todayMoney ?></span></h3>
            <p>今日收入</p>
        </div>
        <div class="box">
            <h3 id="totalOrder"><?= $totalOrder ?></h3>
            <p>总订单数</p>
        </div>
        <div class="box">
            <h3>¥<span id="totalMoney"><?= $totalMoney ?></span></h3>
            <p>总收入</p>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <?php if ($editGoods) { ?>
                <h3>修改商品</h3>
            <?php } else { ?>
                <h3>添加商品（必须上传图片）</h3>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <?php if ($editGoods) { ?>
                    <input type="hidden" name="type" value="<?= $editGoods['_table'] ?>">
                    <input type="hidden" name="code" value="<?= $editGoods['code'] ?>">
                <?php } else { ?>
                    <label>商品分类</label>
                    <select name="type" required>
                        <option value="clothes">服饰</option>
                        <option value="shoes_bags">鞋包</option>
                        <option value="fruits">水果</option>
                        <option value="daily_goods">百货</option>
                    </select><br>

                    <label>商品编码</label>
                    <input name="code" required><br>
                <?php } ?>

                <label>商品名称</label>
                <input name="name" value="<?= $editGoods ? $editGoods['name'] : '' ?>" required><br>

                <label>价格</label>
                <input name="price" value="<?= $editGoods ? $editGoods['price'] : '' ?>" required><br>

                <label>商品描述</label>
                <textarea name="description"><?= $editGoods ? $editGoods['description'] : '' ?></textarea><br>

                <label>优惠券</label>
                <input name="coupon_info" value="<?= $editGoods ? $editGoods['coupon_info'] : '无优惠券' ?>"><br>

                <label>服务</label>
                <input name="service" value="<?= $editGoods ? $editGoods['service'] : '7天无理由退换' ?>"><br>

                <label>退货包邮</label>
                <select name="return_free_shipping">
                    <option value="1" <?= $editGoods && $editGoods['return_free_shipping'] == 1 ? 'selected' : '' ?>>是</option>
                    <option value="0" <?= $editGoods && $editGoods['return_free_shipping'] == 0 ? 'selected' : '' ?>>否</option>
                </select><br>

                <label>商品图片</label>
                <input type="file" name="img"><br>

                <?php if ($editGoods) { ?>
                    <button type="submit" name="editGoods">保存修改</button>
                <?php } else { ?>
                    <button type="submit" name="addGoods">发布商品</button>
                <?php } ?>
            </form>
        </div>

        <div class="card">
            <h3>我的商品（实时显示）</h3>
            <table>
                <tr><th>分类</th><th>编码</th><th>名称</th><th>价格</th><th>操作</th></tr>
                <?php foreach($myGoods as $g){ ?>
                <tr>
                    <td><?= $g['_table'] ?></td>
                    <td><?= $g['code'] ?></td>
                    <td><?= $g['name'] ?></td>
                    <td>¥<?= $g['price'] ?></td>
                    <td>
                        <!-- 修改按钮 -->
                        <a class="edit" href="?edit=1&table=<?= $g['_table'] ?>&code=<?= $g['code'] ?>">修改</a>
                        |
                        <a class="del" href="?del=1&table=<?= $g['_table'] ?>&code=<?= $g['code'] ?>">删除</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
// 退出登录
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>