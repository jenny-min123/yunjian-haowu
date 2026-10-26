<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php");exit; }
include "db_conn.php";
$user = $_SESSION['user']['username'];

// 统一删除收藏接口（修复：把删除逻辑写在本页，不再依赖外部api）
if (isset($_GET['act']) && $_GET['act'] === 'delete_fav' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = mysqli_query($conn, "DELETE FROM user_favorites WHERE id=$id AND username='$user'");
    echo json_encode(['ok' => $res ? 1 : 0]);
    exit;
}

// 头像上传处理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $dir = 'uploads/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $file = $_FILES['avatar'];
    if ($file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allow = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allow)) {
            $fileName = 'avatar_' . $user . '_' . time() . '.' . $ext;
            $savePath = $dir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $savePath)) {
                mysqli_query($conn, "UPDATE user_profile SET avatar='$savePath' WHERE username='$user'");
                echo json_encode(['ok' => 1, 'url' => $savePath]);
                exit;
            }
        }
    }
    echo json_encode(['ok' => 0, 'msg' => '上传失败']);
    exit;
}

// 加载用户资料
$profile = mysqli_query($conn,"SELECT * FROM user_profile WHERE username='$user'");
if(mysqli_num_rows($profile) == 0){
  mysqli_query($conn,"INSERT INTO user_profile (username,avatar) VALUES ('$user','public/face.png')");
  $profile = mysqli_query($conn,"SELECT * FROM user_profile WHERE username='$user'");
}
$me = mysqli_fetch_array($profile);

// 订单
$orders = mysqli_query($conn,"SELECT * FROM orders WHERE user_id='$user' ORDER BY create_time DESC LIMIT 20");

// 收藏
$favorites = mysqli_query($conn,"SELECT * FROM user_favorites WHERE username='$user' ORDER BY create_time DESC LIMIT 20");

// 浏览记录
$browses = mysqli_query($conn,"SELECT * FROM user_browse_history WHERE username='$user' ORDER BY browse_time DESC LIMIT 20");

// 关注店铺
$follows = mysqli_query($conn,"SELECT * FROM user_follow_shops WHERE username='$user' ORDER BY create_time DESC");
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/cloud.png">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/user_center.css">
  <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>个人中心 - 云间好物</title>
</head>
<body>

<nav>
  <div class="title">
    <div>
      <img src="public/cloud.png" alt="">
      <h1 class="topic">云间好物</h1>
    </div>
    <div>
      <a href="index.php">首页</a>|
      <a href="login.php">退出登录</a>
    </div>
  </div>
</nav>

<div class="user-center">
  <div class="user-header">
    <form id="avatarForm" enctype="multipart/form-data" style="margin:0;">
      <label for="avatarFile">
        <img src="<?= $me['avatar'] ?>" class="avatar" id="avatarPreview">
      </label>
      <input type="file" id="avatarFile" name="avatar" accept="image/*" style="display:none;">
    </form>

    <div class="user-info">
      <h2><?=$me['nickname'] ?: $user?></h2>
      <div>账号：<?=$user?></div>
      
      <button 
        onclick="document.getElementById('avatarFile').click()" 
        style="margin-top:8px; padding:6px 12px; background:#409eff; color:white; border:none; border-radius:6px; cursor:pointer;">
        <i class="fas fa-camera"></i> 上传头像
      </button>
    </div>
  </div>

  <div class="tab-bar">
    <div class="tab active" onclick="showPanel('orders')">我的订单</div>
    <div class="tab" onclick="showPanel('favorites')">我的收藏</div>
    <div class="tab" onclick="showPanel('browse')">浏览记录</div>
    <div class="tab" onclick="showPanel('follow')">关注店铺</div>
    <div class="tab" onclick="showPanel('profile')">个人设置</div>
  </div>

  <!-- 订单 -->
  <div class="panel" id="ordersPanel">
    <h3>最近订单</h3>
    <?php while($o=mysqli_fetch_array($orders)){ ?>
      <div class="order-item">
        <div><?=$o['name']?> × <?=$o['qty']?></div>
        <div>￥<?=$o['total_price']?> · <?=$o['create_time']?></div>
      </div>
    <?php } ?>
  </div>

  <!-- 收藏 -->
  <div class="panel" id="favoritesPanel" style="display:none;">
    <h3>我的收藏</h3>
    <div class="goods-list" id="favoriteList">
      <?php if(mysqli_num_rows($favorites) == 0){ ?>
        <div style="padding:15px;">暂无收藏</div>
      <?php } ?>
      <?php while($f=mysqli_fetch_array($favorites)){ ?>
        <div class="goods-item" data-id="<?=$f['id']?>">
          <img src="img/<?=$f['goods_code']?>.jpg">
          <div class="goods-info">
            <div class="goods-name"><?=$f['goods_name']?></div>
            <div class="goods-price">￥<?=$f['goods_price']?></div>
            <button onclick="deleteFavorite(<?=$f['id']?>, this)" 
            style="margin-top:8px;background:#ff4444;color:white;border:none;padding:4px 8px;border-radius:4px;font-size:12px;cursor:pointer;">
            删除收藏
          </button>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- 浏览记录 -->
  <div class="panel" id="browsePanel" style="display:none;">
    <h3>最近浏览</h3>
    <div class="goods-list">
      <?php while($b=mysqli_fetch_array($browses)){ ?>
        <div class="goods-item">
          <img src="img/<?=$b['goods_code']?>.jpg">
          <div class="goods-info">
            <div class="goods-name"><?=$b['goods_name']?></div>
            <div class="goods-price">￥<?=$b['goods_price']?></div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- 关注店铺 -->
  <div class="panel" id="followPanel" style="display:none;">
    <h3>关注的店铺</h3>
    <?php while($s=mysqli_fetch_array($follows)){ ?>
      <div style="padding:10px;border-bottom:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
        <span><?=$s['seller_name']?></span>
        <button onclick="unfollowShop('<?=addslashes($s['seller_name'])?>', this)" style="background:#ff4d4f;color:white;border:none;padding:4px 10px;border-radius:4px;cursor:pointer;">
          取消关注
        </button>
      </div>
    <?php } ?>
  </div>

  <!-- 个人设置 -->
  <div class="panel" id="profilePanel" style="display:none;">
    <h3>个人资料</h3>
    <form action="user_profile_save.php" method="post">
      <div class="form-row">
        <label>昵称</label>
        <input name="nickname" value="<?=$me['nickname']?>">
      </div>
      <div class="form-row">
        <label>性别</label>
        <input name="gender" value="<?=$me['gender']?>">
      </div>
      <div class="form-row">
        <label>手机号</label>
        <input name="phone" value="<?=$me['phone']?>">
      </div>
      <button class="btn">保存</button>
    </form>
  </div>
</div>

<script>
function showPanel(key){
  document.querySelectorAll('.panel').forEach(p=>p.style.display='none');
  document.getElementById(key+'Panel').style.display='block';
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  document.querySelector(`.tab:nth-child(${
    key=='orders'?1:key=='favorites'?2:key=='browse'?3:key=='follow'?4:5
  })`).classList.add('active');
}

// 头像上传
document.getElementById('avatarFile').onchange = function(){
  let form = new FormData(document.getElementById('avatarForm'));
  fetch('user_center.php', { method: 'POST', body: form })
  .then(res => res.json())
  .then(data => {
    if(data.ok){
      alert('头像上传成功！');
      document.getElementById('avatarPreview').src = data.url + '?t=' + Date.now();
    }else{
      alert('上传失败');
    }
  });
}

// ======================================
// 修复：删除收藏后真正删除 + 刷新
// ======================================
function deleteFavorite(favId, btn) {
  if(!confirm('确定要删除这条收藏吗？')) return;

  fetch(`user_center.php?act=delete_fav&id=${favId}`, { 
    method: 'GET',
    headers: { 'Accept': 'application/json' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.ok) {
      // 真正从DOM移除
      const item = btn.closest('.goods-item');
      if (item) item.remove();
      
      alert('删除成功！');

      // 如果全部删完了显示空状态
      const list = document.getElementById('favoriteList');
      if (list.querySelectorAll('.goods-item').length === 0) {
        list.innerHTML = '<div style="padding:15px;">暂无收藏</div>';
      }
    } else {
      alert('删除失败，请重试');
    }
  })
  .catch(err => {
    alert('请求失败');
    console.error(err);
  });
}

// 取消关注
function unfollowShop(seller, btn){
  if(!confirm('确定取消关注？')) return;
  fetch('api_follow.php?act=del&seller='+encodeURIComponent(seller))
  .then(res=>res.json()).then(data=>{
    if(data.ok){ btn.closest('div').remove(); alert('已取消'); }
  });
}
</script>

</body>
</html>