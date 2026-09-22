<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>TRENDUP — Upgrade Your Style.</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/beranda.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="#" class="brand-logo">TRENDUP</a>

    <div class="nav-links d-none d-lg-flex">
      <a href="#home">{{ __('site.nav_home') }}</a>
      <a href="{{ route('shop') }}">{{ __('site.nav_shop') }}</a>
      <a href="#categories">{{ __('site.nav_categories') }}</a>
      <a href="{{ route('contact') }}">{{ __('site.nav_contact') }}</a>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div class="search-box d-none d-md-flex">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--cool-gray)"></i>
        <input type="text" id="searchInput" placeholder="{{ __('site.search_placeholder') }}">
      </div>
      <a href="{{ route('wishlist') }}" class="icon-btn" style="text-decoration:none;">
        <i class="fa-solid fa-heart"></i>
      </a>
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

<header class="hero" id="home">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1>{{ __('site.hero_title_1') }}<br>{{ __('site.hero_title_2') }} <span>{{ __('site.hero_title_3') }}</span></h1>
        <p>{{ __('site.hero_desc') }}</p>
        <a href="{{ route('shop') }}" class="btn-shopnow">{{ __('site.hero_shopnow') }} <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div class="hero-img-wrap">
          <img src="{{ asset('images/trend.png') }}" alt="TRENDUP Hero" onerror="this.parentElement.style.display='none';">
        </div>
      </div>
    </div>
    <div class="hero-marquee">
      <div class="marquee-track">
        <span class="on">{{ __('site.cat_jam_tangan') }}</span><span>{{ __('site.cat_jam_dinding') }}</span><span class="on">{{ __('site.cat_jam_beker') }}</span><span>{{ __('site.cat_baju') }}</span><span class="on">{{ __('site.cat_sepatu') }}</span><span>{{ __('site.cat_celana') }}</span>
        <span class="on">{{ __('site.cat_jam_tangan') }}</span><span>{{ __('site.cat_jam_dinding') }}</span><span class="on">{{ __('site.cat_jam_beker') }}</span><span>{{ __('site.cat_baju') }}</span><span class="on">{{ __('site.cat_sepatu') }}</span><span>{{ __('site.cat_celana') }}</span>
      </div>
    </div>
  </div>
</header>

<section class="section-pad" id="categories">
  <div class="container">
    <span class="eyebrow">{{ __('site.eyebrow_categories') }}</span>
    <h2 class="section-title">{{ __('site.section_categories_title') }}</h2>
    <div class="row g-3" id="categoryGrid"></div>
  </div>
</section>

<section class="section-pad" style="background:var(--soft-gray)" id="shop">
  <div class="container">
    <span class="eyebrow">{{ __('site.eyebrow_trending') }}</span>
    <h2 class="section-title">{{ __('site.section_new_title') }}</h2>

    <div class="mb-4" id="filterBar"></div>

    <div class="row g-4" id="productGrid"></div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <span class="eyebrow">{{ __('site.eyebrow_bestseller') }}</span>
    <h2 class="section-title">{{ __('site.section_bestseller_title') }}</h2>
    <div class="row g-4" id="bestsellerGrid"></div>
  </div>
</section>

<section class="section-pad pt-0">
  <div class="container">
    <div class="promo-banner">
      <div class="slash"></div>
      <h2>{{ __('site.promo_title') }}</h2>
      <p>{{ __('site.promo_desc') }}</p>
      <a href="{{ route('register') }}" class="btn-promo">{{ __('site.promo_btn') }}</a>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-logo">TREND<span>UP</span></div>
        <p style="color:var(--cool-gray);font-size:14px;margin-top:14px;">{{ __('site.footer_desc') }}</p>
        <div class="mt-3">
          <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
          <a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_shop_title') }}</h6>
        <a href="#">{{ __('site.cat_jam_tangan') }}</a>
        <a href="#">{{ __('site.cat_sepatu') }}</a>
        <a href="#">{{ __('site.cat_baju') }}</a>
        <a href="#">{{ __('site.cat_celana') }}</a>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_help_title') }}</h6>
        <a href="#">{{ __('site.footer_help_howto') }}</a>
        <a href="{{ route('tracking') }}">{{ __('site.footer_help_tracking') }}</a>
        <a href="#">{{ __('site.footer_help_returns') }}</a>
      </div>
      <div class="col-lg-4">
        <h6>{{ __('site.footer_newsletter_title') }}</h6>
        @if (session('newsletter_success'))
          <div class="mb-2" style="color:#9fe870;font-size:13px;">{{ session('newsletter_success') }}</div>
        @endif
        @error('email')
          <div class="mb-2" style="color:#ff8080;font-size:13px;">{{ $message }}</div>
        @enderror
        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="d-flex gap-2">
          @csrf
          <input type="email" name="email" class="form-control" placeholder="{{ __('site.footer_newsletter_placeholder') }}" style="border-radius:999px;border:2px solid #333;background:transparent;color:#fff;" required>
          <button type="submit" class="btn-login" style="border:none;">{{ __('site.footer_newsletter_btn') }}</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom d-flex justify-content-between flex-wrap gap-2">
      <span>{{ __('site.footer_copyright') }}</span>
      <span>{{ __('site.footer_address') }}</span>
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
      <a href="{{ route('checkout') }}" class="btn-shopnow w-100 justify-content-center" style="border-radius:999px;">{{ __('site.offcanvas_cart_checkout') }}</a>
    </div>
  </div>
</div>

<div class="toast-add" id="toastAdd">
  <i class="fa-solid fa-circle-check"></i>
  <span id="toastText">{{ __('site.product_add_to_cart') }}</span>
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
// String terjemahan dari Blade untuk dipakai JS
const I18N = {
  addToCart: @json(__('site.product_add_to_cart')),
  viewDetail: @json(__('site.product_view_detail')),
  added: @json(__('site.product_added')),
  notFound: @json(__('site.product_not_found')),
  addedToCart: @json(__('site.toast_added_to_cart')),
  filterAll: @json(__('site.filter_all')),
};

// Peta terjemahan nama kategori (EN/ID), diambil dari file bahasa yang sama
// dengan I18N di atas. Nama produk (brand/merek) tetap tidak diterjemahkan.
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

const CART_KEY = "trendup_cart";

// Catatan: `key` di bawah ini cuma identifier internal (dipakai untuk matching
// filter, tidak ditampilkan langsung) — label yang tampil ke user diambil dari
// CAT_LABEL/catLabel() di atas, yang sudah ikut berubah sesuai bahasa aktif.
// Nama produk (brand/merek, mis. "ROLEX", "ADIDAS LONDON") tetap tidak
// diterjemahkan karena itu nama produk asli, bukan teks UI.
// Kategori, produk, dan bestseller sekarang diambil dari database
// (dikirim dari HomeController lewat variabel $categoriesData, $productsData, $bestsellersData).
const categories = @json($categoriesData);
const products = @json($productsData);
const bestsellers = @json($bestsellersData);

// Wishlist: daftar id produk yang sudah di-wishlist user yang sedang login.
const wishlistIds = @json($wishlistIds);
const IS_LOGGED_IN = @json(auth()->check());
const LOGIN_URL = "{{ route('login') }}";
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

function toggleWishlist(id, btn){
  if(!IS_LOGGED_IN){
    window.location.href = LOGIN_URL;
    return;
  }

  const icon = btn.querySelector("i");
  const isActive = btn.classList.contains("active");
  const method = isActive ? "DELETE" : "POST";

  fetch(`/wishlist/${id}`, {
    method: method,
    headers: {
      "X-CSRF-TOKEN": CSRF_TOKEN,
      "Accept": "application/json",
    },
  })
  .then(res => res.json())
  .then(() => {
    if(isActive){
      btn.classList.remove("active");
      icon.classList.remove("fa-solid");
      icon.classList.add("fa-regular");
      icon.style.color = "#333";
    } else {
      btn.classList.add("active");
      icon.classList.remove("fa-regular");
      icon.classList.add("fa-solid");
      icon.style.color = "#e0245e";
    }
  })
  .catch(() => {
    showToast("Gagal memperbarui wishlist, coba lagi.");
  });
}

// Load cart dari localStorage supaya tidak hilang saat pindah/reload halaman
let cart = [];
try {
  cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
} catch(e) {
  cart = [];
}

function saveCart(){
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function formatRupiah(num){
  return "Rp " + num.toLocaleString("id-ID");
}

function renderCategories(){
  const grid = document.getElementById("categoryGrid");
  grid.innerHTML = categories.map(c => `
    <div class="col-6 col-md-4 col-lg-2">
      <div class="cat-card" onclick="filterByCategory('${c.key}')">
        <i class="${c.icon} cat-icon"></i>
        <div class="cat-name">${catLabel(c.key)}</div>
      </div>
    </div>
  `).join("");
}

let activeFilter = I18N.filterAll;
function renderFilterBar(){
  const bar = document.getElementById("filterBar");
  const cats = [I18N.filterAll, ...categories.map(c=>c.key)];
  bar.innerHTML = cats.map(c => `
    <button class="filter-pill ${activeFilter===c ? 'active' : ''}" onclick="setFilter('${c}')">${c===I18N.filterAll ? c : catLabel(c)}</button>
  `).join("");
}
function setFilter(cat){
  activeFilter = cat;
  renderFilterBar();
  renderProducts();
}
function filterByCategory(cat){
  setFilter(cat);
  document.getElementById("shop").scrollIntoView({behavior:"smooth"});
}

function starsHtml(rating){
  let html = "";
  for(let i = 1; i <= 5; i++){
    if(rating >= i){
      html += '<i class="fa-solid fa-star"></i>';
    } else if(rating >= i - 0.5){
      html += '<i class="fa-solid fa-star-half-stroke"></i>';
    } else {
      html += '<i class="fa-regular fa-star"></i>';
    }
  }
  return html;
}

function productCard(p){
  const badgeHtml = p.badge ? `<span class="badge-product ${p.badge==='NEW'?'badge-new':'badge-sale'}">${p.badge}</span>` : "";
  const imgHtml = p.img
    ? `<img src="${p.img}" alt="${p.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
       <i class="${p.icon}" style="display:none;"></i>`
    : `<i class="${p.icon}"></i>`;
  const wished = wishlistIds.includes(p.id);
  const rating = p.rating || 0;
  return `
    <div class="col-6 col-md-4 col-lg-3 product-item" data-name="${p.name.toLowerCase()}" data-cat="${p.cat}">
      <div class="product-card" style="position:relative;">
        <div class="product-img" onclick="goToDetail(${p.id})">
          ${badgeHtml}
          ${imgHtml}
        </div>
        <button class="wishlist-toggle-btn ${wished ? 'active' : ''}" onclick="toggleWishlist(${p.id}, this)" title="Wishlist" style="position:absolute;top:10px;right:10px;width:36px;height:36px;border-radius:50%;border:none;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.15);z-index:2;">
          <i class="${wished ? 'fa-solid' : 'fa-regular'} fa-heart" style="color:${wished ? '#e0245e' : '#333'};"></i>
        </button>
        <div class="product-info">
          <div class="product-cat">${catLabel(p.cat)}</div>
          <div class="product-name" onclick="goToDetail(${p.id})">${p.name}</div>
          <div class="rating-row">
            <span class="stars">${starsHtml(rating)}</span>
            <span class="rating-text">${rating.toFixed(1)}${p.reviewCount ? ` (${p.reviewCount})` : ''}</span>
          </div>
          <div class="product-price">${formatRupiah(p.price)}</div>
          <div class="d-flex gap-2 product-actions">
            <button class="btn-addcart" style="flex:1;width:auto;" onclick="goToDetail(${p.id})">${I18N.viewDetail}</button>
            <button class="btn-cart-icon" onclick="addToCart(${p.id}, this)" title="${I18N.addToCart}">
              <i class="fa-solid fa-cart-shopping"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderProducts(){
  const grid = document.getElementById("productGrid");
  const searchVal = document.getElementById("searchInput").value.toLowerCase();
  let filtered = products.filter(p => {
    const matchCat = activeFilter === I18N.filterAll || p.cat === activeFilter;
    const matchSearch = p.name.toLowerCase().includes(searchVal);
    return matchCat && matchSearch;
  });
  grid.innerHTML = filtered.length ? filtered.map(productCard).join("") :
    `<div class="col-12 text-center py-5 text-muted">${I18N.notFound}</div>`;
}

function renderBestsellers(){
  document.getElementById("bestsellerGrid").innerHTML = bestsellers.map(productCard).join("");
}

function goToDetail(id){
  window.location.href = "{{ route('product.detail') }}?id=" + id;
}

function addToCart(id, btn){
  const product = products.find(p => p.id === id) || bestsellers.find(p => p.id === id);
  const existing = cart.find(item => item.id === id);
  if(existing){
    existing.qty += 1;
  } else {
    cart.push({...product, qty:1});
  }
  saveCart();
  updateCartUI();
  showToast(`${product.name} ${I18N.addedToCart}`);

  if(btn){
    const originalHtml = btn.innerHTML;
    btn.classList.add("added");
    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
    setTimeout(()=>{ btn.classList.remove("added"); btn.innerHTML = originalHtml; }, 1200);
  }
}

function changeQty(id, delta){
  const item = cart.find(i => i.id === id);
  if(!item) return;
  item.qty += delta;
  if(item.qty <= 0){
    cart = cart.filter(i => i.id !== id);
  }
  saveCart();
  updateCartUI();
}

function removeFromCart(id){
  cart = cart.filter(i => i.id !== id);
  saveCart();
  updateCartUI();
}

function updateCartUI(){
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
      <div class="thumb">
        ${item.img
          ? `<img src="${item.img}" alt="${item.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><i class="${item.icon}" style="display:none;color:var(--cool-gray)"></i>`
          : `<i class="${item.icon}" style="color:var(--cool-gray)"></i>`}
      </div>
      <div class="flex-grow-1">
        <div style="font-weight:700;font-size:14px;">${item.name}</div>
        <div style="font-size:13px;color:var(--cool-gray);">${formatRupiah(item.price)}</div>
        <div class="d-flex align-items-center gap-2 mt-2">
          <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
          <span style="font-weight:700;">${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
          <button class="ms-auto btn btn-sm text-danger" onclick="removeFromCart(${item.id})"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  `).join("");

  const total = cart.reduce((a,b)=>a + (b.price*b.qty), 0);
  document.getElementById("cartTotal").textContent = formatRupiah(total);
}

function showToast(msg){
  const toast = document.getElementById("toastAdd");
  document.getElementById("toastText").textContent = msg;
  toast.classList.add("show");
  setTimeout(()=> toast.classList.remove("show"), 2000);
}

// Sinkronkan cart antar tab/halaman yang terbuka bersamaan
window.addEventListener("storage", (e) => {
  if(e.key === CART_KEY){
    try {
      cart = JSON.parse(e.newValue) || [];
    } catch(err) {
      cart = [];
    }
    updateCartUI();
  }
});

document.getElementById("searchInput").addEventListener("input", renderProducts);

renderCategories();
renderFilterBar();
renderProducts();
renderBestsellers();
updateCartUI();
</script>
</body>
</html>