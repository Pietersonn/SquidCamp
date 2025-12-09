<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SquidCamp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            background-color: #f5f5f9;
            font-family: 'Public Sans', sans-serif;
            padding-bottom: 20px;
        }

        /* 1. Header Lengkung (Hero Section) */
        .hero-header {
            background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            padding: 50px 25px 120px 25px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi Lingkaran */
        .hero-header::before {
            content: ''; position: absolute; top: -50%; left: -20%;
            width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%;
        }
        .hero-header::after {
            content: ''; position: absolute; bottom: 10%; right: -10%;
            width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;
        }

        /* Logo Box */
        .logo-box {
            background: white; width: 80px; height: 80px; border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            animation: floatLogo 3s ease-in-out infinite;
        }
        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* 2. Floating Card (Info Login/User) */
        .floating-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            margin: -80px 20px 30px 20px;
            box-shadow: 0 15px 35px rgba(169, 173, 181, 0.15);
            position: relative;
            z-index: 10;
            text-align: center;
        }

        /* 3. Event Card Styling */
        .event-card {
            border: none; border-radius: 20px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s; margin-bottom: 20px; background: white;
        }
        .event-card:active { transform: scale(0.98); }

        .event-cover {
            height: 150px; background-size: cover; background-position: center; position: relative;
        }

        /* Badge Status */
        .status-badge {
            position: absolute; top: 15px; right: 15px;
            background: rgba(255, 255, 255, 0.95); color: #00a79d;
            padding: 5px 12px; border-radius: 30px;
            font-size: 0.75rem; font-weight: 800;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-action-primary {
            background-color: #00a79d; color: white; border: none; font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 167, 157, 0.3);
        }
        .text-primary-squid { color: #00a79d; }
    </style>
</head>
<body>

    {{-- 1. HERO SECTION --}}
    <div class="hero-header">
        <div class="logo-box">
            <img src="{{ asset('assets/img/logo/logo-squidcamp.png') }}" alt="Logo" width="50">
        </div>
        <h3 class="fw-bold text-white mb-1">SquidCamp</h3>
        <p class="text-white opacity-80 small mb-0">Youth Entrepreneurship Training</p>
    </div>

    {{-- 2. FLOATING CARD (LOGIKA LOGIN/USER) --}}
    <div class="floating-card">
        @guest
            {{-- Tampilan Jika Belum Login --}}
            <h5 class="fw-bold text-dark mb-2">Selamat Datang!</h5>
            <p class="text-muted small mb-4">Bergabunglah dengan ribuan peserta lainnya dan buktikan kemampuan tim kamu.</p>

            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill" style="border-color: #00a79d; color: #00a79d;">
                        Masuk
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('register') }}" class="btn btn-action-primary w-100 rounded-pill">
                        Daftar
                    </a>
                </div>
            </div>
        @else
            {{-- Tampilan Jika User SUDAH Login --}}
            <div class="mb-3">
                <h5 class="fw-bold text-dark mb-0">Hai, {{ Auth::user()->name }}!</h5>
            </div>

            <p class="text-muted small mb-3">Siap untuk tantangan hari ini?</p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill btn-sm">
                    <i class='bx bx-log-out me-1'></i> Logout
                </button>
            </form>
        @endguest
    </div>

    {{-- 3. RULES & GUIDELINES SECTION --}}
    <div class="container px-3 mb-4">
        <h6 class="fw-bold text-dark mb-3 ps-1">📚 Informasi Penting</h6>

        <div class="row g-3">
            <div class="col-6">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: 0.2s;">
                        <div class="card-body p-3 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center mb-2 rounded-circle"
                                 style="width: 45px; height: 45px; background-color: #ffe5e5; color: #ff3e1d;">
                                <i class='bx bxs-file-pdf fs-3'></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Rulebook</h6>
                            <span class="badge bg-light text-secondary rounded-pill" style="font-size: 0.65rem;">
                                <i class='bx bx-download me-1'></i>PDF
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: 0.2s;">
                        <div class="card-body p-3 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center mb-2 rounded-circle"
                                 style="width: 45px; height: 45px; background-color: #e0f2f1; color: #00a79d;">
                                <i class='bx bxs-book-content fs-3'></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Panduan</h6>
                            <span class="badge bg-light text-secondary rounded-pill" style="font-size: 0.65rem;">
                                <i class='bx bx-download me-1'></i>PDF
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- 4. LIST EVENT SECTION --}}
    <div class="container px-3 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark m-0">🔥 Event Tersedia</h6>
            <span class="badge bg-primary rounded-pill" style="background-color: #00a79d !important;">Season 1</span>
        </div>

        @forelse($events as $event)
            <div class="card event-card">
                {{-- Cover Image & Badge Status --}}
                <div class="event-cover" style="background-image: url('{{ $event->banner_image_path ? asset('storage/'.$event->banner_image_path) : asset('assets/img/backgrounds/1.jpg') }}');">
                    <span class="status-badge">
                        @php
                            $isToday = \Carbon\Carbon::parse($event->event_date)->isToday();
                        @endphp

                        @if($event->is_finished)
                            SELESAI
                        @elseif($event->is_active)
                            LIVE NOW
                        @elseif($isToday && !$event->is_active)
                            MENUNGGU ADMIN
                        @else
                            COMING SOON
                        @endif
                    </span>
                </div>

                <div class="card-body p-3">
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $event->name }}</h5>

                    <div class="d-flex align-items-center text-muted small mb-3">
                        <i class='bx bx-calendar me-1 text-primary-squid'></i>
                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                    </div>

                    <p class="card-text small text-secondary mb-3" style="line-height: 1.5;">
                        {{ Str::limit($event->description ?? 'Tantangan logika, algoritma, dan kerjasama tim menanti anda di event ini.', 80) }}
                    </p>

                    {{-- LOGIKA TOMBOL EVENT --}}
                    @if($event->is_finished)
                        {{-- KONDISI 1: Event Selesai --}}
                        <button class="btn btn-secondary w-100 rounded-3 py-2" disabled>
                            <i class='bx bx-flag me-1'></i> Event Telah Selesai
                        </button>

                    @elseif($event->is_active)
                        {{-- KONDISI 2: Event LIVE (Satu-satunya yang bisa diklik) --}}
                        <a href="{{ route('main.dashboard') }}" class="btn btn-action-primary w-100 rounded-3 py-2">
                            Masuk Event <i class='bx bx-right-arrow-alt ms-1'></i>
                        </a>

                    @elseif($isToday && !$event->is_active)
                        {{-- KONDISI 3: Hari H tapi belum Start --}}
                        <button class="btn btn-warning w-100 rounded-3 py-2 text-dark fw-bold" disabled style="opacity: 1;">
                            <i class='bx bx-time me-1'></i> Menunggu Dimulai
                        </button>

                    @else
                        {{-- KONDISI 4: Coming Soon (Masa Depan) --}}
                        <button class="btn btn-secondary w-100 rounded-3 py-2" disabled style="opacity: 0.6;">
                            <i class='bx bx-calendar me-1'></i> Coming Soon
                        </button>
                    @endif

                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-3 text-secondary opacity-50">
                    <i class='bx bx-calendar-x fs-1'></i>
                </div>
                <h6 class="text-muted mb-1">Belum ada event dibuka.</h6>
                <p class="small text-muted">Cek kembali nanti ya!</p>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @include('components.swal')
</body>
</html>
