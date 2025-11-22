@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Guidelines - $event->name")

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
    .guideline-price-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .card .dropdown {
        position: relative;
        z-index: 11;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Guidelines
    </h4>
    <a href="{{ route('admin.events.guidelines.create', $event->id) }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Pilih Guideline
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<div class="row">
  @forelse ($selected_guidelines as $guideline)
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 card-hover-animation position-relative">

        {{-- Badge Price --}}
        <span class="badge bg-info guideline-price-badge">
            ${{ number_format($guideline->price, 0, ',', '.') }}
        </span>

        <div class="card-body d-flex flex-column p-0">

            {{-- Header Visual --}}
            <div class="bg-label-info d-flex align-items-center justify-content-center rounded-top" style="height: 200px; width: 100%;">
                @if($guideline->file_pdf)
                    <i class="bx bxs-file-pdf text-info" style="font-size: 5rem;"></i>
                @else
                    <i class="bx bx-book-open text-info" style="font-size: 5rem;"></i>
                @endif
            </div>

            <div class="p-3 d-flex flex-column flex-grow-1">
                <h5 class="card-title mb-1">{{ $guideline->title }}</h5>
                <p class="card-text text-muted small mb-3">
                    {{ Str::limit($guideline->description, 80) ?? 'Tidak ada deskripsi.' }}
                </p>

                @if($guideline->file_pdf)
                    <a href="{{ asset('storage/'.$guideline->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-info w-100 mt-auto">
                        <i class="bx bx-file me-1"></i> Lihat PDF
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary w-100 mt-auto" disabled>
                        <i class="bx bx-x-circle me-1"></i> Tidak ada File
                    </button>
                @endif
            </div>

            {{-- Actions Dropdown --}}
            <div class="mt-auto d-flex justify-content-end p-3 pt-0">
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button" id="cardOpt{{ $guideline->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $guideline->id }}">
                        <a class="dropdown-item" href="{{ route('admin.events.guidelines.edit', ['event' => $event->id, 'guideline' => $guideline->id]) }}">
                            <i class="bx bx-refresh me-1"></i> Ganti Guideline
                        </a>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                           onclick="if(confirm('Yakin hapus guideline ini dari event?')) { document.getElementById('delete-form-{{ $guideline->id }}').submit(); }">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>

            <form id="delete-form-{{ $guideline->id }}" action="{{ route('admin.events.guidelines.destroy', ['event' => $event->id, 'guideline' => $guideline->id]) }}" method="POST" style="display: none;">
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
                        <i class="bx bx-book" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4>Belum ada Guideline</h4>
                <p class="text-muted mb-4">Event ini belum memiliki guideline.</p>
                <a href="{{ route('admin.events.guidelines.create', $event->id) }}" class="btn btn-primary">
                    Pilih Guideline
                </a>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
