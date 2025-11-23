@extends('admin.layouts.contentNavbarLayout')

@section('title', "Edit Event Case")

@section('styles')
<style>
    .case-preview-card {
        transition: all 0.3s;
        border: 2px dashed #ffab00; /* Warna warning */
    }
    .case-level-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
</style>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} / Cases /</span> Edit
</h4>

<div class="row">
    <!-- KOLOM KIRI: Preview Case Saat Ini -->
    <div class="col-md-5 mb-4">
        <h5 class="mb-3">Case Saat Ini</h5>

        <div class="card h-100 position-relative case-preview-card bg-lighter">
            <span class="badge bg-label-warning case-level-badge">
                {{ $case->difficulty ?? 'N/A' }}
            </span>

            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="bx bx-briefcase-alt-2 fs-1"></i>
                        </span>
                    </div>
                </div>
                <h4 class="card-title mb-1">{{ $case->title }}</h4>
                <p class="card-text text-muted small">
                    {{ Str::limit($case->description, 100) ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>
            <div class="card-footer text-center border-top p-3">
                <small class="text-danger fw-bold">Akan dihapus dari event ini</small>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Form Ganti Case -->
    <div class="col-md-7">
        <h5 class="mb-3">Ganti Dengan</h5>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Pergantian</h5>
                <small class="text-muted">Pilih case baru</small>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.cases.update', ['event' => $event->id, 'case' => $case->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label" for="new_case_id">Pilih Case Pengganti</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <select class="form-select form-select-lg" id="new_case_id" name="new_case_id" required>
                                <option value="" selected disabled>-- Silakan Pilih --</option>
                                @foreach($available_cases as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->title }} ({{ $c->difficulty ?? 'Normal' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($available_cases->isEmpty())
                            <div class="form-text text-warning mt-2">
                                <i class='bx bx-error-circle'></i> Tidak ada case lain yang tersedia.
                            </div>
                        @endif
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bx bx-error me-2"></i>
                        <div>
                            Tindakan ini akan mengganti case <strong>{{ $case->title }}</strong> dengan pilihan di atas.
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.events.cases.index', $event->id) }}" class="btn btn-outline-secondary">
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
