@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Kelola Challenge Event')

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-secondary: #00d2c6;
    }
    /* --- HERO HEADER --- */
    .header-event-challenge {
        background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        box-shadow: 0 10px 20px rgba(0, 167, 157, 0.2);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .header-decoration {
        position: absolute;
        top: -20px;
        right: -20px;
        font-size: 10rem;
        opacity: 0.1;
        color: white;
        transform: rotate(-15deg);
    }

    /* --- TABLE STYLES --- */
    .table-card {
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }
    .table-header-row th {
        background-color: #f8f9fa !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 2px solid #eee;
        color: #666;
    }
    .challenge-row {
        transition: all 0.2s ease;
    }
    .challenge-row:hover {
        background-color: #f0fdfa !important;
        transform: scale(1.005);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        z-index: 10;
        position: relative;
    }

    /* --- BADGES --- */
    .price-tag {
        background: rgba(0, 167, 157, 0.1);
        color: var(--squid-primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        border: 1px solid rgba(0, 167, 157, 0.2);
    }
    .action-btn {
        width: 35px; height: 35px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%;
        transition: 0.3s;
        color: #ff3e1d;
        background: rgba(255, 62, 29, 0.1);
        border: none;
    }
    .action-btn:hover {
        background: #ff3e1d;
        color: white;
        box-shadow: 0 4px 10px rgba(255, 62, 29, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- 1. HERO HEADER --}}
    <div class="header-event-challenge d-flex justify-content-between align-items-center">
        <div style="position: relative; z-index: 2;">
            <h3 class="fw-bold mb-1 text-white"><i class='bx bx-joystick me-2'></i> Event Challenges</h3>
            <p class="mb-0 opacity-75">Kelola misi untuk event: <strong>{{ $event->name }}</strong></p>
        </div>
        <div style="position: relative; z-index: 2; display: flex; gap: 10px;">
            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-light fw-bold shadow-sm">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="bx bx-plus-circle me-1"></i> Tambah Challenge
            </a>
        </div>
        <i class='bx bx-game header-decoration'></i>
    </div>

    {{-- 2. MODERN TABLE --}}
    <div class="card table-card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="table-header-row">
                    <tr>
                        <th class="ps-4">Nama Challenge</th>
                        <th>Reward (Price)</th>
                        <th>Deskripsi</th>
                        <th>Lampiran</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0 bg-white">
                    @forelse($challenges as $challenge)
                    <tr class="challenge-row">
                        <td class="ps-4">
                            <div class="d-flex align-items-center py-2">
                                <div class="avatar avatar-sm bg-label-info me-3 rounded p-1">
                                    <i class='bx bx-trophy fs-4'></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $challenge->nama }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $challenge->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{-- Logic Warna Badge Harga --}}
                            @php
                                $priceColor = '#00a79d'; // Default Teal
                                if($challenge->price >= 700000) $priceColor = '#ff3e1d'; // Red for Hard
                                elseif($challenge->price >= 500000) $priceColor = '#ffab00'; // Yellow for Medium
                            @endphp
                            <span class="price-tag" style="color: {{ $priceColor }}; border-color: {{ $priceColor }}33; background: {{ $priceColor }}1a;">
                                SQ$ {{ number_format($challenge->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="d-inline-block text-truncate text-muted" style="max-width: 250px;" title="{{ $challenge->deskripsi }}">
                                {{ Str::limit($challenge->deskripsi ?? '-', 50) }}
                            </span>
                        </td>
                        <td>
                            @if($challenge->file_pdf)
                                <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-xs btn-outline-secondary rounded-pill">
                                    <i class="bx bxs-file-pdf me-1"></i> PDF
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.events.challenges.destroy', [$event->id, $challenge->id]) }}" method="POST" onsubmit="return confirm('Hapus challenge ini dari event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" data-bs-toggle="tooltip" title="Lepas dari Event">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="150" class="mb-3 grayscale opacity-50">
                            <h6 class="text-muted">Belum ada challenge di event ini.</h6>
                            <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-sm btn-primary mt-2">
                                Pilih dari Master Data
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
