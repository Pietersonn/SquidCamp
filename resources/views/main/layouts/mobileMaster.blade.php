<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title') - SquidCamp</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/logo-squidcamp.png') }}" />

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- CDN Icons Boxicons (Paling Aman) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- VITE RESOURCES --}}
    @vite([
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/vendor/libs/jquery/jquery.js',
        'resources/assets/vendor/libs/popper/popper.js',
        'resources/assets/vendor/js/bootstrap.js',
        'resources/assets/vendor/js/helpers.js',
        'resources/assets/js/config.js'
    ])

    {{-- MANUAL CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/user/squid-theme.css') }}" />
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

    {{-- SweetAlert JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SCRIPT GLOBAL NOTIFIKASI (TAMBAHAN BARU) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek Flash Message Success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#00a79d',
                    timer: 3000
                });
            @endif

            // Cek Flash Message Error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif

            // Cek Validation Errors (Laravel $errors)
            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    html: '<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#00a79d'
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
