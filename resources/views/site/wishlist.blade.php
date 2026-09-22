<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('site.wishlist_heading') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/wishlist.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>
    <div class="d-flex align-items-center gap-3">
      <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
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
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <span class="current">{{ __('site.wishlist_heading') }}</span>
    </div>
    <h1>{{ __('site.wishlist_heading') }}</h1>
    <p>{{ __('site.wishlist_subtitle') }}</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">

    <div class="empty-state" id="emptyState">
      <i class="fa-regular fa-heart"></i>
      <h3>{{ __('site.wishlist_empty_title') }}</h3>
      <p>{{ __('site.wishlist_empty_desc') }}</p>
      <a href="{{ route('shop') }}" class="btn-shop">{{ __('site.btn_shop_now') }}</a>
    </div>

    <div class="row g-4" id="wishlistGrid"></div>

  </div>
</section>

<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-logo">TREND<span>UP</span></div>
        <p style="color:var(--cool-gray);font-size:14px;margin-top:14px;">{{ __('site.footer_desc') }}</p>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_shop_title') }}</h6>
        <a href="{{ route('shop') }}">{{ __('site.shop_title') }}</a>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_help_title') }}</h6>
        <a href="#">{{ __('site.footer_help_howto') }}</a>
        <a href="{{ route('tracking') }}">{{ __('site.footer_help_tracking') }}</a>
      </div>
    </div>
    <div class="footer-bottom d-flex justify-content-between flex-wrap gap-2">
      <span>{{ __('site.footer_project_copyright') }}</span>
      <span>{{ __('site.footer_project_disclaimer') }}</span>
    </div>
  </div>
</footer>

<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
  <div class="offcanvas-header">
    <h5 class="mb-0" style="font-family:'Anton',sans-serif;">{{ __('site.offcanvas_cart_title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <div id="cartItemsList" class="flex-grow-1"></div>
    <div id="cartEmpty" class="text-center text-muted py-5">
      <i class="fa-solid fa-bag-shopping fa-2x mb-3" style="color:var(--cool-gray)"></i>
      <p>{{ __('site.offcanvas_cart_empty') }}</p>
    </div>
    <div class="border-top pt-3 mt-2" id="cartSummary" style="display:none;border-color:var(--jet)!important;">
      <div class="d-flex justify-content-between mb-3">
        <strong>{{ __('site.offcanvas_cart_total') }}</strong>
        <strong id="cartTotal">Rp 0</strong>
      </div>
      <a href="{{ route('checkout') }}" class="btn-shop w-100 text-center" style="border-radius:999px;">{{ __('site.offcanvas_cart_checkout') }}</a>
    </div>
  </div>
</div>

<div class="toast-add" id="toastAdd">
  <i class="fa-solid fa-circle-check"></i>
  <span id="toastText">{{ __('site.toast_success_default') }}</span>
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
  removeTitle: @json(__('site.wishlist_remove_title')),
  addToCart: @json(__('site.product_add_to_cart')),
  added: @json(__('site.product_added')),
  removedFromWishlist: @json(__('site.toast_removed_from_wishlist')),
  addedToCart: @json(__('site.toast_added_to_cart')),
};

function formatRupiah(num){ return "Rp " + num.toLocaleString("id-ID"); }

// Wishlist sekarang diambil dari database lewat WishlistController@index
// (dikirim ke view lewat variabel $wishlistData), bukan localStorage lagi.
let wishlistItems = @json($wishlistData);
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

function getCart(){
  return JSON.parse(localStorage.getItem("trendup_cart") || "[]");
}
function saveCart(list){
  localStorage.setItem("trendup_cart", JSON.stringify(list));
}

function wishlistCard(p){
  const imgHtml = p.img
    ? `<img src="${p.img}" alt="${p.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
       <i class="${p.icon}" style="display:none;"></i>`
    : `<i class="${p.icon}"></i>`;
  return `
    <div class="col-6 col-md-4 col-lg-3">
      <div class="wishlist-card">
        <button class="remove-heart" onclick="removeWishlist(${p.id})" title="Hapus dari wishlist">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="wishlist-img">${imgHtml}</div>
        <div class="wishlist-info">
          <div class="wishlist-cat">${p.cat || ""}</div>
          <div class="wishlist-name">${p.name}</div>
          <div class="wishlist-price">${formatRupiah(p.price)}</div>
          <button class="btn-addcart" onclick="addToCartFromWishlist(${p.id}, this)">Add to Cart</button>
        </div>
      </div>
    </div>
  `;
}

function renderWishlist(){
  const grid = document.getElementById("wishlistGrid");
  const emptyState = document.getElementById("emptyState");

  if(wishlistItems.length === 0){
    grid.innerHTML = "";
    emptyState.style.display = "block";
    return;
  }
  emptyState.style.display = "none";
  grid.innerHTML = wishlistItems.map(wishlistCard).join("");
}

function removeWishlist(id){
  const item = wishlistItems.find(w => w.id === id);
  if(!item) return;

  fetch(`/wishlist/${id}`, {
    method: "DELETE",
    headers: {
      "X-CSRF-TOKEN": CSRF_TOKEN,
      "Accept": "application/json",
    },
  })
  .then(res => res.json())
  .then(() => {
    wishlistItems = wishlistItems.filter(w => w.id !== id);
    renderWishlist();
    showToast(`${item.name} dihapus dari wishlist`);
  })
  .catch(() => {
    showToast("Gagal menghapus, coba lagi.");
  });
}

function addToCartFromWishlist(id, btn){
  const product = wishlistItems.find(w => w.id === id);
  if(!product) return;

  let cart = getCart();
  const existing = cart.find(c => c.id === id);
  if(existing){
    existing.qty += 1;
  } else {
    cart.push({...product, qty:1});
  }
  saveCart(cart);
  updateCartUI();

  btn.classList.add("added");
  const original = btn.textContent;
  btn.textContent = "Ditambahkan ✓";
  setTimeout(()=>{ btn.classList.remove("added"); btn.textContent = original; }, 1200);
  showToast(`${product.name} ditambahkan ke keranjang`);
}

function updateCartUI(){
  const cart = getCart();
  const totalQty = cart.reduce((a,b)=>a+b.qty,0);
  document.getElementById("cartCount").textContent = totalQty;

  const list = document.getElementById("cartItemsList");
  const emptyState = document.getElementById("cartEmpty");
  const summary = document.getElementById("cartSummary");

  if(cart.length === 0){
    list.innerHTML = "";
    emptyState.style.display = "block";
    summary.style.display = "none";
    return;
  }

  emptyState.style.display = "none";
  summary.style.display = "block";

  list.innerHTML = cart.map(item => `
    <div class="cart-item">
      <div class="thumb"><i class="${item.icon}" style="color:var(--cool-gray)"></i></div>
      <div class="flex-grow-1">
        <div style="font-weight:700;font-size:14px;">${item.name}</div>
        <div style="font-size:13px;color:var(--cool-gray);">${formatRupiah(item.price)} &middot; x${item.qty}</div>
      </div>
    </div>
  `).join("");

  const total = cart.reduce((a,b)=> a + (b.price*b.qty), 0);
  document.getElementById("cartTotal").textContent = formatRupiah(total);
}

function showToast(msg){
  const toast = document.getElementById("toastAdd");
  document.getElementById("toastText").textContent = msg;
  toast.classList.add("show");
  setTimeout(()=> toast.classList.remove("show"), 2000);
}

renderWishlist();
updateCartUI();
</script>
</body>
</html>