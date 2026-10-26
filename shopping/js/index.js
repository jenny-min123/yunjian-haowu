// 路由配置
const routes = {
  home: '/',
  clothes: '/clothes',
  shoes_bags: '/shoes_bags',
  fruits: '/fruits',
  daily_goods: '/daily_goods'
};

// 页面DOM结构加载完立刻执行，不等图片、购物车加载，速度极快，几乎不闪烁
document.addEventListener('DOMContentLoaded', function () {
  calcTotal();
  updateCart();
  renderCartItems();
});

// 导航点击路由跳转
document.querySelectorAll('.nav li').forEach(li => {
  li.addEventListener('click', function () {
    const route = this.dataset.route;
    window.location.href = routes[route]; // 跳转到对应路由
  });
});

// 移除旧的loadAllGoods函数（已废弃）
function loadAllGoods(){}


let cart = JSON.parse(localStorage.getItem('cart')) || [];
let totalCount = 0;
let totalPrice = 0;

// 搜索功能
function doSearch() {
  let kw = document.getElementById('searchInput').value.trim();
  if (kw === '') {
    location.search = '';
    return;
  }
  location.search = 'keyword=' + encodeURIComponent(kw);
}

// 点击推荐加载全部商品
function loadAllGoods() {
  location.search = '';
}

// 切换购物车显示
function toggleCartSummary() {
  let cartSum = document.getElementById('cartSummary');
  cartSum.style.display = cartSum.style.display === 'block' ? 'none' : 'block';
}

// 展开收起商品列表
function toggleCartList(e) {
  e.stopPropagation();
  let items = document.getElementById('cartItems');
  let arrow = document.querySelector('.arrow');
  if (items.style.display === 'block') {
    items.style.display = 'none';
    arrow.classList.remove('up');
  } else {
    items.style.display = 'block';
    arrow.classList.add('up');
    renderCartItems();
  }
}

// 加入购物车
function addToCart(btn, code, name, price, desc) {
  let item = btn.parentElement;
  btn.style.display = 'none';
  item.querySelector('.quantity').style.display = 'flex';
  item.querySelector('.quantity span').innerText = 1;

  let exist = cart.find(i => i.code === code);
  if (exist) {
    exist.qty = 1;
  } else {
    cart.push({ code, name, price, desc, qty: 1, checked: true });
  }
  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
}

// 首页数量加减
function changeQty(btn, num, price) {
  let qtyBox = btn.parentElement;
  let numEl = qtyBox.querySelector('span');
  let old = parseInt(numEl.innerText);
  let newNum = old + num;
  if (newNum < 0) newNum = 0;

  let item = btn.closest('.item');
  let code = item.getAttribute('onclick').match(/"(.*?)"/)[1];
  let goods = cart.find(i => i.code === code);
  if (!goods) return;

  if (newNum === 0) {
    item.querySelector('.item-btn').style.display = 'flex';
    qtyBox.style.display = 'none';
    cart = cart.filter(i => i.code !== code);
  } else {
    goods.qty = newNum;
    numEl.innerText = newNum;
  }

  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
}

// 购物车内数量加减
function changeCartQty(code, num) {
  let goods = cart.find(i => i.code === code);
  if (!goods) return;
  let newNum = goods.qty + num;
  if (newNum < 1) {
    cart = cart.filter(i => i.code !== code);
  } else {
    goods.qty = newNum;
  }

  document.querySelectorAll('.item').forEach(item => {
    let c = item.getAttribute('onclick').match(/"(.*?)"/)[1];
    if (c === code) {
      if (newNum < 1) {
        item.querySelector('.item-btn').style.display = 'flex';
        item.querySelector('.quantity').style.display = 'none';
        item.querySelector('.quantity span').innerText = 0;
      } else {
        item.querySelector('.quantity span').innerText = newNum;
      }
    }
  });

  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
}

// 计算总价
function calcTotal() {
  totalCount = 0;
  totalPrice = 0;
  cart.forEach(item => {
    if (item.checked) {
      totalCount += item.qty;
      totalPrice += item.qty * item.price;
    }
  });
  if (totalCount < 0) totalCount = 0;
  if (totalPrice < 0) totalPrice = 0;
}

// 更新界面
function updateCart() {
  let count = cart.reduce((sum, i) => sum + i.qty, 0);
  document.getElementById('cart-count').innerText = count;
  document.getElementById('sumCount').innerText = totalCount;
  document.getElementById('sumPrice').innerText = totalPrice.toFixed(2);
}

// 保存本地
function saveCart() {
  localStorage.setItem('cart', JSON.stringify(cart));
}

// 渲染购物车
function renderCartItems() {
  let box = document.getElementById('cartItems');
  if (cart.length === 0) {
    box.innerHTML = '<div style="text-align:center;color:#999;padding:10px 0;">购物车为空</div>';
    return;
  }
  let html = '';
  cart.forEach(item => {
    html += `
    <div class="cart-item" onclick="openModal('${item.code}','${item.name}','${item.price}','${item.desc}')">
      <div class="cart-img">        
        <input type="checkbox" class="cart-check" data-code="${item.code}" ${item.checked ? 'checked' : ''}
          onclick="event.stopPropagation();checkItem(this)">
        <img src="img/${item.code}.jpg" alt="">
      </div>        
      <div class="cart-qty" onclick="event.stopPropagation()">
        <button onclick="changeCartQty('${item.code}',-1)">-</button>
        <span>${item.qty}</span>
        <button onclick="changeCartQty('${item.code}',1)">+</button>
      </div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">￥${item.price} × ${item.qty}</div>
      </div>
    </div>
    `;
  });
  box.innerHTML = html;
}

// 勾选商品
function checkItem(box) {
  let code = box.dataset.code;
  let item = cart.find(i => i.code === code);
  if (item) item.checked = box.checked;
  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
}

// 全选
function selectAll() {
  let allChecked = cart.every(i => i.checked);
  cart.forEach(i => i.checked = !allChecked);
  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
}

// 删除选中
function deleteSelected() {
  let oldLen = cart.length;
  cart = cart.filter(i => !i.checked);
  if (cart.length === oldLen) {
    alert('请先选择要删除的商品');
    return;
  }
  document.querySelectorAll('.quantity span').forEach(s => s.innerText = 0);
  document.querySelectorAll('.item-btn').forEach(btn => btn.style.display = 'flex');
  document.querySelectorAll('.quantity').forEach(q => q.style.display = 'none');
  
  saveCart();
  calcTotal();
  updateCart();
  renderCartItems();
  alert('删除成功');
}

// 打开支付界面
function openPay() {
  let checkedItems = cart.filter(i => i.checked);
  if (checkedItems.length === 0) {
    alert('请先选择要结算的商品');
    return;
  }
  document.getElementById('payModal').style.display = 'flex';
  let list = document.getElementById('payList');
  let html = '';
  checkedItems.forEach(i => {
    html += `
    <div class="pay-item">
      <div>${i.name}</div>
      <div>￥${i.price} × ${i.qty}</div>
    </div>`;
  });
  list.innerHTML = html;
  document.getElementById('payTotal').innerText = totalPrice.toFixed(2);
}

// 关闭支付
function closePay() {
  document.getElementById('payModal').style.display = 'none';
}

// ===================== 【修复完成】支付函数 =====================
function doPay() {
  let checkedItems = cart.filter(i => i.checked);
  if (checkedItems.length === 0) {
    alert("请选择商品");
    return;
  }
  let total = document.getElementById("sumPrice").innerText;

  fetch("index.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "pay=1&items=" + encodeURIComponent(JSON.stringify(checkedItems)) + "&total=" + encodeURIComponent(total)
  })
  .then(res => {
    if (!res.ok) throw new Error("服务器异常");
    return res.json();
  })
  .then(data => {
    if (data.ok === 1) {
      alert("支付成功！");
      
      // 支付成功后清空已选商品
      cart = cart.filter(i => !i.checked);
      saveCart();
      
      // 重置页面商品按钮
      document.querySelectorAll('.quantity span').forEach(s => s.innerText = 0);
      document.querySelectorAll('.item-btn').forEach(btn => btn.style.display = 'flex');
      document.querySelectorAll('.quantity').forEach(q => q.style.display = 'none');

      closePay();
      calcTotal();
      updateCart();
      renderCartItems();
    } else {
      alert("支付失败：" + data.msg);
    }
  })
  .catch(err => {
    alert("支付错误：" + err.message);
  });
}

// 弹窗功能
function openModal(code, name, price, desc, seller='', coupon='', service='', ret='') {
  document.getElementById('modal').style.display = 'flex';
  document.getElementById('modalImg').src = 'img/' + code + '.jpg';
  document.getElementById('modalName').innerText = name;
  document.getElementById('modalPrice').innerText = '￥' + price;
  document.getElementById('modalDesc').innerText = desc;
  document.getElementById('modalSeller').innerText = seller;
  document.getElementById('modalCoupon').innerText = coupon;
  document.getElementById('modalService').innerText = service;
  document.getElementById('modalReturn').innerText = ret;
  document.getElementById('modalQty').innerText = 1;
  checkFavoriteStatus(code);
  addBrowseHistory(code);
}

function closeModal() {
  document.getElementById('modal').style.display = 'none';
}

function changeModalQty(num) {
  let qty = document.getElementById('modalQty');
  let n = parseInt(qty.innerText) + num;
  if (n < 1) n = 1;
  qty.innerText = n;
}

// 放大镜
const wrap = document.getElementById('magnifierWrap');
const mag = document.getElementById('magnifier');
const mImg = document.getElementById('modalImg');
if (wrap && mag && mImg) {
  wrap.addEventListener('mousemove', function (e) {
    mag.style.display = 'block';
    let rect = wrap.getBoundingClientRect();
    let x = e.clientX - rect.left;
    let y = e.clientY - rect.top;
    let mx = x - mag.offsetWidth / 2;
    let my = y - mag.offsetHeight / 2;
    if (mx < 0) mx = 0;
    if (my < 0) my = 0;
    if (mx > wrap.offsetWidth - mag.offsetWidth) mx = wrap.offsetWidth - mag.offsetWidth;
    if (my > wrap.offsetHeight - mag.offsetHeight) my = wrap.offsetHeight - mag.offsetHeight;
    mag.style.left = mx + 'px';
    mag.style.top = my + 'px';
    let scale = 2;
    mag.style.backgroundImage = `url(${mImg.src})`;
    mag.style.backgroundSize = `${wrap.offsetWidth * scale}px ${wrap.offsetHeight * scale}px`;
    mag.style.backgroundPosition = `-${mx * scale}px -${my * scale}px`;
  });
  wrap.addEventListener('mouseleave', function () {
    mag.style.display = 'none';
  });
}

function goTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
function goBottom() {
  window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}

// 点击商家跳转到商家主页
function gotoSeller(sellerName, backUrl){
  if(!backUrl) backUrl = "index.php";
  location.href = "seller.php?name="+encodeURIComponent(sellerName)+"&back="+encodeURIComponent(backUrl);
}

// 收藏商品
function toggleFavorite() {
  let code = document.getElementById('modalImg').src.split('/').pop().replace('.jpg', '');
  let name = document.getElementById('modalName').innerText;
  let price = document.getElementById('modalPrice').innerText.replace('￥', '');

  fetch('api_favorite.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'act=toggle&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&price='+encodeURIComponent(price)
  }).then(res=>res.json()).then(data=>{
    alert(data.msg);
    if(data.ok){
      let btn = document.getElementById('favoriteBtn');
      if(data.action == 'add'){
        btn.innerHTML = '<i class="fas fa-heart"></i> 已收藏';
        btn.style.background = '#ccc';
      }else{
        btn.innerHTML = '<i class="far fa-heart"></i> 收藏商品';
        btn.style.background = '#ff4d4f';
      }
    }
  });
}

// 打开弹窗时自动判断是否已收藏
function checkFavoriteStatus(code){
  fetch('api_favorite.php?act=check&code='+code)
  .then(res=>res.json()).then(data=>{
    let btn = document.getElementById('favoriteBtn');
    if(data.favorited){
      btn.innerHTML = '<i class="fas fa-heart"></i> 已收藏';
      btn.style.background = '#ccc';
    }else{
      btn.innerHTML = '<i class="far fa-heart"></i> 收藏商品';
      btn.style.background = '#ff4d4f';
    }
  });
}

// 打开商品弹窗时，添加浏览记录
function addBrowseHistory(code) {
  fetch('api_browse.php?act=add&code=' + code)
  .then(res => res.json())
  .then(data => {
    // 无需提示，静默记录
  });
}