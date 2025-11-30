@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Edit Challenge')

@section('styles')
<style>
    /* Theme Colors */
    :root {
        --squid-primary: #00a79d;
        --squid-secondary: #00796b;
        --squid-light: #e0f2f1;
    }

    /* Input Focus State */
    .form-control:focus {
        border-color: var(--squid-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 167, 157, 0.15);
    }

    /* Difficulty Selector - Compact Pill Style */
    .difficulty-selector .form-check-input {
        display: none;
    }

    .difficulty-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #d9dee3;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
        color: #697a8d;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .difficulty-label:hover {
        border-color: var(--squid-primary);
        background-color: #f9fdfd;
    }

    /* Selected State */
    .difficulty-selector .form-check-input:checked + .difficulty-label {
        background-color: var(--squid-light);
        border-color: var(--squid-primary);
        color: var(--squid-secondary);
        box-shadow: 0 2px 6px rgba(0, 167, 157, 0.1);
    }

    .difficulty-icon {
        font-size: 1.2rem;
    }

    /* File Upload Box Simple */
    .upload-box {
        border: 2px dashed #d9dee3;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        background-color: #fcfcfc;
    }
    .upload-box:hover {
        border-color: var(--squid-primary);
        background-color: #fff;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">

                {{-- Card Header --}}
                <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center py-3" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 fw-bold" style="color: var(--squid-secondary);">
                        <i class='bx bx-edit me-2'></i>Edit Misi #{{ $challenge->id }}
                    </h5>
                    <a href="{{ route('admin.challenges.index') }}" class="btn btn-sm btn-label-secondary rounded-pill">
                        <i class='bx bx-arrow-back me-1'></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.challenges.update', $challenge->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- 1. Nama Challenge --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Nama Challenge</label>
                            <input type="text" name="nama" class="form-control" value="{{ $challenge->nama }}" required>
                        </div>

                        {{-- 2. Pilihan Kesulitan (Simple Pills) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Pilih Tingkat Kesulitan</label>
                            <div class="row g-2">
                                <div class="col-4 difficulty-selector">
                                    <input type="radio" name="price" id="tier1" value="300000" class="form-check-input" {{ $challenge->price == 300000 ? 'checked' : '' }}>
                                    <label for="tier1" class="difficulty-label">
                                        <i class='bx bx-target-lock difficulty-icon'></i>
                                        <span>Easy (300K)</span>
                                    </label>
                                </div>
                                <div class="col-4 difficulty-selector">
                                    <input type="radio" name="price" id="tier2" value="500000" class="form-check-input" {{ $challenge->price == 500000 ? 'checked' : '' }}>
                                    <label for="tier2" class="difficulty-label">
                                        <i class='bx bx-diamond difficulty-icon'></i>
                                        <span>Medium (500K)</span>
                                    </label>
                                </div>
                                <div class="col-4 difficulty-selector">
                                    <input type="radio" name="price" id="tier3" value="700000" class="form-check-input" {{ $challenge->price == 700000 ? 'checked' : '' }}>
                                    <label for="tier3" class="difficulty-label">
                                        <i class='bx bx-trophy difficulty-icon'></i>
                                        <span>Hard (700K)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Instruksi & Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="5" required>{{ $challenge->deskripsi }}</textarea>
                        </div>

                        {{-- 4. File Upload (Simple Area) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">File Pendukung (PDF)</label>

                            @if($challenge->file_pdf)
                                <div class="d-flex align-items-center mb-2 p-2 rounded bg-label-info">
                                    <i class='bx bxs-file-pdf fs-4 me-2 text-info'></i>
                                    <span class="small flex-grow-1 text-truncate">File saat ini tersedia.</span>
                                    <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-xs btn-info fw-bold">Lihat</a>
                                </div>
                            @endif

                            <div class="upload-box" onclick="document.getElementById('file_pdf').click()">
                                <i class='bx bx-cloud-upload fs-3 text-secondary mb-2'></i>
                                <p class="small text-muted mb-0">Klik untuk mengganti file lama (Max 2MB)</p>
                                <input type="file" name="file_pdf" id="file_pdf" class="d-none" accept=".pdf" onchange="showFileName(this)">
                                <p id="file-name" class="small fw-bold text-primary mt-2 mb-0 d-none"></p>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="d-grid pt-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: var(--squid-primary); border: none; border-radius: 8px;">
                                <i class='bx bx-save me-1'></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showFileName(input) {
        const nameDisplay = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            nameDisplay.textContent = "File baru terpilih: " + input.files[0].name;
            nameDisplay.classList.remove('d-none');
        } else {
            nameDisplay.classList.add('d-none');
        }
    }
</script>
@endsection
