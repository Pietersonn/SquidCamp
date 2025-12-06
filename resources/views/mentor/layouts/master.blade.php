<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title') | SquidCamp Mentor</title>

  <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/logo-squidcamp.png') }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  {{-- Pastikan semua file ini terdaftar di vite.config.js jika masih error CORS/404 --}}
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

  <link rel="stylesheet" href="{{ asset('assets/css/user/squid-mobile.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/user/navbar.css') }}">

  @yield('styles')

  <style>
    body {
      background-color: #f4f6f8;
      padding-bottom: 80px;
      overflow-x: hidden;
    }
    ::-webkit-scrollbar { width: 0px; background: transparent; }
  </style>
</head>

<body>

  <main id="main-content" style="position: relative; min-height: 100vh;">
      @yield('content')
  </main>

  {{-- Include Bottom Nav --}}
  @include('mentor.layouts.bottom-nav')

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#00a79d', timer: 3000 });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#d33' });
        @endif

        @if($errors->any())
            Swal.fire({ icon: 'warning', title: 'Perhatian', html: '<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>', confirmButtonColor: '#00a79d' });
        @endif
    });
  </script>

  {{-- PERBAIKAN: Ganti yield menjadi stack --}}
  @stack('scripts')

</body>
</html>
