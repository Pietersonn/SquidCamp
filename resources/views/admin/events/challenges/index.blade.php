@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Challenges - $event->name")

@section('styles')
<style>
    .card-hover-animation {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        z-index: 1;
    }
    .card-hover-animation:hover {
        transform: scale(1.03);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 10;
    }
    /* Wrapper link agar area card bisa diklik */
    .card-link-wrapper {
        text-decoration: none;
        color: inherit;
        display: block;
        flex-grow: 1;
    }
    .challenge-reward-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    /* Dropdown agar tampil di atas elemen lain */
    .card .dropdown {
        position: relative;
        z-index: 11;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Challenges
    </h4>
    {{-- Tombol arahkan ke Create (Pilih Challenge) --}}
    <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Pilih Challenge
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<div class="row">
  {{-- PERBAIKAN: Gunakan $selected_challenges (sesuai controller) --}}
  @forelse ($selected_challenges as $challenge)
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 card-hover-animation position-relative">

        {{-- Badge Reward --}}
        <span class="badge bg-success challenge-reward-badge">
            ${{ number_format($challenge->kategori, 0, ',', '.') }}
        </span>

        <div class="card-body d-flex flex-column p-0">

            {{-- Placeholder Image / Header Visual --}}
            <div class="bg-label-primary d-flex align-items-center justify-content-center rounded-top" style="height: 200px; width: 100%;">
                @if($challenge->file_pdf)
                    <i class="bx bxs-file-pdf text-primary" style="font-size: 5rem;"></i>
                @else
                    <i class="bx bx-trophy text-primary" style="font-size: 5rem;"></i>
                @endif
            </div>

            <div class="p-3 d-flex flex-column flex-grow-1">
                <h5 class="card-title mb-1">{{ $challenge->nama }}</h5>
                <p class="card-text text-muted small mb-3">
                    {{ Str::limit($challenge->deskripsi, 80) ?? 'Tidak ada deskripsi.' }}
                </p>

                @if($challenge->file_pdf)
                    <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-info w-100 mt-auto">
                        <i class="bx bx-file me-1"></i> Lihat Instruksi PDF
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary w-100 mt-auto" disabled>
                        <i class="bx bx-x-circle me-1"></i> Tidak ada File
                    </button>
                @endif
            </div>

            {{-- Footer: Actions Dropdown (Mirip contoh Event Management) --}}
            <div class="mt-auto d-flex justify-content-end p-3 pt-0">
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button" id="cardOpt{{ $challenge->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $challenge->id }}">

                        {{-- Edit (Ganti Challenge) --}}
                        <a class="dropdown-item" href="{{ route('admin.events.challenges.edit', ['event' => $event->id, 'challenge' => $challenge->id]) }}">
                            <i class="bx bx-refresh me-1"></i> Ganti Challenge
                        </a>

                        {{-- Hapus --}}
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                           onclick="if(confirm('Yakin hapus challenge ini dari event?')) { document.getElementById('delete-form-{{ $challenge->id }}').submit(); }">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>

            {{-- Hidden Form untuk Delete --}}
            <form id="delete-form-{{ $challenge->id }}" action="{{ route('admin.events.challenges.destroy', ['event' => $event->id, 'challenge' => $challenge->id]) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-3">
                    <div class="badge bg-label-secondary p-3 rounded-circle">
                        <i class="bx bx-joystick" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4>Belum ada Challenge</h4>
                <p class="text-muted mb-4">Event ini belum memiliki challenge yang dipilih dari Master Data.</p>
                <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-primary">
                    Pilih Challenge Sekarang
                </a>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
