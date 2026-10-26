<?php
session_start();
include "db_conn.php";

// 未登录直接返回
if(!isset($_SESSION['user'])){
  echo json_encode(['ok'=>0,'msg'=>'请先登录']);exit;
}

// 当前登录用户
$user = $_SESSION['user']['username'];

// 获取动作：GET/POST
$act = $_POST['act'] ?? $_GET['act'];

// =====================
// 功能：检查商品是否已收藏
// =====================
if($act == 'check'){
  $code = $_GET['code'];
  $r = mysqli_query($conn,"SELECT * FROM user_favorites WHERE username='$user' AND goods_code='$code'");
  echo json_encode(['favorited'=>mysqli_num_rows($r)>0]);exit;
}

// =====================
// 功能：切换收藏/取消收藏
// =====================
if($act == 'toggle'){
  $code = $_POST['code'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  
  // 查询是否已收藏
  $r = mysqli_query($conn,"SELECT * FROM user_favorites WHERE username='$user' AND goods_code='$code'");
  if(mysqli_num_rows($r)>0){
    // 已收藏 → 删除
    mysqli_query($conn,"DELETE FROM user_favorites WHERE username='$user' AND goods_code='$code'");
    echo json_encode(['ok'=>1,'msg'=>'已取消收藏','action'=>'remove']);exit;
  }else{
    // 未收藏 → 添加
    
    // 自动判断商品表
    $tables = ['clothes','shoes_bags','fruits','daily_goods'];
    $type = '';
    foreach($tables as $t){
      $c = mysqli_query($conn,"SELECT 1 FROM $t WHERE code='$code'");
      if(mysqli_num_rows($c)>0){ $type=$t; break; }
    }

    // 插入收藏
    mysqli_query($conn,"INSERT INTO user_favorites (username,goods_code,goods_name,goods_price,goods_type) VALUES ('$user','$code','$name','$price','$type')");
    echo json_encode(['ok'=>1,'msg'=>'收藏成功','action'=>'add']);exit;
  }
}

// =====================
// 功能：个人中心删除收藏
// =====================
if($act == 'delete'){
  $id = $_GET['id'];
  $del = mysqli_query($conn,"DELETE FROM user_favorites WHERE id='$id' AND username='$user'");
  echo json_encode(['ok' => $del ? 1 : 0]);
  exit;
}
?>