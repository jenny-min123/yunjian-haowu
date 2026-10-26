<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php");exit; }
include "db_conn.php";
$user = $_SESSION['user']['username'];

$nickname = $_POST['nickname'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];

mysqli_query($conn,"UPDATE user_profile SET 
  nickname='$nickname', gender='$gender', phone='$phone' 
  WHERE username='$user'
");

header("Location: user_center.php");
?>