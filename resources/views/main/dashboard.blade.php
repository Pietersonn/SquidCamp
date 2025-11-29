@extends('main.layouts.mobileMaster')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* CSS Khusus Dashboard (Inline) */
    .dashboard-header {
        background: linear-gradient(135deg, var(--squid-primary) 0%, var(--squid-secondary) 100%);
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        padding: 40px 25px 90px 25px; /* Padding bawah extra agar card bisa 'naik' */
        color: white;
        position: relative;
    }

    .header-profile-img {
        width: 50px; height: 50px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.3);
        object-fit: cover; background: #fff;
    }

    /* Floating Card Saldo */
    .balance-card {
        background: white;
        border-radius: 24px;
        padding: 25px;
        margin: -60px 20px 25px 20px; /* Margin negatif agar naik ke atas */
        box-shadow: 0 15px 35px rgba(169, 173, 181, 0.15);
        position: relative; z-index: 10;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* Menu Grid Kecil */
    .quick-menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        padding: 0 25px;
        margin-bottom: 30px;
    }
    .quick-menu-item {
        text-align: center; text-decoration: none; color: #566a7f;
    }
    .quick-icon {
        width: 50px; height: 50px;
        background: white; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        font-size: 1.4rem; color: var(--squid-primary);
        margin: 0 auto 8px auto;
    }
</style>
@endsection

@section('content')

{{-- 1. HEADER --}}
<div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
        <p class="mb-0 opacity-75 small">Selamat Datang,</p>
        <h4 class="text-white fw-bold mb-0">{{ Auth::user()->name }}</h4>
        <span class="badge bg-white text-primary mt-2 rounded-pill px-3" style="color: var(--squid-primary) !important;">
            {{ $group->name ?? 'No Team' }}
        </span>
    </div>
    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User" class="header-profile-img">
</div>

{{-- 2. FLOATING BALANCE --}}
<div class="balance-card">
    <div>
        <span class="d-block text-muted small fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">SALDO TIM</span>
        <h2 class="mb-0 fw-bolder text-dark">SQ$ {{ number_format($group->squid_dollar ?? 0) }}</h2>
    </div>
    <div class="avatar p-2 rounded bg-label-primary" style="background-color: #e0f2f1; color: #00a79d;">
        <i class='bx bx-wallet fs-2'></i>
    </div>
</div>

{{-- 3. QUICK MENU --}}
<div class="quick-menu-grid">
    <a href="#" class="quick-menu-item">
        <div class="quick-icon text-warning"><i class='bx bx-crown'></i></div>
        <span class="small fw-bold">Top 10</span>
    </a>
    <a href="#" class="quick-menu-item">
        <div class="quick-icon text-info"><i class='bx bx-group'></i></div>
        <span class="small fw-bold">Tim</span>
    </a>
    <a href="#" class="quick-menu-item">
        <div class="quick-icon text-danger"><i class='bx bx-bell'></i></div>
        <span class="small fw-bold">Info</span>
    </a>
    <a href="#" class="quick-menu-item">
        <div class="quick-icon text-primary"><i class='bx bx-support'></i></div>
        <span class="small fw-bold">Bantuan</span>
    </a>
</div>

{{-- 4. ACTIVE CHALLENGES (Contoh) --}}
<div class="container px-4 pb-5">
    <h6 class="fw-bold mb-3 text-dark">Misi Terbaru</h6>
    <!-- Card Challenge bisa di include atau loop disini -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="avatar bg-label-warning rounded me-3">
                    <i class='bx bx-code-alt'></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Belum ada misi aktif</h6>
                    <small class="text-muted">Tunggu instruksi mentor.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
