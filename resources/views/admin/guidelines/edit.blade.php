@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Edit Guideline')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">Edit Guideline</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.guidelines.update', $guideline->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" value="{{ $guideline->title }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Harga</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">$</span>
                            <input type="number" name="price" class="form-control" value="{{ $guideline->price }}">
                        </div>
                    </div>

                    {{-- Tampilkan PDF Lama --}}
                    <div class="mb-6">
                        <label class="form-label">PDF Sebelumnya</label><br>
                        @if($guideline->file_pdf)
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ asset('storage/'.$guideline->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bx bx-file me-1"></i> Lihat PDF
                                </a>
                                <span class="text-muted small fst-italic">Upload baru di bawah jika ingin mengganti</span>
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
                        <textarea name="description" class="form-control" rows="4">{{ $guideline->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bx bx-save me-1"></i> Update
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
