<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('site.nav_shop') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/shop.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>

    <div class="nav-links d-none d-lg-flex">
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a>
      <a href="{{ route('shop') }}" class="active">{{ __('site.nav_shop') }}</a>
      <a href="{{ route('home') }}#categories">{{ __('site.nav_categories') }}</a>
      <a href="{{ route('contact') }}">{{ __('site.nav_contact') }}</a>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div class="search-box d-none d-md-flex">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--cool-gray)"></i>
        <input type="text" id="searchInput" placeholder="{{ __('site.search_placeholder') }}">
      </div>
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
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <span class="current">{{ __('site.nav_shop') }}</span>
    </div>
    <h1>{{ __('site.shop_title') }}</h1>
    <p>{{ __('site.shop_subtitle') }}</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">

    <div id="categoryFilterBar" class="mb-4"></div>

    <div class="shop-toolbar">
      <span class="result-count" id="resultCount"></span>

      <div class="sort-dropdown" id="sortDropdown">
        <button type="button" class="sort-toggle" id="sortToggle">
          <span id="sortToggleLabel">{{ __('site.sort_label') }} {{ __('site.sort_newest') }}</span>
          <i class="fa-solid fa-chevron-down"></i>
        </button>
        <ul class="sort-menu" id="sortMenu">
          <li data-value="default" class="active">{{ __('site.sort_label') }} {{ __('site.sort_newest') }}</li>
          <li data-value="price-asc">{{ __('site.sort_price_low') }}</li>
          <li data-value="price-desc">{{ __('site.sort_price_high') }}</li>
          <li data-value="name-asc">{{ __('site.sort_name_az') }}</li>
        </ul>
      </div>
    </div>

    <div class="row g-4" id="productGrid"></div>

    <div class="pagination-bar" id="paginationBar"></div>

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
      <a href="{{ route('checkout') }}" class="btn-addcart w-100 text-center" style="border-radius:999px;">{{ __('site.offcanvas_cart_checkout') }}</a>
    </div>
  </div>
</div>

<div class="toast-add" id="toastAdd">
  <i class="fa-solid fa-circle-check"></i>
  <span id="toastText">{{ __('site.toast_product_added_generic') }}</span>
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
  filterAll: @json(__('site.filter_all')),
  addToCart: @json(__('site.product_add_to_cart')),
  viewDetail: @json(__('site.product_view_detail')),
  notFound: @json(__('site.product_not_found')),
  addedToCart: @json(__('site.toast_added_to_cart')),
  resultCountZero: @json(__('site.shop_result_count_zero')),
  resultCountTemplate: @json(__('site.shop_result_count')),
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

// Catatan: `key` di bawah ini cuma identifier internal (dipakai untuk matching
// filter, tidak ditampilkan langsung) — label yang tampil ke user diambil dari
// CAT_LABEL/catLabel() di atas, yang sudah ikut berubah sesuai bahasa aktif.
// Nama produk (brand/merek, mis. "TRENDUP Chrono Black") tetap tidak
// diterjemahkan karena itu nama produk asli, bukan teks UI.
// Kategori & produk sekarang diambil dari database
// (dikirim dari ShopController lewat variabel $categoriesData, $productsData).
const categories = @json($categoriesData);
const products = @json($productsData);

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

let cart = [];
let activeFilter = I18N.filterAll;
let currentPage = 1;
const pageSize = 24;
let toastTimer = null;
let sortValue = "default";

function formatRupiah(num){
  return "Rp " + num.toLocaleString("id-ID");
}

function renderCategoryFilterBar(){
  const bar = document.getElementById("categoryFilterBar");
  const cats = [I18N.filterAll, ...categories.map(c=>c.key)];
  bar.innerHTML = cats.map(c => {
    const count = c === I18N.filterAll ? products.length : products.filter(p => p.cat === c).length;
    const label = c === I18N.filterAll ? c : catLabel(c);
    return `<button class="filter-pill ${activeFilter===c ? 'active' : ''}" onclick="setFilter('${c}')">${label} <span class="count">(${count})</span></button>`;
  }).join("");
}

function setFilter(cat){
  activeFilter = cat;
  currentPage = 1;
  renderCategoryFilterBar();
  renderProducts();
}

function getFilteredSorted(){
  const search = document.getElementById("searchInput").value.toLowerCase();

  let filtered = products.filter(p => {
    const matchCat = activeFilter === I18N.filterAll || p.cat === activeFilter;
    const matchSearch = p.name.toLowerCase().includes(search);
    return matchCat && matchSearch;
  });

  if(sortValue === "price-asc") filtered.sort((a,b) => a.price - b.price);
  if(sortValue === "price-desc") filtered.sort((a,b) => b.price - a.price);
  if(sortValue === "name-asc") filtered.sort((a,b) => a.name.localeCompare(b.name));

  return filtered;
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
    <div class="col-6 col-md-4 col-lg-3">
      <div class="product-card" style="position:relative;">
        <div class="product-img" style="cursor:pointer;" onclick="goToDetail(${p.id})">
          ${badgeHtml}
          ${imgHtml}
        </div>
        <button class="wishlist-toggle-btn ${wished ? 'active' : ''}" onclick="toggleWishlist(${p.id}, this)" title="Wishlist" style="position:absolute;top:10px;right:10px;width:36px;height:36px;border-radius:50%;border:none;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.15);z-index:2;">
          <i class="${wished ? 'fa-solid' : 'fa-regular'} fa-heart" style="color:${wished ? '#e0245e' : '#333'};"></i>
        </button>
        <div class="product-info">
          <div class="product-cat">${catLabel(p.cat)}</div>
          <div class="product-name" style="cursor:pointer;" onclick="goToDetail(${p.id})">${p.name}</div>
          <div class="rating-row">
            <span class="stars">${starsHtml(rating)}</span>
            <span class="rating-text">${rating.toFixed(1)}${p.reviewCount ? ` (${p.reviewCount})` : ''}</span>
          </div>
          <div class="product-price">${formatRupiah(p.price)}</div>
          <div class="d-flex gap-2 product-actions">
            <button class="btn-addcart" style="flex:1;width:auto;" onclick="goToDetail(${p.id})">${I18N.viewDetail}</button>
            <button class="btn-cart-icon" onclick="addToCart(${p.id})" title="${I18N.addToCart}">
              <i class="fa-solid fa-cart-shopping"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderProducts(){
  const filtered = getFilteredSorted();
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);

  const grid = document.getElementById("productGrid");
  grid.innerHTML = pageItems.length
    ? pageItems.map(productCard).join("")
    : `<div class="col-12"><div class="empty-result"><i class="fa-solid fa-magnifying-glass"></i>${I18N.notFound}</div></div>`;

  document.getElementById("resultCount").textContent =
    filtered.length === 0 ? I18N.resultCountZero : I18N.resultCountTemplate
      .replace(":from", start + 1)
      .replace(":to", Math.min(start + pageSize, filtered.length))
      .replace(":total", filtered.length);

  renderPagination(totalPages);
}

function renderPagination(totalPages){
  const bar = document.getElementById("paginationBar");
  if(totalPages <= 1){ bar.innerHTML = ""; return; }
  let html = `<button class="page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}><i class="fa-solid fa-chevron-left"></i></button>`;
  for(let i = 1; i <= totalPages; i++){
    html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="goToPage(${i})">${i}</button>`;
  }
  html += `<button class="page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}><i class="fa-solid fa-chevron-right"></i></button>`;
  bar.innerHTML = html;
}

function goToPage(page){
  currentPage = page;
  renderProducts();
  window.scrollTo({top:0, behavior:"smooth"});
}

function goToDetail(id){
  window.location.href = "{{ route('product.detail') }}?id=" + id;
}

function addToCart(id){
  const product = products.find(p => p.id === id);
  if(!product) return;

  const existing = cart.find(item => item.id === id);
  if(existing){
    existing.qty += 1;
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      price: product.price,
      icon: product.icon,
      img: product.img,
      qty: 1
    });
  }

  updateCartUI();
  showToast(`${product.name} ${I18N.addedToCart}`);
}

function showToast(message){
  const toast = document.getElementById("toastAdd");
  document.getElementById("toastText").textContent = message;
  toast.classList.add("show");

  if(toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.remove("show");
  }, 2500);
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
        <div style="font-size:13px;color:var(--cool-gray);display:flex;align-items:center;gap:10px;margin-top:6px;">
          <button class="qty-btn" onclick="changeQty(${item.id}, -1)">-</button>
          <span>${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
          <span style="margin-left:auto;">${formatRupiah(item.price * item.qty)}</span>
        </div>
      </div>
      <button class="qty-btn" style="border:none;color:var(--cool-gray);" onclick="removeFromCart(${item.id})"><i class="fa-solid fa-xmark"></i></button>
    </div>
  `).join("");

  const total = cart.reduce((a,b)=>a + (b.price*b.qty), 0);
  document.getElementById("cartTotal").textContent = formatRupiah(total);
}

function changeQty(id, delta){
  const item = cart.find(i => i.id === id);
  if(!item) return;
  item.qty += delta;
  if(item.qty <= 0){
    cart = cart.filter(i => i.id !== id);
  }
  updateCartUI();
}

function removeFromCart(id){
  cart = cart.filter(i => i.id !== id);
  updateCartUI();
}

document.getElementById("searchInput").addEventListener("input", () => { currentPage = 1; renderProducts(); });

const sortDropdown = document.getElementById("sortDropdown");
const sortToggle = document.getElementById("sortToggle");
const sortMenu = document.getElementById("sortMenu");
const sortToggleLabel = document.getElementById("sortToggleLabel");

sortToggle.addEventListener("click", () => {
  sortDropdown.classList.toggle("open");
});

sortMenu.querySelectorAll("li").forEach(li => {
  li.addEventListener("click", () => {
    sortValue = li.dataset.value;
    sortToggleLabel.textContent = li.textContent;
    sortMenu.querySelectorAll("li").forEach(el => el.classList.remove("active"));
    li.classList.add("active");
    sortDropdown.classList.remove("open");
    currentPage = 1;
    renderProducts();
  });
});

document.addEventListener("click", (e) => {
  if(!sortDropdown.contains(e.target)){
    sortDropdown.classList.remove("open");
  }
});

renderCategoryFilterBar();
renderProducts();
updateCartUI();
</script>
</body>
</html>