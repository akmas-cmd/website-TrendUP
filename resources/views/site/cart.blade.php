<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('site.breadcrumb_cart') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/cart.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>
    <div class="d-flex align-items-center gap-3">
      <button class="icon-btn">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-count" id="cartCount">0</span>
      </button>

      <!-- Language switcher -->
      <div class="lang-switch-dropdown" id="langSwitchDropdown">
        <button class="lang-switch-btn" type="button" id="langSwitchToggle">
          <span>{{ strtoupper(app()->getLocale()) }}</span>
          <i class="fa-solid fa-chevron-down"></i>
        </button>
        <ul class="lang-switch-menu" id="langSwitchMenu">
          <li class="{{ app()->getLocale() === 'id' ? 'active' : '' }}"><a href="{{ route('lang.switch', 'id') }}">Bahasa Indonesia</a></li>
          <li class="{{ app()->getLocale() === 'en' ? 'active' : '' }}"><a href="{{ route('lang.switch', 'en') }}">English</a></li>
        </ul>
      </div>

      @guest
        <a href="{{ route('login') }}" class="btn-login d-none d-sm-inline-block">{{ __('site.nav_login') }}</a>
      @endguest

      @auth
        <div class="dropdown d-none d-sm-inline-block">
          <button class="btn-login" type="button" data-bs-toggle="dropdown" title="{{ auth()->user()->name }}">
            <i class="fa-solid fa-user"></i>
            <i class="fa-solid fa-chevron-down" style="font-size:10px;"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            @if (auth()->user()->isAdmin())
              <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard Admin</a></li>
            @else
              <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fa-solid fa-id-card"></i> {{ __('site.nav_profile') }}</a></li>
            @endif
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
              </form>
            </li>
          </ul>
        </div>
      @endauth
    </div>
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb-trendup">
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <a href="#">{{ __('site.nav_shop') }}</a> / <span class="current">{{ __('site.breadcrumb_cart') }}</span>
    </div>
    <h1>{{ __('site.cart_title') }}</h1>

    <div class="stepper">
      <div class="step active"><span class="num">1</span> {{ __('site.step_cart') }}</div>
      <div class="step-divider"></div>
      <div class="step"><span class="num">2</span> {{ __('site.step_checkout') }}</div>
      <div class="step-divider"></div>
      <div class="step"><span class="num">3</span> {{ __('site.step_done') }}</div>
    </div>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <div class="row g-5" id="cartWrapper">

      <div class="col-lg-8">
        <div class="cart-table-head">
          <div>{{ __('site.th_product') }}</div>
          <div>{{ __('site.th_price') }}</div>
          <div>{{ __('site.th_qty') }}</div>
          <div>{{ __('site.th_subtotal') }}</div>
          <div></div>
        </div>
        <div id="cartRows"></div>

        <a href="#" class="btn-continue"><i class="fa-solid fa-arrow-left"></i> {{ __('site.btn_continue_shopping') }}</a>
      </div>

      <div class="col-lg-4">
        <div class="summary-card">
          <h3>{{ __('site.summary_title') }}</h3>
          <div class="summary-row">
            <span>{{ __('site.summary_subtotal') }} (<span id="itemCount">0</span> {{ __('site.summary_item') }})</span>
            <span id="subtotalVal">Rp 0</span>
          </div>
          <div class="summary-row">
            <span>{{ __('site.summary_shipping') }}</span>
            <span id="shippingVal">Rp 15.000</span>
          </div>

          <div class="summary-row total">
            <span>{{ __('site.summary_total') }}</span>
            <span id="totalVal">Rp 0</span>
          </div>

          <a href="{{ route('checkout') }}" class="btn-checkout">
            {{ __('site.btn_checkout') }} <i class="fa-solid fa-arrow-right"></i>
          </a>
          <div class="secure-note"><i class="fa-solid fa-lock"></i> {{ __('site.secure_note') }}</div>
        </div>
      </div>

    </div>

    <div class="empty-cart" id="emptyState" style="display:none;">
      <i class="fa-solid fa-bag-shopping"></i>
      <h3>{{ __('site.empty_cart_title') }}</h3>
      <p>{{ __('site.empty_cart_desc') }}</p>
      <a href="#" class="btn-shopnow" style="background:var(--jet);color:var(--white);padding:14px 32px;border-radius:999px;font-weight:800;text-transform:uppercase;letter-spacing:1px;">{{ __('site.btn_shop_now') }}</a>
    </div>
  </div>
</section>

<div class="toast-add" id="toastAdd">
  <i class="fa-solid fa-circle-check"></i>
  <span id="toastText">{{ __('site.toast_item_removed_generic') }}</span>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>

// ===== LANGUAGE SWITCHER DROPDOWN =====
const langSwitchDropdown = document.getElementById("langSwitchDropdown");
const langSwitchToggle = document.getElementById("langSwitchToggle");
if (langSwitchDropdown && langSwitchToggle) {
  langSwitchToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    langSwitchDropdown.classList.toggle("open");
  });
  document.addEventListener("click", (e) => {
    if (!langSwitchDropdown.contains(e.target)) {
      langSwitchDropdown.classList.remove("open");
    }
  });
}
// String terjemahan dikirim dari Blade supaya JS ikut ganti bahasa
const I18N = {
  removedSuffix: @json(__('site.toast_item_removed')),
  removedGeneric: @json(__('site.toast_item_removed_generic')),
};

const SHIPPING_FEE = 15000;

// Peta terjemahan nama kategori (EN/ID) - sama seperti CAT_LABEL di shop.blade.php,
// supaya kategori produk di keranjang ikut berubah saat bahasa antarmuka diganti.
const CAT_LABEL = {
  "jam-tangan": @json(__('site.cat_jam_tangan')),
  "jam-dinding": @json(__('site.cat_jam_dinding')),
  "jam-beker": @json(__('site.cat_jam_beker')),
  "baju": @json(__('site.cat_baju')),
  "sepatu": @json(__('site.cat_sepatu')),
  "celana": @json(__('site.cat_celana')),
  "jaket": @json(__('site.cat_jaket')),
};
function catLabel(key){
  return CAT_LABEL[key] || key;
}

let cart = [
  {id:1, name:"TRENDUP Chrono Black", cat:"jam-tangan", price:350000, qty:1, size:null, icon:"fa-solid fa-clock"},
  {id:5, name:"TRENDUP Runner Lime", cat:"sepatu", price:425000, qty:1, size:"41", icon:"fa-solid fa-shoe-prints"},
];

function formatRupiah(num){
  return "Rp " + num.toLocaleString("id-ID");
}

function renderCart(){
  const rows = document.getElementById("cartRows");
  const wrapper = document.getElementById("cartWrapper");
  const emptyState = document.getElementById("emptyState");

  if(cart.length === 0){
    wrapper.style.display = "none";
    emptyState.style.display = "block";
    updateCartCount();
    return;
  }
  wrapper.style.display = "flex";
  emptyState.style.display = "none";

  rows.innerHTML = cart.map(item => `
    <div class="cart-row">
      <div class="cart-product">
        <div class="cart-thumb"><i class="${item.icon}"></i></div>
        <div>
          <div class="cart-name">${item.name}</div>
          <div class="cart-meta">${catLabel(item.cat)}</div>
          ${item.size ? `<div class="cart-size-select">${item.size}</div>` : ""}
        </div>
      </div>
      <div class="cart-price">${formatRupiah(item.price)}</div>
      <div class="qty-control">
        <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
        <span class="qty-val">${item.qty}</span>
        <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
      </div>
      <div class="cart-subtotal">${formatRupiah(item.price * item.qty)}</div>
      <button class="remove-btn" onclick="removeItem(${item.id})"><i class="fa-solid fa-xmark"></i></button>
    </div>
  `).join("");

  updateSummary();
  updateCartCount();
}

function changeQty(id, delta){
  const item = cart.find(i => i.id === id);
  if(!item) return;
  item.qty += delta;
  if(item.qty <= 0){
    cart = cart.filter(i => i.id !== id);
    showToast(I18N.removedGeneric);
  }
  renderCart();
}

function removeItem(id){
  const item = cart.find(i => i.id === id);
  cart = cart.filter(i => i.id !== id);
  renderCart();
  if(item) showToast(`${item.name} ${I18N.removedSuffix}`);
}

function updateSummary(){
  const subtotal = cart.reduce((a,b)=> a + (b.price*b.qty), 0);
  const totalItems = cart.reduce((a,b)=> a + b.qty, 0);
  const shipping = cart.length ? SHIPPING_FEE : 0;
  const total = subtotal + shipping;

  document.getElementById("itemCount").textContent = totalItems;
  document.getElementById("subtotalVal").textContent = formatRupiah(subtotal);
  document.getElementById("shippingVal").textContent = formatRupiah(shipping);
  document.getElementById("totalVal").textContent = formatRupiah(Math.max(total,0));
}

function updateCartCount(){
  const totalQty = cart.reduce((a,b)=> a + b.qty, 0);
  document.getElementById("cartCount").textContent = totalQty;
}

function showToast(msg){
  const toast = document.getElementById("toastAdd");
  document.getElementById("toastText").textContent = msg;
  toast.classList.add("show");
  setTimeout(()=> toast.classList.remove("show"), 2000);
}

renderCart();
</script>
</body>
</html>