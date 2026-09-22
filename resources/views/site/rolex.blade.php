<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} — {{ __('site.pd_title_suffix') }}</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/rolex.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>
    <div class="d-flex align-items-center gap-3">
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

<div class="breadcrumb-bar">
  <div class="container breadcrumb-trendup">
    <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <a href="{{ route('shop') }}">{{ __('site.nav_shop') }}</a> / <a href="{{ route('shop') }}">{{ __('site.cat_' . $product->category->key) }}</a> / <span class="current">{{ $product->name }}</span>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-6">
        <div class="gallery-main">
          @if ($product->badge)
            <span class="badge-product {{ $product->badge === 'NEW' ? 'badge-new' : 'badge-sale' }}">{{ $product->badge }}</span>
          @endif
          @if ($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" id="mainImage" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <i class="{{ $product->icon ?? 'fa-solid fa-box' }}" id="mainIcon" style="display:none;"></i>
          @else
            <i class="{{ $product->icon ?? 'fa-solid fa-box' }}" id="mainIcon"></i>
          @endif
        </div>
        <div class="gallery-thumbs">
          <div class="thumb-item active"><i class="{{ $product->icon ?? 'fa-solid fa-box' }}"></i></div>
        </div>
      </div>

      <div class="col-lg-6">
        <span class="product-category-tag">{{ __('site.cat_' . $product->category->key) }}</span>
        <h1 class="product-title">{{ $product->name }}</h1>

        <div class="rating-row">
          <span class="stars">
            @for ($i = 1; $i <= 5; $i++)
              @if ($avgRating >= $i)
                <i class="fa-solid fa-star"></i>
              @elseif ($avgRating >= $i - 0.5)
                <i class="fa-solid fa-star-half-stroke"></i>
              @else
                <i class="fa-regular fa-star"></i>
              @endif
            @endfor
          </span>
          <span class="rating-text">
            {{ $reviewCount > 0 ? number_format($avgRating, 1) : '0.0' }}
            ({{ $reviewCount }} {{ __('site.pd_reviews_suffix') }})
          </span>
        </div>

        <div class="price-row">
          <span class="price-now">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
        </div>

        @if ($product->stock > 0)
          <div class="stock-badge"><i class="fa-solid fa-circle"></i> {{ __('site.pd_stock_available') }} — {{ $product->stock }} pcs</div>
        @else
          <div class="stock-badge" style="background:#fde8e8;color:#c62828;"><i class="fa-solid fa-circle"></i> {{ __('site.pd_out_of_stock') }}</div>
        @endif

        <p class="product-desc">
          {{ $product->description ?: __('site.pd_desc_p1') }}
        </p>

        @php($variants = $product->sizes ? array_values(array_filter(array_map('trim', explode(',', $product->sizes)))) : [])
        @if (count($variants))
          <div class="option-group">
            <div class="option-label">
              <span>{{ __('site.pd_choose_variant') }}</span>
            </div>
            <div class="size-options">
              @foreach ($variants as $index => $variant)
                <button class="size-btn {{ $index === 0 ? 'active' : '' }}" data-color-id="{{ $variant }}">{{ $variant }}</button>
              @endforeach
            </div>
          </div>
        @endif

        <div class="option-group">
          <div class="option-label">
            <span>{{ __('site.pd_qty_label') }}</span>
            <span style="color:var(--cool-gray);font-weight:400;text-transform:none;">{{ __('site.pd_max') }} {{ $product->stock }} pcs</span>
          </div>
          <div class="qty-purchase">
            <button onclick="changeQty(-1)" {{ $product->stock <= 0 ? 'disabled' : '' }}>−</button>
            <span class="qty-num" id="qtyVal">1</span>
            <button onclick="changeQty(1)" {{ $product->stock <= 0 ? 'disabled' : '' }}>+</button>
          </div>
        </div>

        <div class="action-row">
          <button class="btn-addcart-main" id="addCartBtn" onclick="addToCart()" {{ $product->stock <= 0 ? 'disabled' : '' }}>
            <i class="fa-solid fa-bag-shopping"></i>
            {{ $product->stock <= 0 ? __('site.pd_out_of_stock') : __('site.pd_add_to_cart_btn') }}
            @if ($product->stock > 0)
              — <span id="totalPriceLabel">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @endif
          </button>
          <button class="btn-wishlist" onclick="toggleWishlist(this)"><i class="fa-regular fa-heart"></i></button>
        </div>

        <div class="trust-badges">
          <div class="trust-item"><i class="fa-solid fa-truck-fast"></i> {{ __('site.pd_shipping') }}</div>
          <div class="trust-item"><i class="fa-solid fa-rotate-left"></i> {{ __('site.pd_return') }}</div>
          <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> {{ __('site.pd_warranty') }}</div>
        </div>
      </div>
    </div>

    <div class="detail-tabs">
      <ul class="nav nav-tabs-trendup" id="detailTab" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">{{ __('site.pd_tab_desc') }}</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-spec">{{ __('site.pd_tab_spec') }}</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-review">{{ __('site.pd_tab_review') }}</button>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active tab-content-trendup" id="tab-desc">
          <p>{{ $product->description ?: __('site.pd_desc_p1') }}</p>
        </div>
        <div class="tab-pane fade" id="tab-spec">
          <table class="spec-table">
            <tr><td>{{ __('site.pd_spec_strap_material') }}</td><td>{{ __('site.pd_spec_strap_value') }}</td></tr>
            <tr><td>{{ __('site.pd_spec_water_resist') }}</td><td>{{ __('site.pd_spec_water_value') }}</td></tr>
            <tr><td>{{ __('site.pd_spec_display') }}</td><td>{{ __('site.pd_spec_display_value') }}</td></tr>
            <tr><td>{{ __('site.pd_spec_weight') }}</td><td>{{ __('site.pd_spec_weight_value') }}</td></tr>
            <tr><td>{{ __('site.pd_spec_warranty') }}</td><td>{{ __('site.pd_spec_warranty_value') }}</td></tr>
          </table>
        </div>
        <div class="tab-pane fade tab-content-trendup" id="tab-review">

          @if (session('review_success'))
            <div class="review-alert"><i class="fa-solid fa-circle-check"></i> {{ session('review_success') }}</div>
          @endif

          <div class="review-summary-box">
            <div>
              <div class="review-summary-score">{{ $reviewCount > 0 ? number_format($avgRating, 1) : '0.0' }}</div>
              <div class="review-summary-stars">
                @for ($i = 1; $i <= 5; $i++)
                  @if ($avgRating >= $i)
                    <i class="fa-solid fa-star"></i>
                  @elseif ($avgRating >= $i - 0.5)
                    <i class="fa-solid fa-star-half-stroke"></i>
                  @else
                    <i class="fa-regular fa-star"></i>
                  @endif
                @endfor
              </div>
              <div class="review-summary-count">{{ $reviewCount }} {{ __('site.pd_reviews_suffix') }}</div>
            </div>
          </div>

          <div class="review-list">
            @forelse ($reviews as $review)
              <div class="review-item">
                <div class="review-item-head">
                  <div class="review-author">
                    <div class="review-avatar">{{ $review->user ? $review->user->initials() : '?' }}</div>
                    <span class="review-author-name">{{ $review->user->name ?? __('site.pd_review_deleted_user') }}</span>
                  </div>
                  <span class="review-date">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="review-stars">
                  @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                  @endfor
                </div>
                @if ($review->comment)
                  <p class="review-comment">{{ $review->comment }}</p>
                @endif
                @php($reviewPhotos = $review->photo_paths)
                @php($reviewVideo = $review->video_path)
                @if (count($reviewPhotos) || $reviewVideo)
                  <div class="review-media">
                    @foreach ($reviewPhotos as $photo)
                      <button type="button" class="review-media-item" data-media-type="image" data-media-src="{{ $photo }}">
                        <img src="{{ $photo }}" alt="" loading="lazy">
                      </button>
                    @endforeach
                    @if ($reviewVideo)
                      <button type="button" class="review-media-item" data-media-type="video" data-media-src="{{ $reviewVideo }}">
                        <video src="{{ $reviewVideo }}#t=0.1" preload="metadata" muted playsinline></video>
                        <span class="review-media-play"><i class="fa-solid fa-play"></i></span>
                      </button>
                    @endif
                  </div>
                @endif
              </div>
            @empty
              <p class="review-empty">{{ __('site.pd_review_empty') }}</p>
            @endforelse
          </div>

          @auth
            <div class="review-form-box">
              <h5>{{ $myReview ? __('site.pd_review_edit_title') : __('site.pd_review_add_title') }}</h5>
              <form method="POST" action="{{ route('review.store', $product) }}" id="reviewForm" enctype="multipart/form-data">
                @csrf
                <div class="review-star-input">
                  @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating', $myReview->rating ?? 0) == $i ? 'checked' : '' }} required>
                    <label for="star{{ $i }}"><i class="fa-solid fa-star"></i></label>
                  @endfor
                </div>
                <textarea name="comment" placeholder="{{ __('site.pd_review_placeholder') }}">{{ old('comment', $myReview->comment ?? '') }}</textarea>

                <div class="review-media-upload">
                  <div class="review-media-label">{{ __('site.pd_review_media_title') }}</div>

                  @if ($myReview && !empty($myReview->media))
                    <div class="review-media-label sub">{{ __('site.pd_review_current_media') }}</div>
                    <div class="review-existing-list">
                      @foreach ($myReview->media as $item)
                        <label class="review-existing-item" data-type="{{ $item['type'] }}">
                          @if ($item['type'] === 'video')
                            <video src="{{ $item['path'] }}#t=0.1" preload="metadata" muted playsinline></video>
                            <span class="review-media-play"><i class="fa-solid fa-play"></i></span>
                          @else
                            <img src="{{ $item['path'] }}" alt="">
                          @endif
                          <span class="review-existing-remove">
                            <input type="checkbox" name="remove_media[]" value="{{ $item['path'] }}" @checked(in_array($item['path'], (array) old('remove_media', []), true))>
                            {{ __('site.pd_review_remove') }}
                          </span>
                        </label>
                      @endforeach
                    </div>
                  @endif

                  <div class="review-media-actions">
                    <label class="review-media-btn" for="reviewPhotos"><i class="fa-solid fa-camera"></i> {{ __('site.pd_review_add_photo') }}</label>
                    <label class="review-media-btn" for="reviewVideo"><i class="fa-solid fa-video"></i> {{ __('site.pd_review_add_video') }}</label>
                    <input type="file" class="review-file-input" id="reviewPhotos" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
                    <input type="file" class="review-file-input" id="reviewVideo" name="video" accept="video/mp4,video/webm,video/quicktime">
                  </div>

                  <div class="review-media-preview" id="reviewMediaPreview"></div>
                  <p class="review-media-hint">{{ __('site.pd_review_media_hint') }}</p>

                  @php($mediaErrors = collect($errors->get('photos'))->merge(collect($errors->get('photos.*'))->flatten())->merge($errors->get('video'))->unique()->values())
                  <div class="review-media-error" id="reviewMediaError" @if ($mediaErrors->isEmpty()) hidden @endif>{{ $mediaErrors->implode(' ') }}</div>
                </div>
                @error('rating')
                  <div class="text-danger mt-2" style="font-size:12px;">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn-addcart-main mt-3" id="reviewSubmitBtn" style="border-radius:999px;">
                  <i class="fa-solid fa-paper-plane"></i> {{ $myReview ? __('site.pd_review_update_btn') : __('site.pd_review_submit_btn') }}
                </button>
              </form>
            </div>
          @else
            <div class="review-login-prompt">
              {{ __('site.pd_review_login_prompt') }} <a href="{{ route('login') }}">{{ __('site.nav_login') }}</a>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </div>
</section>

<div class="review-lightbox" id="reviewLightbox" hidden>
  <button type="button" class="review-lightbox-close" id="reviewLightboxClose" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
  <div class="review-lightbox-body" id="reviewLightboxBody"></div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
  <div class="offcanvas-header" style="border-bottom:2px solid var(--jet);">
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
      <a href="{{ route('checkout') }}" class="btn-addcart-main w-100 justify-content-center" style="border-radius:999px;">{{ __('site.offcanvas_cart_checkout') }}</a>
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
// String terjemahan dikirim dari Blade supaya JS ikut ganti bahasa
const I18N = {
  addedBtn: @json(__('site.pd_added_to_cart_btn')),
  addedToCart: @json(__('site.toast_added_to_cart')),
  colorLabel: @json(__('site.pd_color_label')),
  variantLabel: @json(__('site.pd_variant_label')),
  wishlistAdded: @json(__('site.pd_wishlist_added')),
  wishlistRemoved: @json(__('site.pd_wishlist_removed')),
  colorLabels: {
    Hitam: @json(__('site.pd_color_black')),
    Lime: @json(__('site.pd_color_lime')),
    Putih: @json(__('site.pd_color_white')),
  },
};

const basePrice = {{ $product->price }};
let qty = 1;
const CART_KEY = "trendup_cart";
let cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];

function formatRupiah(num){ return "Rp " + num.toLocaleString("id-ID"); }

function changeQty(delta){
  qty = Math.max(1, Math.min(productStock, qty + delta));
  document.getElementById("qtyVal").textContent = qty;
  document.getElementById("totalPriceLabel").textContent = formatRupiah(basePrice * qty);
}

function addToCart(){
  // Varian disimpan pakai teks aslinya (data-color-id) supaya konsisten
  // walau bahasa antarmuka diganti - label yang ditampilkan tetap ikut terjemahan.
  const selectedColor = document.querySelector(".size-btn.active")?.dataset.colorId || null;
  const existing = cart.find(item => item.name === productName && item.color === selectedColor);
  if(existing){
    existing.qty += qty;
  } else {
    cart.push({
      id: productId,
      name: productName,
      color: selectedColor,
      price: basePrice,
      qty: qty,
      icon: productIcon,
      img: productImage
    });
  }
  updateCartUI();

  const btn = document.getElementById("addCartBtn");
  btn.classList.add("added");
  const original = btn.innerHTML;
  btn.innerHTML = `<i class="fa-solid fa-check"></i> ${I18N.addedBtn}`;
  setTimeout(()=>{ btn.classList.remove("added"); btn.innerHTML = original; }, 1400);
  showToast(`${qty} ${productName} ${I18N.addedToCart}`);
}

function changeCartQty(id, delta){
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

function updateCartUI(){
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
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
      <div class="thumb">${item.img
          ? `<img src="${item.img}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><i class="${item.icon}" style="display:none;color:var(--cool-gray)"></i>`
          : `<i class="${item.icon}" style="color:var(--cool-gray)"></i>`}</div>
      <div class="flex-grow-1">
        <div style="font-weight:700;font-size:14px;">${item.name}</div>
        <div style="font-size:13px;color:var(--cool-gray);">${item.color ? `${I18N.variantLabel} ${I18N.colorLabels[item.color] || item.color} &middot; ` : ""}${formatRupiah(item.price)}</div>
        <div class="d-flex align-items-center gap-2 mt-2">
          <button class="qty-btn-mini" onclick="changeCartQty(${item.id}, -1)">−</button>
          <span style="font-weight:700;">${item.qty}</span>
          <button class="qty-btn-mini" onclick="changeCartQty(${item.id}, 1)">+</button>
          <button class="ms-auto btn btn-sm text-danger" onclick="removeFromCart(${item.id})"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  `).join("");

  const total = cart.reduce((a,b)=> a + (b.price*b.qty), 0);
  document.getElementById("cartTotal").textContent = formatRupiah(total);
}

const productId = {{ $product->id }};
const productName = @json($product->name);
const productCat = @json(__('site.cat_' . $product->category->key));
const productIcon = @json($product->icon ?? 'fa-solid fa-box');
const productImage = @json($product->image ?? null);
const productStock = {{ $product->stock }};

function getWishlist(){
  return JSON.parse(localStorage.getItem("trendup_wishlist") || "[]");
}
function saveWishlist(list){
  localStorage.setItem("trendup_wishlist", JSON.stringify(list));
}

function toggleWishlist(btn){
  let wishlist = getWishlist();
  const exists = wishlist.find(w => w.id === productId);
  const icon = btn.querySelector("i");

  if(exists){
    wishlist = wishlist.filter(w => w.id !== productId);
    btn.classList.remove("active");
    icon.classList.remove("fa-solid");
    icon.classList.add("fa-regular");
    showToast(I18N.wishlistRemoved);
  } else {
    wishlist.push({
      id: productId,
      name: productName,
      price: basePrice,
      icon: productIcon,
      cat: productCat
    });
    btn.classList.add("active");
    icon.classList.remove("fa-regular");
    icon.classList.add("fa-solid");
    showToast(I18N.wishlistAdded);
  }
  saveWishlist(wishlist);
}

function initWishlistState(){
  const wishlist = getWishlist();
  const exists = wishlist.find(w => w.id === productId);
  if(exists){
    const btn = document.querySelector(".btn-wishlist");
    const icon = btn.querySelector("i");
    btn.classList.add("active");
    icon.classList.remove("fa-regular");
    icon.classList.add("fa-solid");
  }
}

document.querySelectorAll(".size-btn:not([disabled])").forEach(btn=>{
  btn.addEventListener("click", ()=>{
    document.querySelectorAll(".size-btn").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
  });
});

document.querySelectorAll(".thumb-item").forEach(thumb=>{
  thumb.addEventListener("click", ()=>{
    document.querySelectorAll(".thumb-item").forEach(t=>t.classList.remove("active"));
    thumb.classList.add("active");
    const icon = thumb.querySelector("i").className;
    document.getElementById("mainIcon").className = icon;
  });
});

function showToast(msg){
  const toast = document.getElementById("toastAdd");
  document.getElementById("toastText").textContent = msg;
  toast.classList.add("show");
  setTimeout(()=> toast.classList.remove("show"), 2000);
}

initWishlistState();
updateCartUI();

// Setelah submit ulasan, halaman di-redirect balik dengan #tab-review di URL
// supaya user langsung melihat tab Ulasan (bukan kembali ke tab Deskripsi).
if (window.location.hash === "#tab-review" || {{ $errors->any() ? 'true' : 'false' }}) {
  const reviewTabBtn = document.querySelector('[data-bs-target="#tab-review"]');
  if (reviewTabBtn) {
    new bootstrap.Tab(reviewTabBtn).show();
    reviewTabBtn.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}

// ===== FOTO & VIDEO ULASAN =====
(function(){
  const form = document.getElementById("reviewForm");
  if(!form) return;

  const photoInput = document.getElementById("reviewPhotos");
  const videoInput = document.getElementById("reviewVideo");
  const previewBox = document.getElementById("reviewMediaPreview");
  const errBox = document.getElementById("reviewMediaError");
  const submitBtn = document.getElementById("reviewSubmitBtn");

  @php($reviewMediaMsg = ['photosMax' => __('site.pd_review_err_photos_max'), 'photoType' => __('site.pd_review_err_photo_type'), 'photoSize' => __('site.pd_review_err_photo_size'), 'videoType' => __('site.pd_review_err_video_type'), 'videoSize' => __('site.pd_review_err_video_size'), 'total' => __('site.pd_review_err_total'), 'sending' => __('site.pd_review_sending')])
  const msg = @json($reviewMediaMsg);

  const MAX_PHOTOS = 5;
  const MAX_PHOTO_BYTES = 3 * 1024 * 1024;
  const MAX_VIDEO_BYTES = 20 * 1024 * 1024;
  const MAX_TOTAL_BYTES = 36 * 1024 * 1024;
  const PHOTO_TYPES = ["image/jpeg", "image/png", "image/webp"];
  const VIDEO_TYPES = ["video/mp4", "video/webm", "video/quicktime"];

  let photos = [];
  let video = null;
  let objectUrls = [];

  function showError(text){
    errBox.textContent = text || "";
    errBox.hidden = !text;
  }

  // Foto lama (mode edit) yang tidak dicentang "Hapus"
  function keptPhotoCount(){
    return form.querySelectorAll('.review-existing-item[data-type="image"] input[type="checkbox"]:not(:checked)').length;
  }

  function syncInputs(){
    const pt = new DataTransfer();
    photos.forEach(f => pt.items.add(f));
    photoInput.files = pt.files;

    const vt = new DataTransfer();
    if(video) vt.items.add(video);
    videoInput.files = vt.files;
  }

  function makeRemoveBtn(onClick){
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "review-preview-remove";
    btn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    btn.addEventListener("click", onClick);
    return btn;
  }

  function renderPreview(){
    objectUrls.forEach(u => URL.revokeObjectURL(u));
    objectUrls = [];
    previewBox.innerHTML = "";

    photos.forEach((file, i) => {
      const url = URL.createObjectURL(file);
      objectUrls.push(url);
      const item = document.createElement("div");
      item.className = "review-preview-item";
      const img = document.createElement("img");
      img.alt = "";
      img.src = url;
      item.appendChild(img);
      item.appendChild(makeRemoveBtn(() => { photos.splice(i, 1); refresh(); }));
      previewBox.appendChild(item);
    });

    if(video){
      const url = URL.createObjectURL(video);
      objectUrls.push(url);
      const item = document.createElement("div");
      item.className = "review-preview-item";
      const vid = document.createElement("video");
      vid.muted = true;
      vid.preload = "metadata";
      vid.src = url + "#t=0.1";
      item.appendChild(vid);
      const play = document.createElement("span");
      play.className = "review-media-play";
      play.innerHTML = '<i class="fa-solid fa-play"></i>';
      item.appendChild(play);
      item.appendChild(makeRemoveBtn(() => { video = null; refresh(); }));
      previewBox.appendChild(item);
    }
  }

  function refresh(){
    syncInputs();
    renderPreview();
  }

  photoInput.addEventListener("change", () => {
    showError("");
    Array.from(photoInput.files).forEach(file => {
      if(!PHOTO_TYPES.includes(file.type)){ showError(msg.photoType); return; }
      if(file.size > MAX_PHOTO_BYTES){ showError(msg.photoSize); return; }
      if(photos.length + keptPhotoCount() >= MAX_PHOTOS){ showError(msg.photosMax); return; }
      photos.push(file);
    });
    refresh();
  });

  videoInput.addEventListener("change", () => {
    showError("");
    const file = videoInput.files[0];
    if(file){
      if(!VIDEO_TYPES.includes(file.type)) showError(msg.videoType);
      else if(file.size > MAX_VIDEO_BYTES) showError(msg.videoSize);
      else video = file;
    }
    refresh();
  });

  form.addEventListener("submit", (e) => {
    if(photos.length + keptPhotoCount() > MAX_PHOTOS){
      e.preventDefault();
      showError(msg.photosMax);
      return;
    }
    const total = photos.reduce((sum, f) => sum + f.size, 0) + (video ? video.size : 0);
    if(total > MAX_TOTAL_BYTES){
      e.preventDefault();
      showError(msg.total);
      return;
    }
    if(submitBtn){
      submitBtn.dataset.label = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + msg.sending;
    }
  });

  // Tombol kembali di browser: aktifkan lagi tombol kirim
  window.addEventListener("pageshow", (e) => {
    if(e.persisted && submitBtn && submitBtn.dataset.label){
      submitBtn.disabled = false;
      submitBtn.innerHTML = submitBtn.dataset.label;
    }
  });
})();

// ===== LIGHTBOX FOTO/VIDEO ULASAN =====
(function(){
  const box = document.getElementById("reviewLightbox");
  const body = document.getElementById("reviewLightboxBody");
  const closeBtn = document.getElementById("reviewLightboxClose");
  if(!box) return;

  function openLightbox(type, src){
    body.innerHTML = "";
    let el;
    if(type === "video"){
      el = document.createElement("video");
      el.controls = true;
      el.autoplay = true;
      el.playsInline = true;
    } else {
      el = document.createElement("img");
      el.alt = "";
    }
    el.src = src;
    body.appendChild(el);
    box.hidden = false;
    document.body.style.overflow = "hidden";
  }

  function closeLightbox(){
    body.innerHTML = "";
    box.hidden = true;
    document.body.style.overflow = "";
  }

  document.querySelectorAll(".review-media-item").forEach(btn => {
    btn.addEventListener("click", () => openLightbox(btn.dataset.mediaType, btn.dataset.mediaSrc));
  });
  closeBtn.addEventListener("click", closeLightbox);
  box.addEventListener("click", (e) => { if(e.target === box || e.target === body) closeLightbox(); });
  document.addEventListener("keydown", (e) => { if(e.key === "Escape" && !box.hidden) closeLightbox(); });
})();
</script>
</body>
</html>