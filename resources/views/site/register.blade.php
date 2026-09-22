<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('site.register_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/register.css') }}" rel="stylesheet">
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

      <span class="eyebrow">{{ __('site.register_eyebrow') }}</span>
      <h1>{{ __('site.register_title') }}</h1>
      <p class="sub">{{ __('site.register_have_account') }} <a href="{{ route('login') }}">{{ __('site.register_login_here') }}</a></p>

      @if ($errors->any())
        <div class="alert-trendup show" style="display:block;background:#ffe1e1;color:#b00020;">
          <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
      @endif

      <div class="alert-trendup" id="successAlert">
        <i class="fa-solid fa-circle-check"></i> {{ __('site.register_success') }}
      </div>

      <form id="registerForm" method="POST" action="{{ route('register.submit') }}" novalidate>
        @csrf
        <label class="form-label-trendup">{{ __('site.label_fullname') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-user leading-icon"></i>
          <input type="text" name="name" class="form-control-trendup" id="regName" value="{{ old('name') }}" placeholder="{{ __('site.placeholder_fullname') }}">
        </div>
        <div class="error-msg" id="nameError">{{ __('site.err_name_min') }}</div>

        <label class="form-label-trendup">{{ __('site.label_email') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-envelope leading-icon"></i>
          <input type="email" name="email" class="form-control-trendup" id="regEmail" value="{{ old('email') }}" placeholder="nama@email.com">
        </div>
        <div class="error-msg" id="emailError">{{ __('site.err_email_invalid') }}</div>

        <label class="form-label-trendup">{{ __('site.label_phone') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-phone leading-icon"></i>
          <input type="text" name="phone" class="form-control-trendup" id="regPhone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
        </div>
        <div class="error-msg" id="phoneError">{{ __('site.err_phone_invalid') }}</div>

        <label class="form-label-trendup">{{ __('site.label_address') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-location-dot leading-icon"></i>
          <textarea name="address" class="form-control-trendup" id="regAddress" rows="3" placeholder="{{ __('site.placeholder_address') }}">{{ old('address') }}</textarea>
        </div>
        <div class="error-msg" id="addressError">{{ __('site.err_address_required') }}</div>

        <label class="form-label-trendup">{{ __('site.label_password') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock leading-icon"></i>
          <input type="password" name="password" class="form-control-trendup" id="regPassword" placeholder="{{ __('site.label_password') }}" oninput="checkStrength()">
          <button type="button" class="toggle-pass" onclick="togglePassword('regPassword', this)"><i class="fa-regular fa-eye"></i></button>
        </div>
        <div class="strength-bar">
          <div class="seg" id="seg1"></div>
          <div class="seg" id="seg2"></div>
          <div class="seg" id="seg3"></div>
        </div>
        <span class="strength-label" id="strengthLabel">{{ __('site.strength_min6') }}</span>
        <div class="error-msg" id="passwordError">{{ __('site.err_password_min') }}</div>

        <label class="form-label-trendup">{{ __('site.label_confirm_password') }}</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock leading-icon"></i>
          <input type="password" name="password_confirmation" class="form-control-trendup" id="regPasswordConfirm" placeholder="{{ __('site.placeholder_confirm_password') }}">
          <button type="button" class="toggle-pass" onclick="togglePassword('regPasswordConfirm', this)"><i class="fa-regular fa-eye"></i></button>
        </div>
        <div class="error-msg" id="passwordConfirmError">{{ __('site.err_password_confirm') }}</div>

        <label class="form-check-trendup">
          <input type="checkbox" id="agreeTerms">
          <span>{{ __('site.agree_terms') }} <a href="#" class="link-lime">{{ __('site.terms_link') }}</a> {{ __('site.and') }} <a href="#" class="link-lime">{{ __('site.privacy_link') }}</a> TRENDUP.</span>
        </label>
        <div class="error-msg" id="termsError" style="margin-top:-18px;">{{ __('site.err_terms_required') }}</div>

        <button type="submit" class="btn-submit" id="registerBtn">
          {{ __('site.btn_register_submit') }} <i class="fa-solid fa-arrow-right"></i>
        </button>
      </form>

      <div class="divider-or">{{ __('site.or_register_with') }}</div>

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
  registerSubmit: @json(__('site.btn_register_submit')),
  min6: @json(__('site.strength_min6')),
  weak: @json(__('site.strength_weak')),
  medium: @json(__('site.strength_medium')),
  strong: @json(__('site.strength_strong')),
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
function isValidPhone(phone){
  return /^[0-9]{10,14}$/.test(phone);
}

function checkStrength(){
  const val = document.getElementById("regPassword").value;
  const seg1 = document.getElementById("seg1");
  const seg2 = document.getElementById("seg2");
  const seg3 = document.getElementById("seg3");
  const label = document.getElementById("strengthLabel");

  [seg1,seg2,seg3].forEach(s => s.className = "seg");

  if(val.length === 0){
    label.textContent = I18N.min6;
    return;
  }
  let score = 0;
  if(val.length >= 6) score++;
  if(val.length >= 10 && /[0-9]/.test(val)) score++;
  if(val.length >= 10 && /[A-Z]/.test(val) && /[0-9]/.test(val)) score++;

  if(score === 1){
    seg1.classList.add("active-weak");
    label.textContent = I18N.weak;
  } else if(score === 2){
    seg1.classList.add("active-mid"); seg2.classList.add("active-mid");
    label.textContent = I18N.medium;
  } else if(score >= 3){
    seg1.classList.add("active-strong"); seg2.classList.add("active-strong"); seg3.classList.add("active-strong");
    label.textContent = I18N.strong;
  }
}

document.getElementById("registerForm").addEventListener("submit", function(e){
  const name = document.getElementById("regName");
  const email = document.getElementById("regEmail");
  const phone = document.getElementById("regPhone");
  const address = document.getElementById("regAddress");
  const password = document.getElementById("regPassword");
  const passwordConfirm = document.getElementById("regPasswordConfirm");
  const terms = document.getElementById("agreeTerms");

  let valid = true;

  function toggleError(input, errorId, condition){
    const err = document.getElementById(errorId);
    if(!condition){
      input.classList.add("error");
      err.classList.add("show");
      valid = false;
    } else {
      input.classList.remove("error");
      err.classList.remove("show");
    }
  }

  toggleError(name, "nameError", name.value.trim().length >= 3);
  toggleError(email, "emailError", isValidEmail(email.value));
  toggleError(phone, "phoneError", isValidPhone(phone.value));
  toggleError(address, "addressError", address.value.trim().length >= 10);
  toggleError(password, "passwordError", password.value.length >= 6);
  toggleError(passwordConfirm, "passwordConfirmError", passwordConfirm.value === password.value && password.value.length > 0);

  const termsError = document.getElementById("termsError");
  if(!terms.checked){
    termsError.classList.add("show");
    valid = false;
  } else {
    termsError.classList.remove("show");
  }

  if(!valid){
    e.preventDefault();
    return;
  }

  const btn = document.getElementById("registerBtn");
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + I18N.processing;
  // form dibiarkan submit asli ke server (tidak dipreventDefault)
});
</script>
</body>
</html>