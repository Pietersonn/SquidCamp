@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Cases - $event->name")

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
    .case-level-badge {
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
        <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Cases
    </h4>
    <a href="{{ route('admin.events.cases.create', $event->id) }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Pilih Case
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<div class="row">
  @forelse ($selected_cases as $case)
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 card-hover-animation position-relative">

        {{-- Badge Level/Difficulty (Jika ada kolom difficulty) --}}
        <span class="badge bg-warning case-level-badge">
            {{ $case->difficulty ?? 'General' }}
        </span>

        <div class="card-body d-flex flex-column p-0">

            {{-- Header Visual --}}
            <div class="bg-label-warning d-flex align-items-center justify-content-center rounded-top" style="height: 200px; width: 100%;">
                <i class="bx bx-briefcase-alt-2 text-warning" style="font-size: 5rem;"></i>
            </div>

            <div class="p-3 d-flex flex-column flex-grow-1">
                <h5 class="card-title mb-1">{{ $case->title }}</h5>
                <p class="card-text text-muted small mb-3">
                    {{ Str::limit($case->description, 100) ?? 'Tidak ada deskripsi.' }}
                </p>

                {{-- Info tambahan jika ada --}}
                <div class="mt-auto">
                    <small class="text-muted"><i class="bx bx-time me-1"></i> {{ $case->created_at->format('d M Y') }}</small>
                </div>
            </div>

            {{-- Actions Dropdown --}}
            <div class="border-top d-flex justify-content-end p-3 bg-light rounded-bottom">
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button" id="cardOpt{{ $case->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $case->id }}">
                        <a class="dropdown-item" href="{{ route('admin.events.cases.edit', ['event' => $event->id, 'case' => $case->id]) }}">
                            <i class="bx bx-refresh me-1"></i> Ganti Case
                        </a>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                           onclick="if(confirm('Yakin hapus case ini dari event?')) { document.getElementById('delete-form-{{ $case->id }}').submit(); }">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>

            <form id="delete-form-{{ $case->id }}" action="{{ route('admin.events.cases.destroy', ['event' => $event->id, 'case' => $case->id]) }}" method="POST" style="display: none;">
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
                        <i class="bx bx-briefcase" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4>Belum ada Case</h4>
                <p class="text-muted mb-4">Event ini belum memiliki case bisnis yang dipilih.</p>
                <a href="{{ route('admin.events.cases.create', $event->id) }}" class="btn btn-primary">
                    Pilih Case
                </a>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
