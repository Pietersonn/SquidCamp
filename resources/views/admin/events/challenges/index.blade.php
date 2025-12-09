@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Challenges - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-gold: #ffab00;
        --squid-danger: #ff3e1d;
    }

    /* --- TABLE STYLES --- */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .table-header-row th {
        background-color: #f8f9fa !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eee;
        color: #566a7f;
        padding: 15px 20px;
    }

    .challenge-row td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .challenge-row:last-child td {
        border-bottom: none;
    }

    .challenge-row:hover {
        background-color: #fafbfc;
    }

    /* --- BADGES --- */
    .price-badge {
        display: inline-block;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid transparent;
    }

    /* TIER 1: Harga Standar (Hijau) < 500k */
    .price-normal {
        background: #e0f2f1;
        color: var(--squid-primary);
        border-color: rgba(0, 167, 157, 0.2);
    }

    /* TIER 2: Harga Menengah (Kuning) >= 500k */
    .price-medium {
        background: #fff8e1;
        color: var(--squid-gold);
        border-color: rgba(255, 171, 0, 0.2);
    }

    /* TIER 3: Harga Tinggi (Merah) >= 700k */
    .price-hard {
        background: #ffe0db;
        color: var(--squid-danger);
        border-color: rgba(255, 62, 29, 0.2);
    }

    /* --- ACTION BUTTONS --- */
    .btn-icon-soft {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px;
        border: none;
        transition: all 0.2s;
        background: transparent;
        color: #888;
    }
    .btn-icon-soft:hover {
        background: #ffe0db;
        color: var(--squid-danger);
        transform: translateY(-2px);
    }

    /* PDF Button */
    .btn-file-pdf {
        padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
        background: #fff0f0; color: #ff3e1d; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid #ff3e1d20;
    }
    .btn-file-pdf:hover { background: #ff3e1d; color: white; }

</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #008f85;">
            <i class='bx bx-joystick me-2'></i>Event Challenges
        </h4>
        <span class="text-muted">Event: {{ $event->name }}</span>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-primary" style="background-color: var(--squid-primary); border:none;">
            <i class="bx bx-plus me-1"></i> Tambah Challenge
        </a>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
            <thead class="table-header-row">
                <tr>
                    <th class="ps-4">Nama Challenge</th>
                    <th>Reward (Price)</th>
                    <th>Deskripsi</th>
                    <th>Lampiran</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($challenges as $challenge)
                    <tr class="challenge-row">
                        {{-- 1. Nama --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-label-primary me-3 rounded p-1 d-flex align-items-center justify-content-center">
                                    <i class='bx bx-trophy fs-4'></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $challenge->nama }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $challenge->id }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Reward --}}
                        <td>
                            @php
                                $priceClass = 'price-normal'; // Default Hijau
                                if ($challenge->price >= 700000) {
                                    $priceClass = 'price-hard'; // Merah
                                } elseif ($challenge->price >= 500000) {
                                    $priceClass = 'price-medium'; // Kuning
                                }
                            @endphp
                            <span class="price-badge {{ $priceClass }}">
                                $ {{ number_format($challenge->price, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- 3. Deskripsi --}}
                        <td>
                            <span class="d-inline-block text-truncate text-muted" style="max-width: 250px;" title="{{ $challenge->deskripsi }}">
                                {{ Str::limit($challenge->deskripsi ?? '-', 50) }}
                            </span>
                        </td>

                        {{-- 4. Lampiran --}}
                        <td>
                            @if($challenge->file_pdf)
                                <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn-file-pdf">
                                    <i class="bx bxs-file-pdf"></i> PDF
                                </a>
                            @else
                                <span class="text-muted small fst-italic">-</span>
                            @endif
                        </td>

                        {{-- 5. Aksi --}}
                        <td class="text-center">
                            <form action="{{ route('admin.events.challenges.destroy', [$event->id, $challenge->id]) }}" method="POST" onsubmit="return confirm('Hapus challenge ini dari event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon-soft" data-bs-toggle="tooltip" title="Hapus">
                                    <i class="bx bx-trash fs-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="mb-3">
                                <div class="avatar avatar-xl bg-label-secondary rounded-circle mx-auto p-3">
                                    <i class='bx bx-ghost fs-1'></i>
                                </div>
                            </div>
                            <h6 class="text-muted mb-1">Belum ada challenge di event ini.</h6>
                            <p class="small text-muted mb-3">Tambahkan misi agar peserta bisa bermain.</p>
                            <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-plus me-1"></i> Tambah Data
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
