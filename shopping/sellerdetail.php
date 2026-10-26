<?php
session_start();
if (!isset($_SESSION['seller'])) {
    header("Location: login.php");
    exit;
}
include "db_conn.php";
$seller = $_SESSION['seller'];
// 商品/订单表的 seller 字段存的是店铺名，不是登录账号
$username = $_SESSION['seller']['seller_name'] ?? $_SESSION['seller']['username'] ?? '';

// 获取店铺所有商品
$tables = ['clothes','shoes_bags','fruits','daily_goods'];
$allGoods = [];
foreach($tables as $t){
    $res = mysqli_query($conn, "SELECT * FROM $t WHERE seller='$username'");
    while($row = mysqli_fetch_array($res)){
        $row['_table'] = $t;
        $allGoods[] = $row;
    }
}

// 店铺统计
$today = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE seller='$username' AND DATE(create_time)=CURDATE()"))['cnt'];
$total = mysqli_fetch_array(mysqli_query($conn, "SELECT sum(qty) AS cnt FROM orders WHERE seller='$username'"))['cnt'];
$money = mysqli_fetch_array(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS mon FROM orders WHERE seller='$username'"))['mon'];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>我的店铺主页 - 商家版</title>
    <link rel="stylesheet" href="css/sellerdetail.css">
</head>

<body>
<div class="container">
    <div class="shop-header">
        <h2>我的店铺主页</h2>
        <p>店铺名称：<?= htmlspecialchars($username) ?> | 仅商家本人可见</p>
        <a href="sellerindex.php" class="back">← 进入商家后台</a>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3><?= $today ?></h3>
            <p>今日订单</p>
        </div>
        <div class="stat-box">
            <h3><?= $total ?></h3>
            <p>总订单数</p>
        </div>
        <div class="stat-box">
            <h3>¥<?= $money ?></h3>
            <p>店铺总收入</p>
        </div>
    </div>

    <div class="goods-box">
        <h3>📦 店铺全部商品（商家预览）</h3>
        <div class="goods-grid">
            <?php foreach($allGoods as $g){ ?>
            <div class="goods-item">
                <img src="img/<?= $g['code'] ?>.jpg" alt="">
                <div class="goods-info">
                    <h4><?= $g['name'] ?></h4>
                    <div class="price">¥<?= $g['price'] ?></div>
                    <div class="type">分类：<?= $g['_table'] ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="goods-box" style="margin-top:20px">
        <h3>📋 商品管理列表（快捷查看）</h3>
        <table>
            <tr>
                <th>分类</th>
                <th>商品编码</th>
                <th>商品名称</th>
                <th>价格</th>
                <th>优惠券</th>
            </tr>
            <?php foreach($allGoods as $g){ ?>
            <tr>
                <td><?= $g['_table'] ?></td>
                <td><?= $g['code'] ?></td>
                <td><?= $g['name'] ?></td>
                <td>¥<?= $g['price'] ?></td>
                <td><?= $g['coupon_info'] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>