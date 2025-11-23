@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Tambah Case')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Case</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.cases.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="form-label">Judul Case</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Market Expansion Strategy">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Tingkat Kesulitan</label>
                        <select name="difficulty" class="form-select" required>
                            <option value="">Pilih Tingkat Kesulitan</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Upload PDF (Studi Kasus)</label>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                        <div class="form-text">Format: PDF, Maks: 5MB</div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail case..."></textarea>
                    </div>

                    <button class="btn btn-primary mt-3">
                        <i class="bx bx-save me-1"></i> Simpan
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
