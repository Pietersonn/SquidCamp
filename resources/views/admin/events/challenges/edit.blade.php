@extends('admin.layouts.contentNavbarLayout')

@section('title', "Edit Event Challenge")

@section('styles')
<style>
    .challenge-preview-card {
        transition: all 0.3s;
        border: 2px dashed #d9dee3;
    }
    .challenge-reward-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
</style>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} / Challenges /</span> Edit
</h4>

<div class="row">
    <!-- KOLOM KIRI: Preview Challenge Saat Ini -->
    <div class="col-md-5 mb-4">
        <h5 class="mb-3">Challenge Saat Ini</h5>

        <div class="card h-100 position-relative challenge-preview-card bg-lighter">
            <span class="badge bg-label-success challenge-reward-badge">
                ${{ number_format($challenge->kategori, 0, ',', '.') }}
            </span>

            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-trophy fs-1"></i>
                        </span>
                    </div>
                </div>
                <h4 class="card-title mb-1">{{ $challenge->nama }}</h4>
                <p class="card-text text-muted small">
                    {{ Str::limit($challenge->deskripsi, 100) ?? 'Tidak ada deskripsi.' }}
                </p>

                @if($challenge->file_pdf)
                    <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-info mt-3">
                        <i class="bx bx-file me-1"></i> Lihat PDF Instruksi
                    </a>
                @endif
            </div>
            <div class="card-footer text-center border-top p-3">
                <small class="text-danger fw-bold">Akan dihapus dari event</small>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Form Ganti Challenge -->
    <div class="col-md-7">
        <h5 class="mb-3">Ganti Dengan</h5>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Pergantian</h5>
                <small class="text-muted">Pilih challenge baru</small>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.challenges.update', ['event' => $event->id, 'challenge' => $challenge->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label" for="challenge_id">Pilih Challenge Pengganti</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <select class="form-select form-select-lg" id="challenge_id" name="challenge_id" required>
                                <option value="" selected disabled>-- Silakan Pilih --</option>
                                @foreach($available_challenges as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->nama }} (Reward: ${{ number_format($c->kategori, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($available_challenges->isEmpty())
                            <div class="form-text text-warning mt-2">
                                <i class='bx bx-error-circle'></i> Tidak ada challenge lain yang tersedia (atau semua sudah dipilih).
                            </div>
                        @endif
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bx bx-error me-2"></i>
                        <div>
                            Tindakan ini akan mengganti challenge <strong>{{ $challenge->nama }}</strong> dengan pilihan di atas.
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.events.challenges.index', $event->id) }}" class="btn btn-outline-secondary">
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
