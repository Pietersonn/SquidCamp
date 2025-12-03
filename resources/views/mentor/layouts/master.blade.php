<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
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

  @yield('styles')

  <style>
    body {
      background-color: #f4f6f8;
      padding-bottom: 80px; /* Space for Bottom Nav */
    }
    /* Scrollbar hide for cleaner mobile look */
    ::-webkit-scrollbar { width: 0px; background: transparent; }
  </style>
</head>

<body>

  <div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">
      <div class="layout-page">
        <div class="content-wrapper">
          @yield('content')
          </div>
      </div>
    </div>
  </div>

  @include('mentor.layouts.bottom-nav')

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        // Cek Validation Errors
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

  @yield('scripts')

</body>
</html>
