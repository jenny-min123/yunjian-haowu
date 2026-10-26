<?php
session_start();
include "db_conn.php";

// 获取URL传入的店铺名称（外部点击跳转过来的店铺名）
$sellerName = "";
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $sellerName = trim(urldecode($_GET['name'])); //urldecode把传入的参数解码，比如传进来的是中文是乱码变成中文显示
}

// 来源页面（用于返回）
$backUrl = $_GET['back'] ?? 'index.php';

// ====================== 登录互斥（用户/商家session绝对互斥，原逻辑保留加固）======================
if (isset($_SESSION['seller']) && !empty($_SESSION['seller'])) {
    unset($_SESSION['user']); 
}
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    unset($_SESSION['seller']); 
}

// 1. 普通用户登录状态判断
$isUserLogin = false;
if (isset($_SESSION['user']) && !empty(trim($_SESSION['user']['username']))) {
    $isUserLogin = true;
}

// 2. 商家登录状态 + 精准解析商家session（完全适配你login.php的存储结构）
// 现在 session 里同时有：username（登录账号）+ seller_name（店铺名）
// 这里要做的是「判断是不是本人店铺」，比对对象必须是店铺名
$isSellerLogin = false;
$loginSellerName = '';
if (isset($_SESSION['seller']) && !empty($_SESSION['seller'])) {
    $isSellerLogin = true;
    if (is_array($_SESSION['seller'])) {
        $loginSellerName = trim($_SESSION['seller']['seller_name'] ?? $_SESSION['seller']['username'] ?? '');
    } else {
        $loginSellerName = trim($_SESSION['seller']);
    }
}

// 3. 判断：是否是【商家本人访问自己的店铺】（强全等对比，值+类型完全匹配）
$isMyShop = false;
if ($isSellerLogin && !empty($loginSellerName) && !empty($sellerName) && $loginSellerName === $sellerName) {
    $isMyShop = true;
}

// ====================== 【核心】全局统一显示店铺名称（所有位置都用这个变量）======================
$showShopName = "";
if ($isMyShop) {
    // 商家本人：显示自己登录的店铺名（注册的店铺名）
    $showShopName = $loginSellerName;
} else {
    // 用户/游客/其他商家：显示URL跳转过来的目标店铺名
    $showShopName = $sellerName;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/cloud.png">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/seller.css">
  <title>云间好物 - 商家主页</title>
</head>
<body>
  <!-- 商家头部信息 -->
  <div class="seller-header">
    <div class="seller-header-container">
      <!-- 返回按钮 -->
      <div class="back-btn">
        <i class="fas fa-arrow-left"></i>
      </div>

      <div class="seller-avatar">
        <img src="public/cloud.png" alt="商家头像">
      </div>
      <div class="seller-info">
        <h2 class="seller-name" id="sellerName"></h2>
        <div class="seller-meta">
          <span><i class="fas fa-shopping-cart"></i> 商品数量: <span id="goodsCount">0</span></span>
          <span><i class="fas fa-star" style="color:#faad14"></i> 店铺评分: 4.8</span>
          <span><i class="fas fa-location-arrow"></i> 发货地: 深圳</span>
        </div>
        <div class="seller-desc" id="sellerDesc">
          专注优质商品，诚信经营，为您提供更好的购物体验！
        </div>
        <button class="seller-btn" id="followShopBtn" onclick="toggleFollowShop()">关注店铺</button>
      </div>
    </div>
  </div>
  <!-- 商家标签页 -->
  <div class="seller-tabs">
    <div class="seller-tab active" onclick="showTab('all')">全部商品</div>
    <div class="seller-tab" onclick="showTab('hot')">热卖商品</div>
  </div>
  <!-- 商家商品列表 -->
  <div class="seller-goods" id="sellerGoodsList">
    <?php
      $tables = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];
      $allGoods = [];

      foreach ($tables as $table) {
          $sql = "SELECT * FROM $table WHERE seller = '$sellerName'";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($result)) {
              $row['_table'] = $table;
              $allGoods[] = $row;
          }
      }

      $goodsCount = count($allGoods);
      // 页面顶部店铺名、商品总数 渲染
      echo "<script>
              document.getElementById('sellerName').innerText = '{$showShopName}';
              document.getElementById('goodsCount').innerText = {$goodsCount};
            </script>";

      if (empty($allGoods)) {
          echo "<div class='no-goods'>该商家暂无商品</div>";
      } else {
          foreach ($allGoods as $row) {
              $code = $row['code'];
              $price = $row['price'];
              $name = $row['name'];
              $description = $row['description'];
              $dbSeller = $row['seller'];//数据库原始店铺名（跳转传参专用，不用于展示）
              $coupon = $row['coupon_info'];
              $service = $row['service'];
              $ret = $row['return_free_shipping'] ? '退货包邮' : '退货不包邮';
              
              echo "
              <div class='item' onclick='openModal(\"{$code}\",\"{$name}\",\"{$price}\",\"{$description}\",\"{$dbSeller}\",\"{$coupon}\",\"{$service}\",\"{$ret}\")'>
                <div class='item-img'>
                    <img src='img/{$code}.jpg' alt=''>            
                </div>
                <div class='item-content'>
                  <div class='item-name'>{$name}</div>
                  <div class='item-price'>￥{$price}</div>
                  <div class='item-extra'>
                    商家：<span class='seller-name' onclick='event.stopPropagation(); gotoSeller(\"{$dbSeller}\")'>{$showShopName}</span>
                  </div>
                  <div class='item-extra'>
                    <span class='tag-coupon'>{$coupon}</span>
                    <span class='tag-service'>{$service}</span>
                    <span class='tag-return'>{$ret}</span>
                  </div>
                  <div class='item-info'>{$description}</div>
                </div>
                <div class='item-btn' onclick='event.stopPropagation(); addToCart(this, \"{$code}\",\"{$name}\",{$price},\"{$description}\")'>加入购物车</div>
                <div class='quantity'>
                  <button onclick='event.stopPropagation(); changeQty(this,-1,{$price})'>-</button>
                  <span>0</span>
                  <button onclick='event.stopPropagation(); changeQty(this,1,{$price})'>+</button>
                </div>
              </div>
              ";
          }
      }
    ?>
  </div>

  <!-- 商品详情弹窗 -->
  <div class="modal" id="modal" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
      <div class="modal-close" onclick="closeModal()">×</div>
      <div class="modal-left" id="magnifierWrap">
        <img id="modalImg" src="">
        <div class="magnifier" id="magnifier"></div>
      </div>
      <div class="modal-right">
        <div class="name" id="modalName"></div>
        <div class="price" id="modalPrice"></div>
        <div class="modal-extra">
          商家：<span id="modalSeller" style="color:#1677ff;cursor:pointer" onclick="gotoSeller(this.dataset.seller)"></span>
        </div>
        <div class="modal-extra">
          <span id="modalCoupon" class="tag-coupon"></span>
          <span id="modalService" class="tag-service"></span>
          <span id="modalReturn" class="tag-return"></span>
        </div>
        <div class="desc" id="modalDesc"></div>
        <div class="quantity">
          <button onclick="changeModalQty(-1)">-</button>
          <span id="modalQty">1</span>
          <button onclick="changeModalQty(1)">+</button>
        </div>
        <!-- 收藏按钮 -->
        <div style="margin-top:12px;">
          <button id="favoriteBtn" style="padding:8px 16px;background:#ff4d4f;color:#fff;border:none;border-radius:6px;cursor:pointer;" onclick="toggleFavorite()">
            <i class="far fa-heart"></i> 收藏商品
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- 购物车 -->
  <div class="cart-summary" id="cartSummary">
    <p>
      已选商品：<span id="sumCount">0</span> 件
      <span class="arrow" onclick="toggleCartList(event)">▼</span>
    </p>
    <div id="cartItems"></div>
    <p class="total">合计：￥<span id="sumPrice">0.00</span></p>
    <div class="cart-btns">
      <button id="selectAll" onclick="selectAll()">全选</button>
      <button id="delSelected" onclick="deleteSelected()">删除选中</button>
      <button id="payBtn" onclick="openPay()">付款</button>
    </div>
  </div>

  <!-- 支付弹窗 -->
  <div class="pay-modal" id="payModal">
    <div class="pay-box">
      <div class="pay-title">确认订单</div>
      <div id="payList"></div>
      <div class="pay-total">总支付：￥<span id="payTotal">0.00</span></div>
      <div class="pay-btns">
        <button id="cancelPay" onclick="closePay()">取消</button>
        <button id="confirmPay" onclick="doPay()">确认支付</button>
      </div>
    </div>
  </div>

  <!-- 回到顶部/底部 -->
  <div class="top" onclick="goTop()">
    <img src="public/up.png" alt="">
  </div>
  <div class="bottom" onclick="goBottom()">
    <img src="public/down.png" alt="">
  </div>

  <!-- 页脚 -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-col">
        <h4>关于我们</h4>
        <ul>
          <li><a href="#">公司简介</a></li>
          <li><a href="#">招贤纳士</a></li>
          <li><a href="#">联系我们</a></li>
          <li><a href="#">隐私政策</a></li>
          <li><a href="#">用户协议</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>客户服务</h4>
        <ul>
          <li><a href="#">帮助中心</a></li>
          <li><a href="#">订单查询</a></li>
          <li><a href="#">配送说明</a></li>
          <li><a href="#">退换货政策</a></li>
          <li><a href="#">常见问题</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>购物指南</h4>
        <ul>
          <li><a href="#">注册登录</a></li>
          <li><a href="#">购物流程</a></li>
          <li><a href="#">支付方式</a></li>
          <li><a href="#">优惠券使用</a></li>
          <li><a href="#">会员福利</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>关注我们</h4>
        <div class="contact-info">
          <p><i class="fas fa-phone"></i> 240200260</p>
          <p><i class="fas fa-envelope"></i> 240200260@mail.szpu.edu.cn</p>
          <p><i class="fas fa-map-marker-alt"></i> 深圳职业技术大学</p>
        </div>
        <div class="social-icons">
          <a href="#"><i class="fab fa-weixin"></i></a>
          <a href="#"><i class="fab fa-weibo"></i></a>
          <a href="#"><i class="fab fa-qq"></i></a>
          <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
        <div class="subscribe">
          <input type="email" placeholder="输入邮箱领取优惠">
          <button>订阅</button>
        </div>
      </div>
    </div>
    <div class="footer-container payment">
      <div class="footer-col">
        <h4>支付方式</h4>
        <div class="payment-icons">
          <i class="fab fa-alipay"></i>
          <i class="fab fa-weixin"></i>
          <i class="fab fa-cc-visa"></i>
          <i class="fab fa-cc-mastercard"></i>
          <i class="fab fa-cc-paypal"></i>
        </div>
      </div>
    </div>
    <div class="copyright">
      <p>© 2026 云间好物 版权所有 | 240200260</p>
    </div>
  </footer>

  <script src="js/index.js"></script>
  <script>
    function toggleFollow(){
      let seller = "<?=$showShopName?>";
      fetch('api_follow.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'seller='+encodeURIComponent(seller)
      }).then(res=>res.json()).then(data=>{
        alert(data.msg);
        let btn = document.getElementById('followBtn');
        if(data.ok){
          btn.innerText = data.action == 'follow' ? '已关注' : '关注店铺';
          btn.style.background = data.action == 'follow' ? '#ccc' : '#409eff';
        }
      });
    }

    // 页面加载时检查是否已关注
    window.onload = function(){
      let seller = "<?=$showShopName?>";
      fetch('api_follow.php?check=1&seller='+seller)
      .then(res=>res.json()).then(data=>{
        if(data.followed){
          document.getElementById('followBtn').innerText = '已关注';
          document.getElementById('followBtn').style.background = '#ccc';
        }
      });
    }
    // 标签页切换
    function showTab(type) {
      document.querySelectorAll('.seller-tab').forEach(tab => {
        tab.classList.remove('active');
      });
      document.querySelector(`.seller-tab[onclick="showTab('${type}')"]`).classList.add('active');
    }

    // 跳转其他店铺（永远传数据库原始seller，保证链接正确）encodeURIComponent专门用来加密网址参数的方法
    function gotoSeller(sellerName){
      location.href = "seller.php?name="+encodeURIComponent(sellerName);
    }

    // 打开商品详情弹窗
    // 参数：code,name,price,desc,【数据库原始seller】,coupon,service,ret
    const globalShowName = "<?=$showShopName?>";//PHP渲染全局统一店铺名到前端JS
    function openModal(code,name,price,desc,dbSeller,coupon,service,ret){
      document.getElementById('modal').style.display = 'flex';
      document.getElementById('modalImg').src = 'img/'+code+'.jpg';
      document.getElementById('modalName').innerText = name;
      document.getElementById('modalPrice').innerText = '￥'+price;
      document.getElementById('modalDesc').innerText = desc;
      // 弹窗展示用统一名称，跳转原始数据库名称
      const modalSellerDom = document.getElementById('modalSeller');
      modalSellerDom.innerText = globalShowName;
      modalSellerDom.dataset.seller = dbSeller;//存储真实跳转参数
      document.getElementById('modalCoupon').innerText = coupon;
      document.getElementById('modalService').innerText = service;
      document.getElementById('modalReturn').innerText = ret;
      document.getElementById('modalQty').innerText = 1;
    }

    // 关闭弹窗
    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    // 弹窗数量修改
    function changeModalQty(num) {
      let qty = parseInt(document.getElementById('modalQty').innerText);
      qty += num;
      if(qty < 1) qty = 1;
      document.getElementById('modalQty').innerText = qty;
    }


    // 智能返回：从哪来，回哪去
    document.querySelector('.back-btn').onclick = function(){
        <?php if($isMyShop) { ?>
            // 商家自己 → 回后台
            location.href = "sellerindex.php";
        <?php } else { ?>
            // 用户 → 回到进入前的页面decodeURIComponent用于解码
            const backUrl = decodeURIComponent("<?php echo urlencode($_GET['back'] ?? 'index.php'); ?>");
            location.href = backUrl;
        <?php } ?>
    };

    let currentSeller = "<?= $showShopName ?>";

// 打开店铺页时检查关注状态
function checkFollowStatus(){
  fetch('api_follow.php?act=check&seller='+encodeURIComponent(currentSeller))
  .then(res=>res.json()).then(data=>{
    let btn = document.getElementById('followShopBtn');
    if(data.followed){
      btn.innerText = '已关注';
      btn.style.background = '#ccc';
    }else{
      btn.innerText = '关注店铺';
      btn.style.background = '#409eff';
    }
  });
}

// 切换关注/取消
function toggleFollowShop(){
  let btn = document.getElementById('followShopBtn');
  let isFollowed = btn.innerText === '已关注';
  let act = isFollowed ? 'del' : 'add';

  fetch('api_follow.php?act='+act+'&seller='+encodeURIComponent(currentSeller))
  .then(res=>res.json()).then(data=>{
    if(data.ok){
      if(isFollowed){
        btn.innerText = '关注店铺';
        btn.style.background = '#409eff';
        alert('已取消关注');
      }else{
        btn.innerText = '已关注';
        btn.style.background = '#ccc';
        alert('关注成功');
      }
    }
  });
}

// 页面加载时执行
checkFollowStatus();
  </script>
</body>
</html>