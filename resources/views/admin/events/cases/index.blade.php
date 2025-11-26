@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Cases - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
        --squid-dark: #00796b;
    }

    /* --- GOKIL CARD STYLES --- */
    .gokil-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .gokil-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.2);
    }

    /* Header Visual dengan Ikon Besar */
    .visual-header {
        height: 150px;
        background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: 0.3s;
    }

    /* Pattern Background */
    .visual-header::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: radial-gradient(var(--squid-primary) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    /* Ikon Utama (Briefcase) */
    .visual-header i.main-icon {
        font-size: 5rem;
        color: var(--squid-primary);
        filter: drop-shadow(0 4px 6px rgba(0, 167, 157, 0.2));
        transition: transform 0.3s ease;
        z-index: 1;
    }

    .gokil-card:hover .visual-header i.main-icon {
        transform: scale(1.15) rotate(-5deg);
    }

    /* Difficulty Badge (Pojok Kanan Atas) */
    .difficulty-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 800;
        font-size: 0.75rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Action Menu (Pojok Kiri Atas) */
    .action-menu {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 5;
    }
    .btn-icon-glass {
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        border: none;
        color: #666;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-icon-glass:hover {
        background: #fff;
        color: var(--squid-primary);
        transform: scale(1.1);
    }

    /* --- CONTENT STYLING (TEXT) --- */
    .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    /* Wrapper Teks dengan Garis Dekorasi */
    .text-content-wrapper {
        padding-left: 15px; /* Memberi jarak dari kiri */
        border-left: 4px solid var(--squid-light); /* Garis Vertikal */
        margin-bottom: 15px;
        transition: border-color 0.3s;
    }

    .gokil-card:hover .text-content-wrapper {
        border-left-color: var(--squid-primary); /* Ubah warna garis saat hover */
    }

    .case-title {
        font-weight: 800;
        color: #333;
        margin-bottom: 6px;
        font-size: 1.1rem;
        line-height: 1.4;
    }

    .case-desc {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 0;
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
        background-color: var(--squid-dark);
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.3);
        transform: translateY(-2px);
    }

    /* Tombol Outline PDF */
    .btn-pdf-outline {
        background-color: transparent;
        color: var(--squid-primary);
        border: 1px solid var(--squid-primary);
        transition: all 0.2s;
        font-weight: 600;
    }
    .btn-pdf-outline:hover {
        background-color: var(--squid-primary);
        color: #fff;
    }
</style>
@endsection

@section('content')

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">
            <i class="bx bx-briefcase-alt-2 fs-3 me-2"></i>Event Cases
        </h4>
        <span class="text-muted">Event: <strong class="text-dark">{{ $event->name }}</strong></span>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary shadow-sm btn-lg">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.events.cases.create', $event->id) }}" class="btn btn-squid shadow-sm btn-lg">
            <i class="bx bx-plus-circle me-1"></i> Pilih Case
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
  @forelse ($selected_cases as $case)
    @php
        // Logic Ikon & Warna berdasarkan Difficulty
        $diffColor = 'primary';
        $diffIcon = 'bx-bar-chart-alt-2';

        if($case->difficulty == 'Easy') {
            $diffColor = 'success'; // Hijau
            $diffIcon = 'bx-smile';
        } elseif($case->difficulty == 'Medium') {
            $diffColor = 'warning'; // Kuning/Oranye
            $diffIcon = 'bx-meh';
        } elseif($case->difficulty == 'Hard') {
            $diffColor = 'danger'; // Merah
            $diffIcon = 'bx-hot';
        }
    @endphp

    <div class="col-md-6 col-lg-4">
      <div class="gokil-card h-100">

        {{-- Menu Aksi (Edit/Hapus) --}}
        <div class="action-menu dropdown">
            <button class="btn-icon-glass" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <ul class="dropdown-menu shadow-sm border-0">
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.events.cases.destroy', ['event' => $event->id, 'case' => $case->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus case ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                            <i class="bx bx-trash me-2"></i> Hapus
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Difficulty Badge --}}
        <div class="difficulty-badge bg-label-{{ $diffColor }} text-{{ $diffColor }}">
            <i class="bx {{ $diffIcon }}"></i> {{ $case->difficulty ?? 'General' }}
        </div>

        {{-- Visual Header --}}
        <div class="visual-header">
            <i class="bx bx-briefcase-alt-2 main-icon"></i>
        </div>

        <div class="card-body">

            {{-- Text Content Wrapper (Dengan Garis Dekorasi) --}}
            <div class="text-content-wrapper">
                <h5 class="case-title text-truncate">{{ $case->title }}</h5>
                <p class="case-desc">
                    {{ Str::limit($case->description, 100) ?? 'Tidak ada deskripsi untuk case ini.' }}
                </p>
            </div>

            <div class="flex-grow-1"></div> {{-- Spacer --}}

            {{-- Tombol PDF --}}
            <div class="d-grid mt-3">
                @if($case->file_pdf)
                    <a href="{{ asset('storage/'.$case->file_pdf) }}" target="_blank" class="btn btn-pdf-outline py-2 btn-sm rounded-pill">
                        <i class="bx bxs-file-pdf me-1"></i> Lihat Dokumen Case
                    </a>
                @else
                    <button class="btn btn-light text-muted py-2 btn-sm rounded-pill" disabled style="cursor: not-allowed;">
                        <i class="bx bx-x-circle me-1"></i> PDF Tidak Tersedia
                    </button>
                @endif
            </div>

            <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-center text-muted">
                <small style="font-size: 0.7rem;">
                    <i class="bx bx-calendar me-1"></i> Ditambahkan: {{ $case->created_at->format('d M Y') }}
                </small>
            </div>
        </div>

      </div>
    </div>
  @empty
    {{-- Empty State --}}
    <div class="col-12">
        <div class="card text-center py-5 border-0 shadow-sm bg-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle" style="background-color: var(--squid-light);">
                        <i class="bx bx-briefcase-alt-2" style="font-size: 3.5rem; color: var(--squid-primary);"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Belum ada Business Case</h3>
                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                    Pilih studi kasus yang menantang untuk dipecahkan oleh peserta event ini.
                </p>
                <a href="{{ route('admin.events.cases.create', $event->id) }}" class="btn btn-lg btn-squid shadow-sm">
                    <i class="bx bx-plus me-1"></i> Pilih Case Sekarang
                </a>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
