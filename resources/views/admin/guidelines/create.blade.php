@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Tambah Guideline')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Guideline</h5>
                <small class="text-body">Form untuk membuat guideline baru</small>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.guidelines.store') }}" method="POST">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-6">
                        <label class="form-label" for="gl-title">Judul</label>
                        <input
                            id="gl-title"
                            name="title"
                            type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Masukkan judul guideline"
                            required>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label class="form-label" for="gl-desc">Deskripsi</label>
                        <textarea
                            id="gl-desc"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Masukkan deskripsi guideline...">{{ old('description') }}</textarea>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        <label class="form-label" for="gl-price">Harga</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input
                                id="gl-price"
                                name="price"
                                type="number"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}"
                                placeholder="Masukkan harga (boleh kosong)">
                        </div>

                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bx bx-plus me-1"></i> Buat Guideline
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
