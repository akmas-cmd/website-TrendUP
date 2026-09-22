<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('site.track_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/tracking.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>
    <div class="d-flex align-items-center gap-3">
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
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <span class="current">{{ __('site.track_title') }}</span>
    </div>
    <h1>{{ __('site.track_title') }}</h1>
    <p>{{ __('site.track_subtitle') }}</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">

    <div class="tracking-form-card">
      <label class="form-label-trendup">{{ __('site.track_order_number_label') }}</label>
      <div class="tracking-input-row">
        <input type="text" class="form-control-trendup" id="orderNumberInput" placeholder="{{ __('site.track_placeholder_example') }}">
        <button class="btn-track" onclick="trackOrder()"><i class="fa-solid fa-magnifying-glass"></i> {{ __('site.track_btn') }}</button>
      </div>
      <p class="tracking-hint">{{ __('site.track_hint') }}</p>
      <div class="error-msg" id="trackError">{{ __('site.track_error') }}</div>
    </div>

    <div class="empty-state" id="emptyState">
      <i class="fa-solid fa-truck-fast"></i>
      {{ __('site.track_empty_hint') }}
    </div>

    <div class="result-wrap" id="resultWrap">

      <div class="order-summary-bar">
        <div>
          <div class="label">{{ __('site.track_order_number_label') }}</div>
          <div class="value" id="rOrderNumber"></div>
        </div>
        <div>
          <div class="label">{{ __('site.track_order_date_label') }}</div>
          <div class="value" id="rOrderDate"></div>
        </div>
        <div>
          <div class="label">{{ __('site.track_payment_label') }}</div>
          <div class="value" id="rPayment"></div>
        </div>
        <div>
          <div class="label">{{ __('site.track_status_label') }}</div>
          <span class="status-badge" id="rStatusBadge"></span>
        </div>
      </div>

      <div class="timeline" id="timelineWrap"></div>

      <div class="order-items-card">
        <h3>{{ __('site.track_items_title') }}</h3>
        <div id="rItems"></div>
      </div>

      <div class="address-card">
        <h3>{{ __('site.track_address_title') }}</h3>
        <div class="address-row"><i class="fa-solid fa-user"></i><span id="rName"></span></div>
        <div class="address-row"><i class="fa-solid fa-phone"></i><span id="rPhone"></span></div>
        <div class="address-row"><i class="fa-solid fa-location-dot"></i><span id="rAddress"></span></div>
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
        <a href="#">{{ __('site.footer_help_returns') }}</a>
      </div>
    </div>
    <div class="footer-bottom d-flex justify-content-between flex-wrap gap-2">
      <span>{{ __('site.footer_project_copyright') }}</span>
      <span>{{ __('site.footer_project_disclaimer') }}</span>
    </div>
  </div>
</footer>

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
// String terjemahan dikirim dari Blade supaya JS ikut ganti bahasa.
// Status & metode pembayaran memakai KODE KANONIS (bukan teks Indonesia) supaya
// labelnya ikut berubah saat bahasa antarmuka diganti.
const I18N = {
  status: {
    pending: @json(__('site.status_pending')),
    processing: @json(__('site.status_processing')),
    shipped: @json(__('site.status_shipped')),
    completed: @json(__('site.status_completed')),
    cancelled: @json(__('site.status_cancelled')),
  },
  statusDesc: {
    pending: @json(__('site.status_desc_pending')),
    processing: @json(__('site.status_desc_processing')),
    shipped: @json(__('site.status_desc_shipped')),
    completed: @json(__('site.status_desc_completed')),
  },
  payment: {
    bank_transfer: @json(__('site.payment_bank_transfer')),
    cod: @json(__('site.payment_cod')),
    ewallet: @json(__('site.payment_ewallet')),
  },
  colorLabels: {
    Hitam: @json(__('site.pd_color_black')),
    Lime: @json(__('site.pd_color_lime')),
    Putih: @json(__('site.pd_color_white')),
  },
  colorPrefix: @json(__('site.track_item_color_prefix')),
  sizePrefix: @json(__('site.track_item_size_prefix')),
  cancelledTitle: @json(__('site.track_status_cancelled_title')),
  cancelledFinal: @json(__('site.track_status_final')),
  cancelledDesc: @json(__('site.track_status_cancelled_desc')),
};

// Data pesanan sekarang dicari lewat AJAX ke TrackingController@search
// (endpoint /tracking/search), bukan objek dummy lagi.

const statusClassMap = {
  pending: "status-menunggu",
  processing: "status-diproses",
  shipped: "status-dikirim",
  completed: "status-selesai",
  cancelled: "status-dibatalkan",
};

const statusFlow = ["pending", "processing", "shipped", "completed"];

function formatRupiah(num){
  return "Rp " + num.toLocaleString("id-ID");
}

function itemMeta(it){
  const parts = [];
  if(it.color) parts.push(`${I18N.colorPrefix} ${I18N.colorLabels[it.color] || it.color}`);
  if(it.size) parts.push(`${I18N.sizePrefix} ${it.size}`);
  parts.push(`x${it.qty}`);
  return parts.join(" &middot; ");
}

function trackOrder(){
  const input = document.getElementById("orderNumberInput").value.trim().toUpperCase();
  const errorMsg = document.getElementById("trackError");
  const resultWrap = document.getElementById("resultWrap");
  const emptyState = document.getElementById("emptyState");
  const btn = document.querySelector(".btn-track");

  if(!input){
    errorMsg.classList.add("show");
    resultWrap.classList.remove("show");
    return;
  }

  const originalBtnHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

  fetch(`{{ route('tracking.search') }}?order_number=${encodeURIComponent(input)}`, {
    headers: { "Accept": "application/json" },
  })
  .then(res => res.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = originalBtnHtml;

    if(!data.found){
      errorMsg.classList.add("show");
      resultWrap.classList.remove("show");
      emptyState.style.display = "block";
      return;
    }

    const order = data.order;

    errorMsg.classList.remove("show");
    emptyState.style.display = "none";
    resultWrap.classList.add("show");

    document.getElementById("rOrderNumber").textContent = order.order_number;
    document.getElementById("rOrderDate").textContent = order.date;
    document.getElementById("rPayment").textContent = I18N.payment[order.payment] || order.payment;

    const badge = document.getElementById("rStatusBadge");
    badge.textContent = I18N.status[order.status] || order.status;
    badge.className = "status-badge " + statusClassMap[order.status];

    renderTimeline(order.status);

    document.getElementById("rItems").innerHTML = order.items.map(it => `
      <div class="order-item-row">
        <div class="order-item-thumb"><i class="${it.icon}"></i></div>
        <div>
          <div class="order-item-name">${it.name}</div>
          <div class="order-item-meta">${itemMeta(it)}</div>
        </div>
        <div class="order-item-price">${formatRupiah(it.price)}</div>
      </div>
    `).join("");

    document.getElementById("rName").textContent = order.name;
    document.getElementById("rPhone").textContent = order.phone;
    document.getElementById("rAddress").textContent = order.address;
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = originalBtnHtml;
    errorMsg.classList.add("show");
    resultWrap.classList.remove("show");
  });
}

function renderTimeline(currentStatus){
  const wrap = document.getElementById("timelineWrap");

  if(currentStatus === "cancelled"){
    wrap.innerHTML = `
      <div class="timeline-item cancelled">
        <div class="timeline-dot"><i class="fa-solid fa-xmark"></i></div>
        <div class="timeline-title">${I18N.cancelledTitle}</div>
        <div class="timeline-date">${I18N.cancelledFinal}</div>
        <div class="timeline-desc">${I18N.cancelledDesc}</div>
      </div>
    `;
    return;
  }

  const currentIndex = statusFlow.indexOf(currentStatus);

  wrap.innerHTML = statusFlow.map((step, i) => {
    let cls = "";
    let icon = i + 1;
    if(i < currentIndex){ cls = "done"; icon = '<i class="fa-solid fa-check"></i>'; }
    if(i === currentIndex){ cls = "current"; icon = '<i class="fa-solid fa-truck-fast"></i>'; }

    return `
      <div class="timeline-item ${cls}">
        <div class="timeline-dot">${icon}</div>
        <div class="timeline-title">${I18N.status[step]}</div>
        <div class="timeline-desc">${I18N.statusDesc[step]}</div>
      </div>
    `;
  }).join("");
}
</script>
</body>
</html>