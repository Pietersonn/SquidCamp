@php
    use App\Helpers\Helper;
@endphp

@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
<html lang="en"
      class="layout-menu-fixed layout-compact"
      data-assets-path="{{ asset('/assets') . '/' }}"
      dir="ltr"
      data-bs-theme="light"
      data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Forgot Password | {{ config('variables.templateName') }}
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

            <!-- Forgot Password Card -->
            <div class="card px-sm-6 px-0">
                <div class="card-body">

                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">
                                {{ config('variables.templateName') }}
                            </span>
                        </a>
                    </div>

                    <h4 class="mb-1">Forgot Password? 🔒</h4>
                    <p class="mb-6">
                        Enter your email and we'll send you instructions to reset your password
                    </p>

                    <form id="formAuthentication"
                          class="mb-6"
                          action="{{ route('password.email') }}"
                          method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                                required />
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <button class="btn btn-primary d-grid w-100" type="submit">
                            Send Reset Link
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-flex justify-content-center">
                            <i class="icon-base bx bx-chevron-left me-1"></i>
                            Back to login
                        </a>
                    </div>

                </div>
            </div>
            <!-- /Forgot Password Card -->

        </div>
    </div>
</div>

{{-- Admin Scripts --}}
@include('admin.layouts.sections.scripts')

</body>
</html>
