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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Login | {{ config('variables.templateName') }}
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Styles Admin --}}
    @include('admin.layouts.sections.styles')

    {{-- Scripts (helpers, configs, etc) --}}
    @include('admin.layouts.sections.scriptsIncludes')

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

                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @include('_partials.macros', [
                                        'width' => 25,
                                        'withbg' => 'var(--bs-primary)'
                                    ])
                                </span>
                                <span class="app-brand-text demo text-body fw-bold">
                                    {{ config('variables.templateName') }}
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-2">Welcome back! 👋</h4>
                        <p class="mb-4">Please sign in to continue</p>

                        <!-- Login Form -->
                        <form id="formAuthentication" action="{{ route('login') }}" method="POST" class="mb-3">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email"
                                    value="{{ old('email') }}"
                                    autofocus>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password"
                                        id="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="••••••••••••">
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="remember-me"
                                        name="remember">
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
                                class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons bx bxl-google"></i>
                            </a>
                        </div>

                    </div>
                </div>
                <!-- /Login Card -->

            </div>
        </div>
    </div>

    {{-- Admin Scripts --}}
    @include('admin.layouts.sections.scripts')

</body>

</html>
