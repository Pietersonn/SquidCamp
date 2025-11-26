@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Event Management')

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-hover: #008f85;
        --squid-light: #e0f2f1;
    }

    /* --- GOKIL EVENT CARD --- */
    .event-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 167, 157, 0.2);
    }

    /* Banner Image Wrapper */
    .event-banner-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        background-color: #f5f5f9;
    }

    .event-banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .event-card:hover .event-banner-img {
        transform: scale(1.05);
    }

    /* Overlay Gradient pada Banner */
    .event-banner-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60%;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        z-index: 1;
    }

    /* Status Badge yang Keren */
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        backdrop-filter: blur(4px);
    }
    .status-active {
        background: rgba(0, 167, 157, 0.9);
        color: #fff;
    }
    .status-inactive {
        background: rgba(108, 117, 125, 0.9);
        color: #fff;
    }

    /* Tanggal Floating di atas Banner */
    .date-badge {
        position: absolute;
        bottom: 15px;
        left: 15px;
        z-index: 2;
        color: #fff;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    /* Konten Body */
    .event-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .event-title {
        font-weight: 800;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        line-height: 1.3;
        transition: color 0.2s;
    }

    .event-card:hover .event-title {
        color: var(--squid-primary);
    }

    /* Tombol Aksi */
    .btn-action-rounded {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        color: #697a8d;
        background: #fff;
        transition: all 0.2s;
    }
    .btn-action-rounded:hover {
        background: var(--squid-light);
        color: var(--squid-primary);
        border-color: var(--squid-primary);
    }

    /* Link Wrapper Seluruh Card */
    .card-link-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }
</style>
@endsection

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">
            <i class="bx bx-calendar-event fs-3 me-2"></i>Event Management
        </h4>
        <span class="text-muted">Kelola semua event, timeline, dan konfigurasi.</span>
    </div>

    <a href="{{ route('admin.events.create') }}" class="btn btn-lg shadow-sm text-white" style="background-color: var(--squid-primary);">
        <i class="bx bx-plus me-1"></i> Buat Event Baru
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-check-circle fs-4 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="row g-4">
    @forelse ($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="event-card">

                {{-- Link Seluruh Card (Kecuali tombol aksi) --}}
                <a href="{{ route('admin.events.show', $event->id) }}" class="card-link-overlay"></a>

                {{-- Banner Image --}}
                <div class="event-banner-wrapper">
                    @if ($event->is_active)
                        <span class="status-badge status-active"><i class="bx bx-check-circle me-1"></i> Aktif</span>
                    @else
                        <span class="status-badge status-inactive"><i class="bx bx-power-off me-1"></i> Nonaktif</span>
                    @endif

                    <div class="event-banner-overlay"></div>

                    @if ($event->banner_image_path)
                        <img class="event-banner-img" src="{{ asset('storage/' . $event->banner_image_path) }}" alt="{{ $event->name }}">
                    @else
                        {{-- Placeholder Image Default --}}
                        <img class="event-banner-img" src="{{ asset('assets/img/backgrounds/1.jpg') }}" alt="Placeholder">
                    @endif

                    <div class="date-badge">
                        @if ($event->event_date)
                            <h5 class="mb-0 text-white fw-bold">{{ $event->event_date->format('d') }}</h5>
                            <small class="text-uppercase text-white-50">{{ $event->event_date->translatedFormat('M Y') }}</small>
                        @else
                            <small class="text-white-50 fst-italic">Tanggal belum diatur</small>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="event-body">
                    <h5 class="event-title text-truncate" title="{{ $event->name }}">{{ $event->name }}</h5>
                    <div class="d-flex align-items-center text-muted mb-3">
                        <i class="bx bx-buildings me-2"></i>
                        <small>{{ $event->instansi ?? 'Instansi Umum' }}</small>
                    </div>

                    {{-- Separator --}}
                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                        <a href="{{ route('admin.events.show', $event->id) }}" class="text-primary fw-bold text-decoration-none small">
                            Lihat Detail &rarr;
                        </a>

                        <div class="dropdown">
                            <button class="btn-action-rounded" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.events.edit', $event->id) }}">
                                        <i class="bx bx-edit-alt me-2 text-primary"></i> Edit Event
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin hapus event ini? Data terkait juga akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                            <i class="bx bx-trash me-2"></i> Hapus Event
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card text-center py-5 border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle" style="background-color: var(--squid-light);">
                            <i class="bx bx-calendar-plus" style="font-size: 4rem; color: var(--squid-primary);"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Belum ada Event</h3>
                    <p class="text-muted mb-4">
                        Mulai perjalanan dengan membuat event pertama Anda.
                    </p>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-lg text-white shadow-sm" style="background-color: var(--squid-primary);">
                        <i class="bx bx-plus me-1"></i> Buat Event Baru
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@endsection
