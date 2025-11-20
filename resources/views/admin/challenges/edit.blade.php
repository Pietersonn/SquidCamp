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
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $challenge->nama }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="300" {{ $challenge->kategori=='300'?'selected':'' }}>300</option>
                            <option value="500" {{ $challenge->kategori=='500'?'selected':'' }}>500</option>
                            <option value="700" {{ $challenge->kategori=='700'?'selected':'' }}>700</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">PDF Sebelumnya</label><br>
                        @if($challenge->file_pdf)
                            <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-secondary">
                                Lihat PDF
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Upload PDF Baru</label>
                        <input type="file" name="file_pdf" class="form-control">
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control">{{ $challenge->deskripsi }}</textarea>
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
