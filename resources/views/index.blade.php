@extends('main.layouts.mobileMaster')

@section('title', 'Welcome to Squad')

@section('styles')
<style>
    /* CSS Khusus Halaman Landing (Inline agar tidak mencemari halaman lain) */

    /* 1. Header Lengkung (Hero Section) */
    .hero-header {
        background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        padding: 50px 25px 100px 25px; /* Padding bawah besar untuk efek floating card */
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Elemen Dekorasi Lingkaran Transparan */
    .hero-header::before {
        content: '';
        position: absolute;
        top: -50%; left: -20%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .hero-header::after {
        content: '';
        position: absolute;
        bottom: 10%; right: -10%;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* Logo Box di tengah Header */
    .logo-box {
        background: white;
        width: 80px; height: 80px;
        border-radius: 22px;
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

    /* 2. Floating Card (Info Login) */
    .floating-card {
        background: white;
        border-radius: 24px;
        padding: 25px;
        margin: -70px 20px 30px 20px; /* Margin negatif agar naik ke atas header */
        box-shadow: 0 15px 35px rgba(169, 173, 181, 0.15);
        position: relative;
        z-index: 10;
        text-align: center;
    }

    /* 3. Event Card Styling */
    .event-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.2s;
        margin-bottom: 20px;
        background: white;
    }

    .event-card:active { transform: scale(0.98); }

    .event-cover {
        height: 150px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .status-badge {
        position: absolute; top: 15px; right: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: #00a79d;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem; font-weight: 800;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-action-primary {
        background-color: #00a79d;
        color: white;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.3);
    }
</style>
@endsection

@section('content')

{{-- 1. HERO SECTION --}}
<div class="hero-header">
    <div class="logo-box">
        <img src="{{ asset('assets/img/logo/logo-squidcamp.png') }}" alt="Logo" width="50">
    </div>
    <h3 class="fw-bold text-white mb-1">SquidCamp</h3>
    <p class="text-white opacity-80 small mb-0">Platform Kompetisi Coding & Logika</p>
</div>

{{-- 2. FLOATING WELCOME CARD --}}
<div class="floating-card">
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
</div>

{{-- 3. LIST EVENT SECTION --}}
<div class="container px-3 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark m-0">🔥 Event Tersedia</h6>
        <span class="badge bg-label-primary rounded-pill">Season 1</span>
    </div>

    @forelse($events as $event)
        <div class="card event-card">
            {{-- Cover Image --}}
            <div class="event-cover" style="background-image: url('{{ $event->banner_image_path ? asset('storage/'.$event->banner_image_path) : asset('assets/img/backgrounds/1.jpg') }}');">
                <span class="status-badge">
                    @if($event->is_active) LIVE NOW @else COMING SOON @endif
                </span>
            </div>

            <div class="card-body p-3">
                <h5 class="card-title fw-bold text-dark mb-1">{{ $event->name }}</h5>

                <div class="d-flex align-items-center text-muted small mb-3">
                    <i class='bx bx-calendar me-1 text-primary'></i>
                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                </div>

                <p class="card-text small text-secondary mb-3" style="line-height: 1.5;">
                    {{ Str::limit($event->description ?? 'Tantangan logika, algoritma, dan kerjasama tim menanti anda di event ini.', 80) }}
                </p>

                <a href="{{ route('main.event.join', $event->id) }}" class="btn btn-action-primary w-100 rounded-3 py-2">
                    Gabung Event <i class='bx bx-right-arrow-alt ms-1'></i>
                </a>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="avatar avatar-xl bg-label-secondary rounded-circle mb-3 mx-auto">
                <i class='bx bx-calendar-x fs-1'></i>
            </div>
            <h6 class="text-muted mb-1">Belum ada event dibuka.</h6>
            <p class="small text-muted">Cek kembali nanti ya!</p>
        </div>
    @endforelse
</div>

@endsection
