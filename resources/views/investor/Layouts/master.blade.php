<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | SquidCamp Investor</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/logo-squidcamp.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite([
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/css/demo.css',
        'resources/assets/vendor/libs/jquery/jquery.js',
        'resources/assets/vendor/libs/popper/popper.js',
        'resources/assets/vendor/js/bootstrap.js',
        'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
        'resources/assets/vendor/js/menu.js',
        'resources/assets/js/main.js'
    ])

    <link rel="stylesheet" href="{{ asset('assets/css/user/squid-mobile.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/user/navbar.css') }}">

    <style>
        body {
            background-color: #f5f5f9;
            padding-bottom: 90px; /* Space for Bottom Nav */
            overflow-x: hidden;
        }

        /* SquidCamp Colors for Investor */
        :root {
            --squid-primary: #00a79d;
            --squid-secondary: #00897b;
            --squid-dark: #004d40;
            --squid-gold: #ffab00;
        }

        .text-squid { color: var(--squid-primary) !important; }
        .bg-squid { background-color: var(--squid-primary) !important; }

        .btn-squid {
            background-color: var(--squid-primary);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-squid:hover {
            background-color: var(--squid-secondary);
            color: white;
            transform: translateY(-2px);
        }
    </style>

    @yield('styles')

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>

    <main id="main-content" style="position: relative; min-height: 100vh;">
        @yield('content')
    </main>

    @include('investor.layouts.bottom-nav')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#00a79d', timer: 2000, showConfirmButton: false });
            @endif

            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ff3e1d' });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
