<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('site.checkout_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar-trendup">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a href="{{ route('home') }}" class="brand-logo">TRENDUP</a>
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
  </div>
</nav>

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
  <div class="container">
    <div class="breadcrumb-trendup">
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <a href="#">{{ __('site.breadcrumb_cart') }}</a> / <span class="current">{{ __('site.checkout_title') }}</span>
    </div>
    <h1>{{ __('site.checkout_title') }}</h1>
    <div class="stepper">
      <div class="step done"><span class="num"><i class="fa-solid fa-check"></i></span> {{ __('site.step_cart') }}</div>
      <div class="step-divider"></div>
      <div class="step active"><span class="num">2</span> {{ __('site.step_checkout') }}</div>
      <div class="step-divider"></div>
      <div class="step"><span class="num">3</span> {{ __('site.step_done') }}</div>
    </div>
  </div>
</div>

<!-- ===== CHECKOUT FORM ===== -->
<section class="section-pad">
  <div class="container">
    <div class="row g-4">

      <div class="col-lg-7">
        <form id="checkoutForm" novalidate>

          <!-- DATA PEMBELI -->
          <div class="form-card">
            <h3><span class="step-num">1</span> {{ __('site.checkout_buyer_data') }}</h3>

            <div class="mb-field">
              <label class="form-label-trendup">{{ __('site.label_fullname') }}</label>
              <input type="text" class="form-control-trendup" id="buyerName" value="{{ $buyer->name }}" placeholder="{{ __('site.placeholder_fullname') }}">
              <div class="error-msg" id="nameError">{{ __('site.err_name_required') }}</div>
            </div>

            <div class="form-row">
              <div>
                <label class="form-label-trendup">{{ __('site.label_phone') }}</label>
                <input type="text" class="form-control-trendup" id="buyerPhone" value="{{ $buyer->phone }}" placeholder="08xxxxxxxxxx">
                <div class="error-msg" id="phoneError">{{ __('site.err_phone_short') }}</div>
              </div>
              <div>
                <label class="form-label-trendup">{{ __('site.label_email') }}</label>
                <input type="email" class="form-control-trendup" id="buyerEmail" value="{{ $buyer->email }}" placeholder="nama@email.com">
                <div class="error-msg" id="emailError">{{ __('site.err_email_invalid') }}</div>
              </div>
            </div>

            <div class="mb-field">
              <label class="form-label-trendup">{{ __('site.label_address') }}</label>
              <textarea class="form-control-trendup" id="buyerAddress" rows="3" placeholder="{{ __('site.placeholder_address') }}">{{ $buyer->address }}</textarea>
              <div class="error-msg" id="addressError">{{ __('site.err_address_required') }}</div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
              <div>
                <label class="form-label-trendup">{{ __('site.label_city') }}</label>
                <input type="text" class="form-control-trendup" id="buyerCity" placeholder="{{ __('site.placeholder_city') }}">
                <div class="error-msg" id="cityError">{{ __('site.err_city_required') }}</div>
              </div>
              <div>
                <label class="form-label-trendup">{{ __('site.label_zip') }}</label>
                <input type="text" class="form-control-trendup" id="buyerZip" placeholder="{{ __('site.placeholder_zip') }}">
                <div class="error-msg" id="zipError">{{ __('site.err_zip_required') }}</div>
              </div>
            </div>
          </div>

          <!-- METODE PEMBAYARAN -->
          <div class="form-card">
            <h3><span class="step-num">2</span> {{ __('site.checkout_payment_method') }}</h3>

            <div class="payment-option selected" data-value="Transfer Bank" onclick="selectPayment(this)">
              <div class="radio-dot"></div>
              <i class="fa-solid fa-building-columns"></i>
              <div>
                <div class="p-title">{{ __('site.pay_bank_transfer') }}</div>
                <div class="p-sub">{{ __('site.pay_bank_transfer_sub') }}</div>
              </div>
            </div>

            <div class="payment-option" data-value="E-Wallet" onclick="selectPayment(this)">
              <div class="radio-dot"></div>
              <i class="fa-solid fa-wallet"></i>
              <div>
                <div class="p-title">{{ __('site.pay_ewallet') }}</div>
                <div class="p-sub">{{ __('site.pay_ewallet_sub') }}</div>
              </div>
            </div>

            <div class="payment-option" data-value="COD" onclick="selectPayment(this)">
              <div class="radio-dot"></div>
              <i class="fa-solid fa-truck"></i>
              <div>
                <div class="p-title">{{ __('site.pay_cod') }}</div>
                <div class="p-sub">{{ __('site.pay_cod_sub') }}</div>
              </div>
            </div>
          </div>

        </form>
      </div>

      <!-- RINGKASAN PESANAN -->
      <div class="col-lg-5">
        <div class="summary-card">
          <h3>{{ __('site.checkout_summary_title') }}</h3>
          <div id="summaryItems"></div>

          <div class="summary-row">
            <span>{{ __('site.summary_subtotal') }}</span>
            <span id="subtotalVal">Rp 0</span>
          </div>
          <div class="summary-row">
            <span>{{ __('site.summary_shipping') }}</span>
            <span id="shippingVal">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
          </div>
          <div class="summary-row total">
            <span>{{ __('site.checkout_total') }}</span>
            <span id="totalVal">Rp 0</span>
          </div>

          <button class="btn-confirm" id="confirmBtn" onclick="submitOrder()">
            {{ __('site.checkout_confirm_btn') }} <i class="fa-solid fa-arrow-right"></i>
          </button>
          <div class="secure-note"><i class="fa-solid fa-lock"></i> {{ __('site.checkout_secure_note') }}</div>
        </div>
      </div>

    </div>
  </div>
</section>

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
const I18N = {
  processing: @json(__('site.checkout_processing')),
  confirmBtn: @json(__('site.checkout_confirm_btn')),
};

const SHIPPING_FEE = {{ $shippingFee }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

// Keranjang asli, dibaca dari localStorage (diisi lewat tombol "Add to Cart"
// di halaman beranda/shop/produk).
const cart = JSON.parse(localStorage.getItem("trendup_cart") || "[]");

let selectedPaymentMethod = "Transfer Bank";

function formatRupiah(num){ return "Rp " + num.toLocaleString("id-ID"); }

function renderSummary(){
  const container = document.getElementById("summaryItems");

  if(cart.length === 0){
    container.innerHTML = `<p style="color:var(--cool-gray);">Keranjang kamu masih kosong. <a href="{{ route('shop') }}">Belanja dulu yuk</a>.</p>`;
    document.getElementById("confirmBtn").disabled = true;
  }

  container.innerHTML += cart.map(item => `
    <div class="summary-item">
      <div class="thumb"><i class="${item.icon}"></i></div>
      <div>
        <div class="name">${item.name}</div>
        <div class="meta">x${item.qty}</div>
      </div>
      <div class="price">${formatRupiah(item.price * item.qty)}</div>
    </div>
  `).join("");

  const subtotal = cart.reduce((a,b)=> a + (b.price*b.qty), 0);
  const total = cart.length ? subtotal + SHIPPING_FEE : 0;
  document.getElementById("subtotalVal").textContent = formatRupiah(subtotal);
  document.getElementById("shippingVal").textContent = formatRupiah(cart.length ? SHIPPING_FEE : 0);
  document.getElementById("totalVal").textContent = formatRupiah(total);
}

function selectPayment(el){
  document.querySelectorAll(".payment-option").forEach(o => o.classList.remove("selected"));
  el.classList.add("selected");
  selectedPaymentMethod = el.dataset.value;
}

function isValidEmail(email){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
function isValidPhone(phone){ return /^[0-9]{10,14}$/.test(phone); }

function submitOrder(){
  if(cart.length === 0){
    document.getElementById("summaryItems").scrollIntoView({behavior:"smooth", block:"start"});
    return;
  }

  const fields = [
    {input: document.getElementById("buyerName"), errId:"nameError", valid: v => v.trim().length >= 3},
    {input: document.getElementById("buyerPhone"), errId:"phoneError", valid: isValidPhone},
    {input: document.getElementById("buyerEmail"), errId:"emailError", valid: isValidEmail},
    {input: document.getElementById("buyerAddress"), errId:"addressError", valid: v => v.trim().length >= 10},
    {input: document.getElementById("buyerCity"), errId:"cityError", valid: v => v.trim().length >= 2},
    {input: document.getElementById("buyerZip"), errId:"zipError", valid: v => v.trim().length >= 4},
  ];

  let valid = true;
  fields.forEach(f => {
    const ok = f.valid(f.input.value);
    const err = document.getElementById(f.errId);
    if(!ok){
      f.input.classList.add("error");
      err.classList.add("show");
      valid = false;
    } else {
      f.input.classList.remove("error");
      err.classList.remove("show");
    }
  });

  if(!valid){
    document.getElementById("checkoutForm").scrollIntoView({behavior:"smooth", block:"start"});
    return;
  }

  const btn = document.getElementById("confirmBtn");
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + I18N.processing;

  const payload = {
    name: document.getElementById("buyerName").value,
    phone: document.getElementById("buyerPhone").value,
    email: document.getElementById("buyerEmail").value,
    address: document.getElementById("buyerAddress").value,
    city: document.getElementById("buyerCity").value,
    zip: document.getElementById("buyerZip").value,
    payment_method: selectedPaymentMethod,
    items: cart.map(item => ({
      id: item.id,
      name: item.name,
      price: item.price,
      qty: item.qty,
      icon: item.icon,
      img: item.img,
    })),
  };

  fetch("{{ route('checkout.store') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": CSRF_TOKEN,
      "Accept": "application/json",
    },
    body: JSON.stringify(payload),
  })
  .then(async res => {
    const data = await res.json().catch(() => null);
    if(!res.ok){
      console.error("Checkout gagal:", res.status, data);
      let msg = (data && data.message) || "Gagal membuat pesanan, coba lagi.";
      if(data && data.errors){
        msg = Object.values(data.errors).flat().join("\n");
      }
      throw new Error(msg);
    }
    return data;
  })
  .then(data => {
    localStorage.removeItem("trendup_cart");
    window.location.href = data.redirect;
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = I18N.confirmBtn + ' <i class="fa-solid fa-arrow-right"></i>';
    alert(err.message || "Gagal membuat pesanan, coba lagi.");
  });
}

renderSummary();
</script>
</body>
</html>