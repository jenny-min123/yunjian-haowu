<?php
session_start();
include "db_conn.php";

// 当前登录用户
$user = $_SESSION['user']['username'];
// 动作：check/add/del
$act = $_GET['act'];

// =====================
// 功能：检查是否已关注店铺
// =====================
if($act == 'check'){
  $seller = $_GET['seller'];
  $res = mysqli_query($conn,"SELECT * FROM user_follow_shops WHERE username='$user' AND seller_name='$seller'");
  echo json_encode(['followed' => mysqli_num_rows($res)>0]);
  exit;
}

// =====================
// 功能：关注店铺
// =====================
if($act == 'add'){
  $seller = $_GET['seller'];
  // INSERT IGNORE 重复关注不会报错
  mysqli_query($conn,"INSERT IGNORE INTO user_follow_shops (username,seller_name) VALUES ('$user','$seller')");
  echo json_encode(['ok'=>1]);
  exit;
}

// =====================
// 功能：取消关注店铺
// =====================
if($act == 'del'){
  $seller = $_GET['seller'];
  $sql = "DELETE FROM user_follow_shops WHERE username='$user' AND seller_name='$seller'";
  mysqli_query($conn,$sql);
  echo json_encode(['ok'=>1]);
  exit;
}
?>