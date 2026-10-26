<?php
// 启动会话，判断用户是否登录
session_start();
// 引入数据库连接文件
include "db_conn.php";

// 用户未登录，直接返回失败
if(!isset($_SESSION['user'])){
  echo json_encode(['ok'=>0]);
  exit;
}

// 获取当前登录用户名
$user = $_SESSION['user']['username'];
// 获取动作类型（add=添加记录）
$act = $_GET['act'];

// =====================
// 功能：添加浏览记录
// =====================
if($act == 'add'){
  // 获取商品编号
  $code = $_GET['code'];
  
  // 所有商品表
  $tables = ['clothes','shoes_bags','fruits','daily_goods'];
  $goods = null;

  // 循环查询商品属于哪个表
  foreach($tables as $t){
    $r = mysqli_query($conn,"SELECT * FROM $t WHERE code='$code'");
    if(mysqli_num_rows($r)>0){
      $goods = mysqli_fetch_array($r);
      break;
    }
  }
  
  // 找到商品 → 写入浏览记录
  if($goods){
    $name = $goods['name'];     // 商品名
    $price = $goods['price'];   // 价格
    $type = $t;                 // 商品类型
    
    // 去重：先删除旧的浏览记录
    mysqli_query($conn,"DELETE FROM user_browse_history WHERE username='$user' AND goods_code='$code'");
    
    // 插入新的浏览记录
    mysqli_query($conn,"INSERT INTO user_browse_history (username,goods_code,goods_name,goods_price,goods_type)
      VALUES ('$user','$code','$name','$price','$type')");
  }
  
  // 返回成功
  echo json_encode(['ok'=>1]);
  exit;
}
?>