@php
  use App\Helpers\Helper;
@endphp

@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('/assets') . '/' }}" dir="ltr"
  data-bs-theme="light" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>
    Login
  </title>

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  {{-- Styles Admin --}}
  @include('admin.layouts.sections.styles')

  {{-- Page-specific CSS --}}
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
</head>

<body>

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">

        <!-- Login Card -->
        <div class="card">
          <div class="card-body">

            <div class="app-brand justify-content-center" style="height: 160px; display: flex; align-items: center;">
              <a class="app-brand-link gap-2">
                <img src="{{ asset('assets/img/logo/logo-squidcamp1.png') }}" alt="Logo"
                  style="width: 300px; height: 100%; object-fit: contain;">
              </a>
            </div>



            <h4 class="mb-2">Welcome back! 👋</h4>
            <p class="mb-4">Please sign in to continue</p>

            <!-- Login Form -->
            <form id="formAuthentication" action="{{ route('login') }}" method="POST" class="mb-3">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                  value="{{ old('email') }}" autofocus>
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••••">
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                  <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
              </div>

              <button class="btn btn-primary d-grid w-100" type="submit">Sign In</button>
            </form>

            <p class="text-center">
              <span>New here?</span>
              <a href="{{ route('register') }}">
                <span>Create an account</span>
              </a>
            </p>

            <div class="divider my-4">
              <div class="divider-text">or</div>
            </div>

            <div class="d-flex justify-content-center">
              <a href="{{ route('auth.google.redirect') }}"
                class="d-flex align-items-center justify-content-center gap-2 w-100"
                style="background:#fff; border:1px solid #dadce0; border-radius:4px; height:45px;">
                <img src="https://developers.google.com/identity/images/g-logo.png" style="width:20px;">
                <span style="color:#3c4043; font-size:14px; font-weight:500;">Continue with Google</span>
              </a>

            </div>

          </div>
        </div>
        <!-- /Login Card -->

      </div>
    </div>
  </div>

  {{-- Admin Scripts --}}
  @include('components.swal')

</body>

</html>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".input-group-text");
    const passwordField = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);

      // Ganti ikon
      this.querySelector("i").classList.toggle("bx-hide");
      this.querySelector("i").classList.toggle("bx-show");
    });
  });
</script>
