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
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    Register | {{ config('variables.templateName') }}
  </title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Include admin styles --}}
  @include('admin.layouts.sections.styles')

  {{-- Include scripts for config --}}
  @include('admin.layouts.sections.scriptsIncludes')

  {{-- Page-specific CSS --}}
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
</head>

<body>

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">

        <div class="card px-sm-6 px-0">
          <div class="card-body">

            <!-- Logo -->
            <div class="app-brand justify-content-center" style="height: 160px; display: flex; align-items: center;">
              <a class="app-brand-link gap-2">
                <img src="{{ asset('assets/img/logo/logo-squidcamp1.png') }}" alt="Logo"
                  style="width: 300px; height: 100%; object-fit: contain;">
              </a>
            </div>

            <h4 class="mb-1">Adventure starts here 🚀</h4>

            <!-- Register Form -->
            <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST">
              @csrf

              <div class="mb-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                  placeholder="Enter your username" value="{{ old('username') }}" required />
                @error('username')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                  value="{{ old('email') }}" required />
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password"
                    placeholder="Enter password" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                @error('password')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                    placeholder="Confirm password" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>

              <div class="my-7">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required />
                  <label class="form-check-label" for="terms-conditions">
                    I agree to <a href="#">privacy policy & terms</a>
                  </label>
                </div>
              </div>

              <button class="btn btn-primary d-grid w-100" type="submit">
                Sign up
              </button>
            </form>

            <p class="text-center">
              <span>Already have an account?</span>
              <a href="{{ route('login') }}">
                <span>Sign in instead</span>
              </a>
            </p>

          </div>
        </div>

      </div>
    </div>
  </div>

  @include('components.swal')

</body>

</html>
