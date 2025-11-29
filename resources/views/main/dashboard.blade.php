@extends('main.layouts.mobileMaster')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* --- HEADER SECTION --- */
    .dashboard-header {
        background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        padding: 40px 25px 90px 25px;
        color: white;
        position: relative;
    }

    .header-profile-img {
        width: 50px; height: 50px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.3);
        object-fit: cover; background: #fff;
    }

    /* --- FLOATING BALANCE CARD --- */
    .balance-card {
        background: white;
        border-radius: 24px;
        padding: 25px;
        margin: -60px 20px 25px 20px;
        box-shadow: 0 15px 35px rgba(169, 173, 181, 0.15);
        position: relative; z-index: 10;
        display: flex; justify-content: space-between; align-items: center;
    }

    .balance-amount {
        font-family: 'Public Sans', sans-serif;
        font-weight: 800;
        color: #232b2b;
        font-size: 2rem;
        line-height: 1;
        letter-spacing: -0.5px;
    }

    .currency-symbol {
        color: #00a79d;
        font-size: 1.5rem;
        margin-right: 2px;
        vertical-align: top;
        font-weight: 600;
    }

    /* Tombol Transfer */
    .btn-transfer-float {
        width: 55px; height: 55px;
        background: linear-gradient(135deg, #e0f2f1 0%, #ffffff 100%);
        color: #00a79d;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.1);
        border: 1px solid #f0fdfa;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .btn-transfer-float:active { transform: scale(0.95); }

    /* --- QUICK MENU GRID --- */
    .quick-menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        padding: 0 25px;
        margin-bottom: 30px;
    }
    .quick-menu-item {
        text-align: center; text-decoration: none; color: #566a7f;
        display: flex; flex-direction: column; align-items: center;
    }
    .quick-icon {
        width: 55px; height: 55px;
        background: white; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        font-size: 1.6rem;
        margin-bottom: 8px;
        transition: transform 0.2s;
    }
    .quick-menu-item:active .quick-icon { transform: scale(0.95); }

    /* --- SECTION TITLE --- */
    .section-heading {
        padding: 0 25px; margin-bottom: 15px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .section-heading h6 { font-weight: 800; margin: 0; color: #232b2b; }
    .section-heading a { font-size: 0.8rem; color: #00a79d; text-decoration: none; }

    /* --- ACTIVE MISSION CARD --- */
    .mission-card {
        margin: 0 25px 20px 25px;
        background: white; border-radius: 20px; padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-left: 5px solid #00a79d;
    }
</style>
@endsection

@section('content')

{{-- 1. HEADER --}}
<div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
        <p class="mb-0 opacity-75 small">Selamat Datang,</p>
        <h4 class="text-white fw-bold mb-0">{{ Auth::user()->name }}</h4>

        {{-- Badge Tim --}}
        <div class="mt-2">
            <span class="badge bg-white text-primary rounded-pill px-3 py-1 shadow-sm" style="color: #00a79d !important; font-weight:700;">
                @if($group)
                    <i class='bx bx-group me-1'></i> {{ $group->name }}
                @else
                    <i class='bx bx-error-circle me-1'></i> Belum Ada Tim
                @endif
            </span>
        </div>
    </div>
    {{-- Avatar User --}}
    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('assets/img/avatars/1.png') }}"
         alt="User" class="header-profile-img shadow-sm">
</div>

{{-- 2. FLOATING BALANCE & TRANSFER --}}
<div class="balance-card">
    <div>
        <span class="d-block text-muted small fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px; text-transform:uppercase;">ASET TIM</span>
        <h2 class="mb-0 balance-amount">
            <span class="currency-symbol">$</span>{{ number_format($group->squid_dollar ?? 0) }}
        </h2>
    </div>

    {{-- Tombol Transfer dengan Icon Pesawat --}}
    <div class="btn-transfer-float" data-bs-toggle="modal" data-bs-target="#transferModal">
        <i class='bx bx-paper-plane'></i>
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

{{-- 4. ACTIVE MISSION --}}
<div class="section-heading">
    <h6>Misi Terbaru</h6>
    <a href="#">Lihat Semua</a>
</div>

<div class="mission-card">
    <span class="badge bg-label-warning mb-2">PENDING</span>
    <h5 class="fw-bold text-dark mb-1">Belum ada misi aktif</h5>
    <p class="text-muted small mb-3">Tunggu instruksi mentor untuk tantangan selanjutnya.</p>

    <a href="#" class="btn btn-sm btn-primary w-100 rounded-pill" style="background-color: #00a79d; border:none;">
        Cek Arena Lomba
    </a>
</div>

{{-- MODAL TRANSFER --}}
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Kirim Squid Dollar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                {{-- ACTION POINT: Pastikan route ini benar --}}
                <form action="{{ route('main.transaction.transfer') }}" method="POST">
                    @csrf

                    {{-- Pilih Penerima --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Penerima</label>
                        <select class="form-select form-select-lg border-0 bg-light" name="to_group_id" required style="border-radius: 15px;">
                            <option value="" selected disabled>Pilih Kelompok...</option>

                            {{-- Loop Groups Lain --}}
                            @if(isset($allGroups) && count($allGroups) > 0)
                                @foreach($allGroups as $targetGroup)
                                    <option value="{{ $targetGroup->id }}">{{ $targetGroup->name }}</option>
                                @endforeach
                            @else
                                <option disabled>Tidak ada kelompok lain</option>
                            @endif

                        </select>
                    </div>

                    {{-- Input Nominal --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Nominal (SQ$)</label>
                        <div class="input-group input-group-lg bg-light rounded-3">
                            <span class="input-group-text border-0 bg-transparent text-primary fw-bold">$</span>
                            <input type="number" name="amount" class="form-control border-0 bg-transparent fw-bold text-dark" placeholder="0" min="1000" required>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.7rem;">Saldo Tim: ${{ number_format($group->squid_dollar ?? 0) }}</small>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" style="background-color: #00a79d; border:none;">
                        Konfirmasi Kirim <i class='bx bx-right-arrow-alt ms-1'></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
