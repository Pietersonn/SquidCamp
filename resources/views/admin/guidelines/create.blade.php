@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Tambah Guideline')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Guideline</h5>
            </div>

            <div class="card-body">
                {{-- PENTING: Tambahkan enctype untuk upload file --}}
                <form action="{{ route('admin.guidelines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="form-label" for="gl-title">Judul</label>
                        <input id="gl-title" name="title" type="text" class="form-control" value="{{ old('title') }}" placeholder="Judul guideline" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="gl-price">Harga</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">$</span>
                            <input id="gl-price" name="price" type="number" class="form-control" value="{{ old('price') }}" placeholder="Contoh: 50000">
                        </div>
                        <div class="form-text">Masukkan angka saja (misal: 50000 untuk $50,000)</div>
                    </div>

                    {{-- Input File PDF --}}
                    <div class="mb-6">
                        <label class="form-label" for="file_pdf">Upload PDF</label>
                        <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept=".pdf">
                        <div class="form-text">Format: PDF, Maks: 5MB</div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="gl-desc">Deskripsi</label>
                        <textarea id="gl-desc" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bx bx-save me-1"></i> Simpan
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
