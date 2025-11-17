@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Event Management')
@section('styles')
<style>
    .card-hover-animation {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        z-index: 1;
    }
    .card-hover-animation:hover {
        transform: scale(1.03); /* Membesar 3% */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 10;
    }
    .card .dropdown {
        position: relative;
        z-index: 11;
    }

    /* KELAS BARU: Untuk area link di dalam card */
    .card-link-wrapper {
        text-decoration: none;
        color: inherit; /* Warisi warna teks dari card */
        display: block;
        flex-grow: 1; /* (PENTING) Membuat area link ini mengisi ruang kosong */
    }
</style>
@endsection
@section('content')

<div class="mb-3">
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        + Buat Event Baru
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    @forelse ($events as $event)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 card-hover-animation">
                <div class="card-body d-flex flex-column p-0">
                    <a href="{{ route('admin.events.show', $event->id) }}" class="card-link-wrapper">

                        @if ($event->banner_image_path)
                            <img class="card-img-top" src="{{ asset('storage/' . $event->banner_image_path) }}" alt="{{ $event->name }} Banner" style="height: 300px; object-fit: cover;">
                        @else
                            <img class="card-img-top" src="{{ asset('assets/img/elements/2.png') }}" alt="Placeholder Banner" style="height: 300px; object-fit: cover;">
                        @endif

                        {{-- KONTEN (dengan padding manual) --}}
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">{{ $event->name }}</h5>
                                @if ($event->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </div>
                            <h6 class="card-subtitle text-muted mb-3">{{ $event->instansi ?? 'Umum' }}</h6>

                            {{-- TANGGAL (tanpa "Jadwal Event") --}}
                            <p class="card-text">
                                <i class="bx bx-calendar me-1"></i>
                                @if ($event->event_date)
                                    {{ $event->event_date->translatedFormat('l, d F Y') }}
                                @else
                                    <span class="text-danger">Belum diatur</span>
                                @endif
                            </p>
                        </div>
                    </a>

                    <div class="mt-auto d-flex justify-content-end p-3 pt-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button" id="cardOpt{{ $event->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $event->id }}">
                                <a class="dropdown-item" href="{{ route('admin.events.edit', $event->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>
                                <a class="dropdown-item text-danger" href="#"
                                   onclick="event.preventDefault();
                                            if(confirm('Yakin hapus event ini?')) {
                                                document.getElementById('delete-form-{{ $event->id }}').submit();
                                            }">
                                    <i class="bx bx-trash me-1"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" id="delete-form-{{ $event->id }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

    @empty
        <div class="col">
            <div class="alert alert-warning" role="alert">
                Belum ada event yang dibuat. Silakan <a href="{{ route('admin.events.create') }}">buat event baru</a>.
            </div>
        </div>
    @endforelse
</div>
@endsection
