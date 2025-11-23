@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Edit Challenge')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">Edit Challenge</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.challenges.update', $challenge->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="form-label">Nama Challenge</label>
                        <input type="text" name="nama" class="form-control" value="{{ $challenge->nama }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Reward (Kategori)</label>
                        <select name="kategori" class="form-control" required>
                            <option value="">Pilih Reward</option>
                            {{-- Cek value dari database (integer) --}}
                            <option value="300000" {{ $challenge->kategori == 300000 ? 'selected' : '' }}>$300,000</option>
                            <option value="500000" {{ $challenge->kategori == 500000 ? 'selected' : '' }}>$500,000</option>
                            <option value="700000" {{ $challenge->kategori == 700000 ? 'selected' : '' }}>$700,000</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">PDF Sebelumnya</label><br>
                        @if($challenge->file_pdf)
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bx bx-file me-1"></i> Lihat PDF
                                </a>
                                <span class="text-muted small fst-italic">Upload baru untuk mengganti</span>
                            </div>
                        @else
                            <span class="text-muted">- Tidak ada file -</span>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Upload PDF Baru (Opsional)</label>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4">{{ $challenge->deskripsi }}</textarea>
                    </div>

                    <button class="btn btn-primary mt-3">
                        <i class="bx bx-save me-1"></i> Update
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection
