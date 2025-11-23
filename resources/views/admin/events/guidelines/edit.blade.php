@extends('admin.layouts.contentNavbarLayout')

@section('title', "Edit Event Guideline")

@section('styles')
<style>
    .guideline-preview-card {
        transition: all 0.3s;
        border: 2px dashed #d9dee3;
    }
    .guideline-price-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
</style>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} / Guidelines /</span> Edit
</h4>

<div class="row">
    <!-- KOLOM KIRI: Preview Guideline Saat Ini -->
    <div class="col-md-5 mb-4">
        <h5 class="mb-3">Guideline Saat Ini</h5>

        <div class="card h-100 position-relative guideline-preview-card bg-lighter">
            <span class="badge bg-label-info guideline-price-badge">
                ${{ number_format($guideline->price, 0, ',', '.') }}
            </span>

            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-info">
                            <i class="bx bx-book-open fs-1"></i>
                        </span>
                    </div>
                </div>
                <h4 class="card-title mb-1">{{ $guideline->title }}</h4>
                <p class="card-text text-muted small">
                    {{ Str::limit($guideline->description, 100) ?? 'Tidak ada deskripsi.' }}
                </p>

                @if($guideline->file_pdf)
                    <a href="{{ asset('storage/'.$guideline->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-info mt-3">
                        <i class="bx bx-file me-1"></i> Lihat PDF
                    </a>
                @endif
            </div>
            <div class="card-footer text-center border-top p-3">
                <small class="text-danger fw-bold">Akan dihapus dari event ini</small>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Form Ganti Guideline -->
    <div class="col-md-7">
        <h5 class="mb-3">Ganti Dengan</h5>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Pergantian</h5>
                <small class="text-muted">Pilih guideline baru</small>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.guidelines.update', ['event' => $event->id, 'guideline' => $guideline->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label" for="guideline_id">Pilih Guideline Pengganti</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <select class="form-select form-select-lg" id="guideline_id" name="guideline_id" required>
                                <option value="" selected disabled>-- Silakan Pilih --</option>
                                @foreach($available_guidelines as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->title }} (Price: ${{ number_format($g->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($available_guidelines->isEmpty())
                            <div class="form-text text-warning mt-2">
                                <i class='bx bx-error-circle'></i> Tidak ada guideline lain yang tersedia.
                            </div>
                        @endif
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bx bx-error me-2"></i>
                        <div>
                            Tindakan ini akan mengganti guideline <strong>{{ $guideline->title }}</strong> dengan pilihan di atas.
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.events.guidelines.index', $event->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
