<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Pilih Event - Investor Portal</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/logo-squidcamp.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />

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

    <style>
        body {
            background-color: #f4f6f8;
        }

        /* --- SQUID THEME (Sama seperti Mentor) --- */
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
            transition: all 0.2s ease-in-out;
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
            border: 1px solid #eee;
            background: white;
            border-radius: 1rem;
        }

        .hover-elevate:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.10) !important;
            border-color: #00a79d !important;
        }

        .event-container {
            max-width: 820px;
            margin: 0 auto;
            padding: 40px 20px;
        }
    </style>
</head>

<body>
    <div class="container-xxl event-container">

        {{-- 1. HEADER SECTION --}}
        <div class="text-center mb-5">
            <h3 class="mb-1 text-squid fw-bold">Selamat Datang, Investor! 👋</h3>
            <p class="text-muted">Pilih Event yang sedang berlangsung untuk mulai berinvestasi.</p>
        </div>

        {{-- 2. EVENT LIST GRID --}}
        <div class="row g-4 justify-content-center">

            @forelse($events as $event)
                <div class="col-md-6">
                    <div class="card h-100 shadow-none hover-elevate">
                        <div class="card-body text-center p-4">

                            {{-- Icon Avatar --}}
                            <div class="avatar avatar-xl mx-auto mb-3">
                                <span class="avatar-initial rounded-circle bg-label-squid">
                                    <i class='bx bx-briefcase fs-1'></i>
                                </span>
                            </div>

                            {{-- Title --}}
                            <h5 class="card-title mb-1 text-dark fw-bold">{{ $event->name }}</h5>

                            {{-- Date --}}
                            @if($event->event_date)
                                <p class="text-muted small mb-3">
                                    <i class='bx bx-calendar me-1'></i>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                </p>
                            @else
                                <p class="text-muted small mb-3">Tanggal belum ditentukan</p>
                            @endif

                            {{-- Instansi / Description --}}
                            <p class="text-secondary mb-4 small">
                                @if($event->instansi)
                                    <i class='bx bx-building-house me-1'></i> {{ $event->instansi }}
                                @else
                                    Investasi pada kelompok terbaik di event ini.
                                @endif
                            </p>

                            {{-- Button --}}
                            <a href="{{ route('investor.dashboard', $event->id) }}" class="btn btn-squid w-100 rounded-pill">
                                Masuk Dashboard <i class='bx bx-right-arrow-alt ms-1'></i>
                            </a>

                        </div>
                    </div>
                </div>
            @empty
                {{-- EMPTY STATE --}}
                <div class="col-12 text-center">
                    <div class="card shadow-none border bg-transparent p-5">
                        <div class="mb-3">
                            <div class="avatar avatar-xl mx-auto">
                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                    <i class='bx bx-ghost fs-1'></i>
                                </span>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Event</h5>
                        <p class="text-muted">Anda belum terdaftar sebagai Investor di event manapun yang sedang aktif.</p>
                    </div>
                </div>
            @endforelse

        </div>

        {{-- 3. LOGOUT BUTTON --}}
        <div class="text-center mt-5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-label-secondary">
                    <i class='bx bx-log-out me-1'></i> Logout
                </button>
            </form>
        </div>

    </div>
</body>
</html>
