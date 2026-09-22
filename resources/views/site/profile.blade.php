<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('site.profile_page_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
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
        <a href="{{ route('login') }}" class="btn-login" style="text-decoration:none;">{{ __('site.nav_login') }}</a>
      @endguest

      @auth
        <div class="dropdown">
          <button class="btn-login" type="button" data-bs-toggle="dropdown" title="{{ auth()->user()->name }}">
            <i class="fa-solid fa-user"></i>
            <i class="fa-solid fa-chevron-down" style="font-size:10px;"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text text-muted" style="font-size:13px;">{{ auth()->user()->name }}</span></li>
            <li><hr class="dropdown-divider"></li>
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
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <span class="current">{{ __('site.breadcrumb_profile') }}</span>
    </div>
    <h1>{{ __('site.profile_heading') }}</h1>
    <p>{{ __('site.profile_subtitle') }}</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">

    @if (session('status') === 'profile-updated')
      <div class="alert-trendup show mb-4"><i class="fa-solid fa-circle-check"></i> {{ __('site.profile_updated_msg') }}</div>
    @elseif (session('status') === 'password-updated')
      <div class="alert-trendup show mb-4"><i class="fa-solid fa-circle-check"></i> {{ __('site.profile_password_updated_msg') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert-trendup show mb-4" style="background:#ffe1e1;color:#b00020;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
      </div>
    @endif

    <div class="row g-4">

      <!-- ===== SUMMARY CARD ===== -->
      <div class="col-lg-4">
        <div class="profile-summary-card">
          <div class="profile-avatar">{{ $user->initials() }}</div>
          <h3 class="profile-name">{{ $user->name }}</h3>
          <div class="profile-email">{{ $user->email }}</div>
          <span class="profile-role-badge {{ $user->isAdmin() ? 'is-admin' : '' }}">
            {{ $user->isAdmin() ? __('site.profile_role_admin') : __('site.profile_role_user') }}
          </span>

          <div class="profile-stats">
            <div class="stat-box">
              <div class="stat-value">{{ $orders->count() }}</div>
              <div class="stat-label">{{ __('site.profile_tab_orders') }}</div>
            </div>
            <div class="stat-box">
              <div class="stat-value">{{ $user->wishlists()->count() }}</div>
              <div class="stat-label">{{ __('site.wishlist_heading') }}</div>
            </div>
          </div>

          <div class="profile-since">
            <i class="fa-regular fa-calendar"></i>
            {{ __('site.profile_member_since', ['date' => $user->created_at->translatedFormat('d M Y')]) }}
          </div>
        </div>
      </div>

      <!-- ===== TABS ===== -->
      <div class="col-lg-8">
        <div class="profile-tabs-card">

          <ul class="nav profile-tab-nav" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="tab-account-btn" data-bs-toggle="tab" data-bs-target="#tab-account" type="button" role="tab">
                <i class="fa-regular fa-id-card"></i> {{ __('site.profile_tab_account') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-password-btn" data-bs-toggle="tab" data-bs-target="#tab-password" type="button" role="tab">
                <i class="fa-solid fa-lock"></i> {{ __('site.profile_tab_password') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-orders-btn" data-bs-toggle="tab" data-bs-target="#tab-orders" type="button" role="tab">
                <i class="fa-solid fa-bag-shopping"></i> {{ __('site.profile_tab_orders') }}
              </button>
            </li>
          </ul>

          <div class="tab-content" id="profileTabContent">

            <!-- ===== ACCOUNT TAB ===== -->
            <div class="tab-pane fade show active" id="tab-account" role="tabpanel">
              <p class="tab-desc">{{ __('site.profile_account_desc') }}</p>

              <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <label class="form-label-trendup">{{ __('site.profile_label_name') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-user leading-icon"></i>
                  <input type="text" name="name" class="form-control-trendup" value="{{ old('name', $user->name) }}">
                </div>

                <label class="form-label-trendup">{{ __('site.profile_label_email') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-envelope leading-icon"></i>
                  <input type="email" name="email" class="form-control-trendup" value="{{ old('email', $user->email) }}">
                </div>

                <label class="form-label-trendup">{{ __('site.profile_label_phone') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-phone leading-icon"></i>
                  <input type="text" name="phone" class="form-control-trendup" value="{{ old('phone', $user->phone) }}" placeholder="{{ __('site.profile_placeholder_phone') }}">
                </div>

                <button type="submit" class="btn-submit">
                  {{ __('site.profile_btn_save') }} <i class="fa-solid fa-check"></i>
                </button>
              </form>
            </div>

            <!-- ===== PASSWORD TAB ===== -->
            <div class="tab-pane fade" id="tab-password" role="tabpanel">
              <p class="tab-desc">{{ __('site.profile_password_desc') }}</p>

              <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <label class="form-label-trendup">{{ __('site.profile_label_current_password') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-lock leading-icon"></i>
                  <input type="password" name="current_password" class="form-control-trendup" id="curPassword">
                  <button type="button" class="toggle-pass" onclick="togglePassword('curPassword', this)"><i class="fa-regular fa-eye"></i></button>
                </div>

                <label class="form-label-trendup">{{ __('site.profile_label_new_password') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-lock leading-icon"></i>
                  <input type="password" name="password" class="form-control-trendup" id="newPassword">
                  <button type="button" class="toggle-pass" onclick="togglePassword('newPassword', this)"><i class="fa-regular fa-eye"></i></button>
                </div>

                <label class="form-label-trendup">{{ __('site.profile_label_confirm_new_password') }}</label>
                <div class="input-wrap">
                  <i class="fa-solid fa-lock leading-icon"></i>
                  <input type="password" name="password_confirmation" class="form-control-trendup" id="newPasswordConfirm">
                  <button type="button" class="toggle-pass" onclick="togglePassword('newPasswordConfirm', this)"><i class="fa-regular fa-eye"></i></button>
                </div>

                <button type="submit" class="btn-submit">
                  {{ __('site.profile_btn_update_password') }} <i class="fa-solid fa-key"></i>
                </button>
              </form>
            </div>

            <!-- ===== ORDERS TAB ===== -->
            <div class="tab-pane fade" id="tab-orders" role="tabpanel">
              <p class="tab-desc">{{ __('site.profile_orders_desc') }}</p>

              @if ($orders->isEmpty())
                <div class="orders-empty-state">
                  <i class="fa-solid fa-box-open"></i>
                  <h4>{{ __('site.profile_orders_empty_title') }}</h4>
                  <p>{{ __('site.profile_orders_empty_desc') }}</p>
                  <a href="{{ route('shop') }}" class="btn-shop">{{ __('site.btn_shop_now') }}</a>
                </div>
              @else
                <div class="table-responsive">
                  <table class="table orders-table align-middle">
                    <thead>
                      <tr>
                        <th>{{ __('site.profile_th_order_number') }}</th>
                        <th>{{ __('site.profile_th_date') }}</th>
                        <th>{{ __('site.profile_th_items') }}</th>
                        <th>{{ __('site.profile_th_total') }}</th>
                        <th>{{ __('site.profile_th_status') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td class="fw-bold">{{ $order->order_number }}</td>
                          <td>{{ $order->created_at->translatedFormat('d M Y') }}</td>
                          <td>{{ __('site.profile_items_count', ['count' => $order->items_count]) }}</td>
                          <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                          <td>
                            <span class="status-badge status-{{ $order->status }}">
                              {{ __('site.tracking_status_'.$order->status) }}
                            </span>
                          </td>
                          <td class="text-end">
                            <a href="{{ route('checkout.success', $order->order_number) }}" class="btn-order-detail">
                              {{ __('site.profile_btn_track') }}
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>

          </div>
        </div>
      </div>

    </div>
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

// ===== SHOW/HIDE PASSWORD =====
function togglePassword(inputId, btn){
  const input = document.getElementById(inputId);
  const icon = btn.querySelector("i");
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

// Kalau ada error validasi dari form password, otomatis buka tab password
@if ($errors->has('current_password') || $errors->has('password'))
  document.addEventListener("DOMContentLoaded", () => {
    const trigger = document.getElementById("tab-password-btn");
    if (trigger) new bootstrap.Tab(trigger).show();
  });
@endif

// Buka tab "Riwayat Pesanan" otomatis kalau diakses lewat link #tab-orders
// (misalnya dari tombol "Riwayat Pesanan" di halaman Pesanan Berhasil)
if (window.location.hash === "#tab-orders") {
  document.addEventListener("DOMContentLoaded", () => {
    const trigger = document.getElementById("tab-orders-btn");
    if (trigger) new bootstrap.Tab(trigger).show();
  });
}

// ===== MINI CART (localStorage, sama seperti halaman lain) =====
function formatRupiah(num){ return "Rp " + num.toLocaleString("id-ID"); }
function getCart(){ return JSON.parse(localStorage.getItem("trendup_cart") || "[]"); }

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

updateCartUI();
</script>
</body>
</html>