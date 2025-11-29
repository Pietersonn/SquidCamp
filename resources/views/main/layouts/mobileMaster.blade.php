<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title') - SquidCamp</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    {{--
        ========================================
        1. VITE: LIBRARY BAWAAN (SCSS & JS)
        ========================================
    --}}
    @vite([
        // HAPUS boxicons.css dari sini jika error 404 terus.
        // Kita akan panggil icon lewat CDN saja agar lebih stabil & pasti muncul.

        // Core Styles (SCSS)
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/vendor/scss/theme-default.scss',

        // JS Files
        'resources/assets/vendor/libs/jquery/jquery.js',
        'resources/assets/vendor/libs/popper/popper.js',
        'resources/assets/vendor/js/bootstrap.js',
        'resources/assets/vendor/js/helpers.js',
        'resources/assets/js/config.js'
    ])

    {{--
        SOLUSI ICON HILANG: Gunakan CDN Boxicons
        Ini cara paling ampuh memastikan icon muncul tanpa pusing path lokal.
    --}}
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    {{--
        ========================================
        2. MANUAL CSS: CUSTOM TEMA & NAVIGASI
        ========================================
    --}}
    {{-- Path: public/assets/css/user/squid-theme.css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/user/squid-theme.css') }}" />

    {{-- Path: public/assets/css/user/navbar.css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/user/navbar.css') }}" />

    @yield('styles')
</head>
<body>

    <!-- Content Area -->
    @yield('content')

    <!-- Bottom Navigation -->
    @if(!request()->routeIs('main.onboarding.*'))
        @include('main.layouts.bottom-nav')
    @endif

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>
</html>
