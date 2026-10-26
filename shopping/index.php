<?php
// 开启会话，用于判断用户是否登录
session_start();

// 判断用户是否登录，如果未登录，强制跳转到登录页
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ==========================================
// 支付接口：用户点击付款后，后端接收数据
// 功能：生成订单、更新商品销量、销售额
// ==========================================
if (isset($_POST['pay'])) {
    // 引入数据库连接
    include "db_conn.php";
    // 获取当前登录的用户名
    $user = $_SESSION['user']['username'];
    // 获取前端提交的购物车商品（JSON转数组）
    $items = json_decode($_POST['items'], true);
    // 获取总金额
    $total = (float)$_POST['total'];

    // 如果购物车没有商品，返回错误
    if (empty($items)) {
        echo json_encode(['ok' => 0, 'msg' => '购物车为空']);
        exit;
    }

    // 循环遍历购物车里的每一件商品
    foreach ($items as $it) {
        $code = $it['code'];          // 商品编号
        $name = $it['name'];          // 商品名
        $price = $it['price'];        // 商品单价
        $qty = $it['qty'];            // 购买数量
        $totalPrice = $price * $qty;  // 该商品总价

        // 要查询的商品表（4个分类）
        $tables = ['clothes','shoes_bags','fruits','daily_goods'];
        $seller = '';  // 商家名
        $table = '';   // 所属分类表

        // 循环查询商品属于哪个表、哪个商家
        foreach ($tables as $t) {
            $res = mysqli_query($conn, "SELECT seller FROM $t WHERE code='$code'");
            if (mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_array($res);
                $seller = $row['seller'];
                $table = $t;
                break;
            }
        }

        // 如果找不到商家，跳过该商品
        if (!$seller) continue;

        // 向订单表插入一条订单记录
        mysqli_query($conn, "INSERT INTO orders (user_id, code, name, price, qty, total_price, seller, type, create_time)
                           VALUES ('$user','$code','$name','$price','$qty','$totalPrice','$seller','$table',NOW())");

        // 更新商品的销量、销售额
        mysqli_query($conn, "UPDATE $table
                            SET sales_count = sales_count + $qty,
                                sales_money = sales_money + $totalPrice,
                                last_update = NOW()
                            WHERE code='$code'");
    }

    // 返回支付成功信息给前端
    echo json_encode(['ok' => 1, 'msg' => '支付成功']);
    exit;
}

// ==========================================
// 退出登录功能
// ==========================================
if (isset($_GET['logout'])) {
    // 销毁会话（退出登录）
    session_destroy();
    // 清除自动登录cookie
    setcookie("user_auto_login", "", time() - 3600, "/");
    // 跳回登录页
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <!-- 网页视口设置，适配手机端 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- 网页图标 -->
  <link rel="icon" href="public/cloud.png">
  <!-- 首页样式表 -->
  <link rel="stylesheet" href="css/index.css">
  <!-- 引入图标库 -->
  <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/css/all.min.css">  
  <title>云间好物</title>
</head>
<body>

  <!-- 顶部导航栏 -->
  <nav>
    <div class="title">
      <!-- 退出登录按钮 -->
      <a href="?logout=1">退出登录</a>
      <!-- LOGO图片 -->
      <img src="public/cloud.png" alt="">
      <h1 class="topic">云间好物</h1>
    </div>
    
    <!-- 搜索框区域 -->
    <div class="search">
      <input type="text" id="searchInput" class="searchinput" placeholder="搜索商品名称/详情" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
      <img src="public/search.png" alt="" class="search-icon" onclick="doSearch()">
    </div>

    <!-- 购物车图标 + 数量角标 -->
    <div class="cart-wrapper" onclick="toggleCartSummary()">
      <img class="cart" src="public/cart.png" alt="">
      <div id="cart-count">0</div>
    </div>
    <a class="myself" href="user_center.php">我的</a>
  </nav>

  <!-- 主体容器：分类导航 + 商品列表 -->
  <div class="container">
  <ul class="nav">
    <?php
      // 判断当前页面是哪个分类（用于导航高亮）
      $currentRoute = '';
      $uri = $_SERVER['REQUEST_URI'];
      $uri = strtok($uri, '?');
      if ($uri == '/' || $uri == '/index.php') {
        $currentRoute = 'home';
      } else {
        $currentRoute = trim($uri, '/');
      }
    ?>

    <!-- 分类导航菜单 -->
    <li class="<?= $currentRoute == 'home' ? 'active' : '' ?>" data-route="home">推荐</li>
    <li class="<?= $currentRoute == 'clothes' ? 'active' : '' ?>" data-route="clothes">服饰</li>
    <li class="<?= $currentRoute == 'shoes_bags' ? 'active' : '' ?>" data-route="shoes_bags">鞋包</li>
    <li class="<?= $currentRoute == 'fruits' ? 'active' : '' ?>" data-route="fruits">水果</li>
    <li class="<?= $currentRoute == 'daily_goods' ? 'active' : '' ?>" data-route="daily_goods">百货</li>

  </ul>

    <!-- 内容区域 -->
    <div class="content">
      <div class="word">云间好物·不止购物</div>
      <!-- 商品列表展示区域 -->
      <div class="list" id="goodsList">
      
      <?php
      // 引入数据库连接
      include "db_conn.php";

      // 分页基础配置
      $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;  // 当前页码
      $pageSize = 8;                                                   // 每页显示8个商品
      $offset = ($page - 1) * $pageSize;                               // SQL 偏移量
      $total = 0;                                                      // 商品总数
      $allData = [];                                                   // 存放所有商品

      // 获取当前路由（首页 / 分类页 / 搜索页）
      $route = '';
      $uri = $_SERVER['REQUEST_URI'];
      $uri = strtok($uri, '?');
      if ($uri == '/' || $uri == '/index.php' || $uri == '') {
          $route = 'home';
      } else {
          $route = trim($uri, '/');
      }

      // ==========================================
      // 搜索功能：如果用户输入关键词，执行搜索
      // ==========================================
      if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
          $kw = (string)trim($_GET['keyword'] ?? '');
          $tables = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];

          // 统计符合搜索条件的商品总数
          foreach ($tables as $table) {
              $countSql = "SELECT COUNT(*) as cnt FROM `$table` WHERE name LIKE '%$kw%' OR description LIKE '%$kw%'";
              $countRes = mysqli_query($conn, $countSql);
              $total += mysqli_fetch_array($countRes)['cnt'];
          }

          // 读取当前页需要显示的商品
          $need = $pageSize;
          foreach ($tables as $table) {
              if ($need <= 0) break;
              $sql = "SELECT * FROM `$table` WHERE name LIKE '%$kw%' OR description LIKE '%$kw%' LIMIT $offset, $need";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_array($result)) {
                  $row['_table'] = $table;
                  $allData[] = $row;
                  $need--;
              }
          }
      } 
      // ==========================================
      // 首页推荐：不分页，一次性展示所有分类随机商品
      // ==========================================
      elseif ($route == 'home') {
          $tables = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];
          foreach ($tables as $table) {
              $sql = "SELECT * FROM `$table` ORDER BY RAND() LIMIT 5";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_array($result)) {
                  $row['_table'] = $table;
                  $allData[] = $row;
              }
          }
          $total = count($allData);
      } 
      // ==========================================
      // 分类页面：服饰 / 鞋包 / 水果 / 百货，带分页
      // ==========================================
      else {
          $validTables = ['clothes', 'shoes_bags', 'fruits', 'daily_goods'];
          if (!in_array($route, $validTables)) {
              echo "<div style='width:100%;text-align:center;padding:40px 0;'>页面不存在</div>";
              exit;
          }
          $countSql = "SELECT COUNT(*) as cnt FROM `$route`";
          $countRes = mysqli_query($conn, $countSql);
          $total = mysqli_fetch_array($countRes)['cnt'];

          $sql = "SELECT * FROM `$route` LIMIT $offset, $pageSize";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($result)) {
              $allData[] = $row;
          }
      }

      // ==========================================
      // 渲染商品列表到页面
      // ==========================================
      if (empty($allData)) {
          echo "<div style='width:100%;text-align:center;padding:40px 0;'>未找到相关商品</div>";
      } else {
          foreach ($allData as $row) {
              $code = $row['code'];
              $price = $row['price'];
              $name = $row['name'];
              $description = $row['description'];
              $seller = $row['seller'];
              $coupon = $row['coupon_info'];
              $service = $row['service'];
              $ret = $row['return_free_shipping'] ? '退货包邮' : '退货不包邮';

              // 输出商品卡片
              echo "
              <div class='item' onclick='openModal(\"$code\",\"$name\",\"$price\",\"$description\",\"$seller\",\"$coupon\",\"$service\",\"$ret\")'>
                <div class='item-img'>
                    <img src='img/$code.jpg' alt=''>            
                </div>
                <div class='item-content'>
                  <div class='item-name'>$name</div>
                  <div class='item-price'>￥$price</div>
                  <div class='item-extra'>
                    商家：<span class='seller-name' onclick='event.stopPropagation(); gotoSeller(\"$seller\", window.location.href)'>$seller</span>
                  </div>
                  <div class='item-extra'>
                    <span class='tag-coupon'>$coupon</span>
                    <span class='tag-service'>$service</span>
                    <span class='tag-return'>$ret</span>
                  </div>
                  <div class='item-info'>$description</div>
                </div>
                <div class='item-btn' onclick='event.stopPropagation(); addToCart(this, \"$code\",\"$name\",$price,\"$description\")'>加入购物车</div>
                <div class='quantity'>
                  <button onclick='event.stopPropagation(); changeQty(this,-1,$price)'>-</button>
                  <span>0</span>
                  <button onclick='event.stopPropagation(); changeQty(this,1,$price)'>+</button>
                </div>
              </div>
              ";
          }
      }
      ?>
      </div>

      <!-- 分页条：只在分类页、搜索页显示 -->
      <?php if($route != 'home'){ ?>
      <div class="pagination">
        <?php
        $totalPage = ceil($total / $pageSize);
        $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
        $paramStr = '';
        $params = $_GET;
        unset($params['page']);
        if(!empty($params)){
            $paramStr = '&'.http_build_query($params);
        }

        // 第一页
        if ($page <= 1) {
            echo "<a class='disabled'>第一页</a>";
        } else {
            echo "<a href='$baseUrl?page=1$paramStr'>第一页</a>";
        }

        // 上一页
        if ($page <= 1) {
            echo "<a class='disabled'>上一页</a>";
        } else {
            echo "<a href='$baseUrl?page=" . ($page - 1) . "$paramStr'>上一页</a>";
        }

        echo "<a class='active'>第 $page 页</a>";

        // 下一页
        if ($page >= $totalPage) {
            echo "<a class='disabled'>下一页</a>";
        } else {
            echo "<a href='$baseUrl?page=" . ($page + 1) . "$paramStr'>下一页</a>";
        }

        // 最后一页
        if ($page >= $totalPage || $totalPage == 0) {
            echo "<a class='disabled'>最后一页</a>";
        } else {
            echo "<a href='$baseUrl?page=$totalPage$paramStr'>最后一页</a>";
        }
        ?>
      </div>
      <?php } ?>
    </div>
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
          商家：<span id="modalSeller" style="color:#1677ff;cursor:pointer" onclick="gotoSeller(this.innerText)"></span>
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

  <!-- 购物车面板 -->
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

  <!-- 支付确认弹窗 -->
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

  <!-- 回到顶部按钮 -->
  <div class="top" onclick="goTop()">
    <img src="public/up.png" alt="">
  </div>

  <!-- 回到底部按钮 -->
  <div class="bottom" onclick="goBottom()">
    <img src="public/down.png" alt="">
  </div>

  <!-- 底部页脚 -->
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

  <!-- 首页交互JS -->
  <script src="js/index.js"></script>
</body>
</html>