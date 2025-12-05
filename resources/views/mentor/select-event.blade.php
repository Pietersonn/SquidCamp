<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Pilih Event - Mentor Area</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Boxicons -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

  <!-- Vite (sesuai layout master) -->
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

  <!-- Custom Theme -->
  <link rel="stylesheet" href="{{ asset('assets/css/user/squid-theme.css') }}">

  <style>
    body {
      background: #f4f6f8;
    }

    /* --- CUSTOM GREEN THEME --- */
    .text-squid { color: #00a79d !important; }

    .bg-label-squid {
        background-color: rgba(0, 167, 157, 0.1) !important;
        color: #00a79d !important;
    }

    .btn-squid {
        background-color: #00a79d;
        border-color: #00a79d;
        color: white;
        box-shadow: 0 2px 4px rgba(0, 167, 157, 0.2);
        font-weight: 600;
    }
    .btn-squid:hover {
        background-color: #008f87;
        border-color: #008f87;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
    }

    .hover-elevate {
      transition: all 0.2s ease-in-out;
      border: 1px solid #eee; /* Default border halus */
    }
    .hover-elevate:hover {
      transform: translateY(-5px);
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.10)!important;
      border-color: #00a79d !important; /* Green Border on Hover */
    }

    .event-container {
      max-width: 820px;
      margin: 0 auto;
      padding: 30px 20px;
    }
  </style>
</head>

<body>

  <div class="container-xxl event-container">

    <div class="text-center mb-5">
      <h3 class="mb-1 text-squid fw-bold">Selamat Datang, Mentor! 👋</h3>
      <p class="text-muted">Silakan pilih Event untuk mengelola grup bimbingan Anda.</p>
    </div>

    <div class="row g-4 justify-content-center">

      @forelse($events as $ev)
      <div class="col-md-6">
        <div class="card h-100 shadow-none hover-elevate">
          <div class="card-body text-center p-4">

            <div class="avatar avatar-xl mx-auto mb-3">
              <span class="avatar-initial rounded-circle bg-label-squid">
                <i class="bx bx-calendar-event fs-1"></i>
              </span>
            </div>

            <h5 class="card-title mb-1 text-dark fw-bold">{{ $ev->name }}</h5>

            <p class="text-muted small mb-3">
              {{ \Carbon\Carbon::parse($ev->start_date)->format('d M Y') }}
              -
              {{ \Carbon\Carbon::parse($ev->end_date)->format('d M Y') }}
            </p>

            <p class="text-secondary mb-4 small">
              {{ Str::limit($ev->description, 80) }}
            </p>

            <a href="{{ route('mentor.dashboard', $ev->id) }}" class="btn btn-squid w-100">
              Masuk Dashboard <i class="bx bx-right-arrow-alt ms-1"></i>
            </a>

          </div>
        </div>
      </div>
      @empty

      <div class="col-12 text-center">
        <div class="alert alert-warning d-flex align-items-center justify-content-center" role="alert">
          <i class="bx bx-error-circle me-2"></i>
          <div><strong>Belum ada Event!</strong> Anda belum terdaftar sebagai Mentor di event manapun.</div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-outline-danger mt-3">Logout</button>
        </form>
      </div>

      @endforelse

    </div>
  </div>

</body>
</html>
