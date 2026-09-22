<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('site.contact_title') }} — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/contact.css') }}" rel="stylesheet">
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
      <a href="{{ route('home') }}">{{ __('site.nav_home') }}</a> / <span class="current">{{ __('site.nav_contact') }}</span>
    </div>
    <h1>{{ __('site.contact_title') }}</h1>
    <p>{{ __('site.contact_subtitle') }}</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <div class="row g-4">

      <div class="col-lg-5">
        <div class="contact-info-card">
          <h3>{{ __('site.contact_info_title') }}</h3>
          <p>{{ __('site.contact_info_desc') }}</p>

          <ul class="contact-info-list">
            <li>
              <div class="icon-wrap"><i class="fa-solid fa-location-dot"></i></div>
              <div>
                <div class="label">{{ __('site.contact_address_label') }}</div>
                <div class="value">{{ $toko['alamat_toko'] }}</div>
              </div>
            </li>
            <li>
              <div class="icon-wrap"><i class="fa-solid fa-envelope"></i></div>
              <div>
                <div class="label">{{ __('site.contact_email_label') }}</div>
                <a class="value" href="mailto:{{ $toko['email_toko'] }}">{{ $toko['email_toko'] }}</a>
              </div>
            </li>
            <li>
              <div class="icon-wrap"><i class="fa-brands fa-whatsapp"></i></div>
              <div>
                <div class="label">{{ __('site.contact_whatsapp_label') }}</div>
                <a class="value" href="https://wa.me/{{ preg_replace('/\D/', '', $toko['whatsapp']) }}" target="_blank" rel="noopener">{{ $toko['whatsapp'] }}</a>
              </div>
            </li>
            <li>
              <div class="icon-wrap"><i class="fa-solid fa-clock"></i></div>
              <div>
                <div class="label">{{ __('site.contact_hours_label') }}</div>
                <div class="value">{{ __('site.contact_hours_value') }}</div>
              </div>
            </li>
          </ul>

          <div class="contact-social">
            <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="form-card">
          <h3>{{ __('site.contact_form_title') }}</h3>

          @if (session('contact_success'))
            <div class="alert-success-trendup"><i class="fa-solid fa-circle-check"></i> {{ session('contact_success') }}</div>
          @endif

          <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <div class="form-row">
              <div>
                <label class="form-label-trendup">{{ __('site.contact_label_name') }}</label>
                <input type="text" name="name" class="form-control-trendup" value="{{ old('name') }}" placeholder="{{ __('site.contact_placeholder_name') }}">
                @error('name')<div class="error-msg-trendup">{{ $message }}</div>@enderror
              </div>
              <div>
                <label class="form-label-trendup">{{ __('site.contact_label_email') }}</label>
                <input type="email" name="email" class="form-control-trendup" value="{{ old('email') }}" placeholder="{{ __('site.contact_placeholder_email') }}">
                @error('email')<div class="error-msg-trendup">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="mb-field">
              <label class="form-label-trendup">{{ __('site.contact_label_subject') }}</label>
              <input type="text" name="subject" class="form-control-trendup" value="{{ old('subject') }}" placeholder="{{ __('site.contact_placeholder_subject') }}">
              @error('subject')<div class="error-msg-trendup">{{ $message }}</div>@enderror
            </div>

            <div class="mb-field">
              <label class="form-label-trendup">{{ __('site.contact_label_message') }}</label>
              <textarea name="message" class="form-control-trendup" rows="5" placeholder="{{ __('site.contact_placeholder_message') }}">{{ old('message') }}</textarea>
              @error('message')<div class="error-msg-trendup">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-submit-contact"><i class="fa-solid fa-paper-plane"></i> {{ __('site.contact_submit_btn') }}</button>
          </form>
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
        <div class="mt-3">
          <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
          <a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_shop_title') }}</h6>
        <a href="{{ route('shop') }}">{{ __('site.shop_title') }}</a>
      </div>
      <div class="col-6 col-lg-2">
        <h6>{{ __('site.footer_help_title') }}</h6>
        <a href="#">{{ __('site.footer_help_howto') }}</a>
        <a href="{{ route('tracking') }}">{{ __('site.footer_help_tracking') }}</a>
        <a href="{{ route('contact') }}">{{ __('site.nav_contact') }}</a>
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
</script>
</body>
</html>