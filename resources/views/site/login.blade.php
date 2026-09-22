<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('site.login_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">

  <!-- ===== LEFT BRAND PANEL ===== -->
  <div class="brand-panel">
    <a href="{{ route('home') }}" class="brand-logo" style="display:inline-block;">TRENDUP</a>
    <span class="brand-tagline">{{ __('site.login_tagline') }}</span>

    <div class="brand-image-wrap">
      <img src="{{ asset('images/trend.png') }}" alt="TRENDUP" class="brand-image">
    </div>

    <p class="brand-caption">{{ __('site.login_caption') }}</p>
  </div>

  <!-- ===== RIGHT FORM PANEL ===== -->
  <div class="form-panel">
    <div class="form-box">
      <div class="d-flex justify-content-end mb-2">
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

      <span class="eyebrow">{{ __('site.login_eyebrow') }}</span>
      <h1>{{ __('site.login_title') }}</h1>
      <p class="sub">{{ __('site.login_no_account') }} <a href="{{ route('register') }}">{{ __('site.login_register_here') }}</a></p>

      @if ($errors->any())
        <div class="alert-trendup show" style="display:block;background:#ffe1e1;color:#b00020;">
          <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
      @endif

      <div class="alert-trendup" id="successAlert">
        <i class="fa-solid fa-circle-check"></i> {{ __('site.login_success') }}
      </div>

      <form id="loginForm" method="POST" action="{{ route('login.submit') }}" novalidate>
        @csrf
        <label class="form-label-trendup">{{ __('site.label_email') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-envelope leading-icon"></i>
          <input type="email" name="email" class="form-control-trendup" id="loginEmail" value="{{ old('email') }}" placeholder="nama@email.com">
        </div>
        <div class="error-msg" id="emailError">{{ __('site.err_email_invalid') }}</div>

        <label class="form-label-trendup">{{ __('site.label_password') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock leading-icon"></i>
          <input type="password" name="password" class="form-control-trendup" id="loginPassword" placeholder="{{ __('site.label_password') }}">
          <button type="button" class="toggle-pass" onclick="togglePassword('loginPassword', this)"><i class="fa-regular fa-eye"></i></button>
        </div>
        <div class="error-msg" id="passwordError">{{ __('site.err_password_min') }}</div>

        <div class="form-options">
          <label class="form-check-trendup">
            <input type="checkbox" name="remember"> {{ __('site.remember_me') }}
          </label>
          <a href="#" class="link-lime">{{ __('site.forgot_password') }}</a>
        </div>

        <button type="submit" class="btn-submit" id="loginBtn">
          {{ __('site.btn_login_submit') }} <i class="fa-solid fa-arrow-right"></i>
        </button>
      </form>

      <div class="divider-or">{{ __('site.or_continue_with') }}</div>

      <div class="social-login">
        <button class="btn-social"><i class="fa-brands fa-google"></i> Google</button>
        <button class="btn-social"><i class="fa-brands fa-facebook"></i> Facebook</button>
      </div>
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
const I18N = {
  processing: @json(__('site.btn_processing')),
  loginSubmit: @json(__('site.btn_login_submit')),
};

function togglePassword(id, btn){
  const input = document.getElementById(id);
  const icon = btn.querySelector("i");
  if(input.type === "password"){
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

function isValidEmail(email){
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

document.getElementById("loginForm").addEventListener("submit", function(e){
  const email = document.getElementById("loginEmail");
  const password = document.getElementById("loginPassword");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");

  let valid = true;

  if(!isValidEmail(email.value)){
    email.classList.add("error");
    emailError.classList.add("show");
    valid = false;
  } else {
    email.classList.remove("error");
    emailError.classList.remove("show");
  }

  if(password.value.length < 6){
    password.classList.add("error");
    passwordError.classList.add("show");
    valid = false;
  } else {
    password.classList.remove("error");
    passwordError.classList.remove("show");
  }

  if(!valid){
    e.preventDefault();
    return;
  }

  const btn = document.getElementById("loginBtn");
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + I18N.processing;
  // form dibiarkan submit asli ke server (tidak dipreventDefault)
});
</script>
</body>
</html>