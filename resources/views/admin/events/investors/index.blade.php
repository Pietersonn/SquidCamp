@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Investors - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-gold: #ffab00;
        --squid-light: #e0f2f1;
    }

    /* --- GOKIL CARD STYLES --- */
    .investor-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .investor-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.15);
    }

    /* Link Overlay (Membuat seluruh kartu bisa diklik) */
    .card-link-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        cursor: pointer;
    }

    /* Header Visual (Coin Pattern) */
    .visual-header {
        height: 130px;
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%); /* Gold Soft */
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        /* Pattern Uang */
        background-image: radial-gradient(#ffc107 1px, transparent 1px);
        background-size: 15px 15px;
    }

    /* Ikon Uang Besar */
    .visual-header i.main-icon {
        font-size: 4.5rem;
        color: #ffc107;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        transition: 0.3s;
    }

    .investor-card:hover .visual-header i.main-icon {
        transform: scale(1.1) rotate(-10deg);
    }

    /* Avatar Floating */
    .avatar-wrapper {
        margin-top: -40px;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
        pointer-events: none; /* Agar klik tembus ke overlay */
    }
    .investor-avatar {
        width: 80px;
        height: 80px;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        background-color: var(--squid-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        transition: 0.3s;
    }
    .investor-card:hover .investor-avatar {
        border-color: var(--squid-light);
        transform: scale(1.05);
    }

    /* Saldo Badge */
    .saldo-box {
        background-color: #f0fdfa;
        border: 1px dashed var(--squid-primary);
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        margin-top: 15px;
        transition: 0.3s;
    }
    .investor-card:hover .saldo-box {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
    }
    .saldo-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #a1acb8;
        margin-bottom: 2px;
        font-weight: 700;
    }
    .saldo-amount {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--squid-primary);
    }
    .investor-card:hover .saldo-label { color: rgba(255,255,255,0.8); }
    .investor-card:hover .saldo-amount { color: #fff; }

    /* Action Button (Glassmorphism) */
    .btn-action-glass {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(4px);
        border: none;
        color: #666;
        transition: 0.2s;
    }
    .btn-action-glass:hover {
        background: #fff;
        color: var(--squid-primary);
        transform: scale(1.1);
    }

    /* Dropdown Wrapper - Harus di atas Link Overlay */
    .card-dropdown-wrapper {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }

    /* Tombol Utama */
    .btn-squid {
        background-color: var(--squid-primary);
        color: #fff;
        border: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-squid:hover {
        background-color: #008f85;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.3);
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')

{{-- HEADER HALAMAN --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">
            <i class="bx bx-line-chart fs-3 me-2"></i>Event Investors
        </h4>
        <span class="text-muted">Event: <strong class="text-dark">{{ $event->name }}</strong></span>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary shadow-sm btn-lg">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.events.investors.create', $event->id) }}" class="btn btn-squid shadow-sm btn-lg">
            <i class="bx bx-plus-circle me-1"></i> Tambah Investor
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <i class="bx bx-check-circle fs-4 me-2"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

<div class="row g-4">
  @forelse ($investors as $investor)
    <div class="col-md-6 col-lg-4 col-xl-3">
      <div class="investor-card">

        {{-- LINK UTAMA: Klik Card -> Masuk Show --}}
        <a href="{{ route('admin.events.investors.show', ['event' => $event->id, 'investor' => $investor->id]) }}" class="card-link-overlay"></a>

        {{-- Visual Header --}}
        <div class="visual-header">
            <i class="bx bx-money main-icon"></i>

            {{-- Dropdown Menu (Z-Index Tinggi agar bisa diklik terpisah dari card) --}}
            <div class="dropdown card-dropdown-wrapper">
                <button class="btn btn-icon btn-action-glass" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li>
                        {{-- Link ke halaman Show Detail --}}
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.events.investors.show', ['event' => $event->id, 'investor' => $investor->id]) }}">
                            <i class="bx bx-show me-2 text-info"></i> Lihat Portfolio
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.events.investors.edit', ['event' => $event->id, 'investor' => $investor->id]) }}">
                            <i class="bx bx-edit-alt me-2 text-primary"></i> Edit Saldo
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.events.investors.destroy', ['event' => $event->id, 'investor' => $investor->id]) }}" method="POST" onsubmit="return confirm('Hapus investor ini dari event?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                <i class="bx bx-trash me-2"></i> Hapus
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Avatar --}}
        <div class="avatar-wrapper">
            @if($investor->user->avatar)
                <img src="{{ asset($investor->user->avatar) }}" class="rounded-circle investor-avatar">
            @else
                <div class="rounded-circle investor-avatar">
                    {{ substr($investor->user->name, 0, 1) }}
                </div>
            @endif
        </div>

        <div class="card-body text-center pt-2 d-flex flex-column">
            <h5 class="fw-bold text-dark mb-0 text-truncate position-relative" style="z-index: 2;">{{ $investor->user->name }}</h5>
            <small class="text-muted text-truncate position-relative" style="z-index: 2;">{{ $investor->user->email }}</small>

            <div class="mt-auto">
                <div class="saldo-box">
                    <span class="saldo-label">Modal Investasi</span>
                    <span class="saldo-amount">${{ number_format($investor->investment_balance, 0, ',', '.') }}</span>
                </div>

                {{-- Helper Text "Klik untuk detail" --}}
                <small class="d-block mt-2 text-muted fst-italic" style="font-size: 0.65rem;">Klik untuk detail portfolio &rarr;</small>
            </div>
        </div>

      </div>
    </div>
  @empty
    <div class="col-12">
        <div class="card text-center py-5 border-0 shadow-sm bg-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle" style="background-color: #fff8e1;">
                        <i class="bx bx-line-chart" style="font-size: 3.5rem; color: #ffc107;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Belum ada Investor</h3>
                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                    Tambahkan investor untuk mendanai kelompok peserta dan memeriahkan kompetisi.
                </p>
                <a href="{{ route('admin.events.investors.create', $event->id) }}" class="btn btn-squid shadow-sm btn-lg">
                    <i class="bx bx-plus me-1"></i> Tambah Investor
                </a>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
