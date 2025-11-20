@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Tambah Challenge')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">Tambah Challenge</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.challenges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="300">300</option>
                            <option value="500">500</option>
                            <option value="700">700</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Upload PDF</label>
                        <input type="file" name="file_pdf" class="form-control">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"></textarea>
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
