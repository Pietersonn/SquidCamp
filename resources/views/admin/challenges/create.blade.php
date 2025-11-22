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
                {{-- Pastikan Route ini sesuai --}}
                <form action="{{ route('admin.challenges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="form-label">Nama Challenge</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Red Light Green Light">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Reward (Kategori)</label>
                        <select name="kategori" class="form-control" required>
                            <option value="">Pilih Reward</option>
                            {{-- Value angka murni, Label format Dolar --}}
                            <option value="300000">$300,000</option>
                            <option value="500000">$500,000</option>
                            <option value="700000">$700,000</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Upload PDF (Instruksi)</label>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail challenge..."></textarea>
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
