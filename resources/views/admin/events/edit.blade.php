@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Edit Event: ' . $event->name)

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
        --squid-hover: #008f85;
    }

    /* --- MODERN CARD --- */
    .gokil-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    }

    /* --- BANNER PREVIEW --- */
    .banner-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border: 2px dashed #d9dee3;
        transition: all 0.3s ease;
        background: #f8f9fa;
        min-height: 200px;
    }
    .banner-wrapper:hover {
        border-color: var(--squid-primary);
    }
    .current-banner {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }
    .upload-btn-wrapper {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }

    /* --- TIMELINE PHASE STYLING --- */
    .phase-card {
        background: #fff;
        border: 1px solid #eee;
        border-left: 4px solid var(--squid-primary);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        transition: 0.3s;
        position: relative;
    }
    .phase-card:hover {
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.1);
        transform: translateX(5px);
    }
    .phase-icon {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 35px;
        height: 35px;
        background: var(--squid-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .phase-title {
        margin-left: 20px;
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    /* --- FORM ELEMENTS --- */
    .form-control:focus {
        border-color: var(--squid-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 167, 157, 0.25);
    }
    .form-label {
        font-weight: 600;
        color: #566a7f;
        font-size: 0.85rem;
    }

    /* --- TOGGLE SWITCH CUSTOM --- */
    .form-check-input:checked {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
    }

    /* --- BUTTONS --- */
    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
        font-weight: 600;
        padding: 10px 24px;
        transition: 0.3s;
    }
    .btn-squid:hover {
        background-color: var(--squid-hover);
        border-color: var(--squid-hover);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')

<form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Header Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">Edit Event</h4>
            <span class="text-muted">Perbarui detail dan jadwal event.</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-x me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-squid">
                <i class="bx bx-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <div class="row g-4">
        {{-- KOLOM KIRI: Info & Visual --}}
        <div class="col-lg-4">
            <div class="gokil-card h-100 p-4">

                <h6 class="fw-bold text-muted mb-3 text-uppercase small">Visual & Branding</h6>

                {{-- Banner Upload --}}
                <div class="mb-4">
                    <label class="form-label d-block mb-2">Banner Event</label>
                    <div class="banner-wrapper">
                        @if ($event->banner_image_path)
                            <img src="{{ asset('storage/' . $event->banner_image_path) }}" alt="Banner Preview" class="current-banner" id="bannerPreview">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted flex-column py-5">
                                <i class="bx bx-image fs-1 mb-2"></i>
                                <small>Belum ada banner</small>
                            </div>
                            <img src="" alt="" class="current-banner d-none" id="bannerPreview">
                        @endif

                        {{-- Hidden Input --}}
                        <input type="file" id="banner_image" name="banner_image" class="d-none" accept="image/*" onchange="previewImage(event)">

                        <div class="upload-btn-wrapper">
                            <label for="banner_image" class="btn btn-sm btn-primary shadow-sm" style="cursor: pointer;">
                                <i class="bx bx-camera me-1"></i> Ganti
                            </label>
                        </div>
                    </div>
                    <div class="form-text small mt-2">Format: JPG, PNG. Max: 2MB.</div>
                </div>

                <hr class="my-4 text-light">

                {{-- Info Dasar --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Event <span class="text-danger">*</span></label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-star"></i></span>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $event->name) }}" placeholder="Contoh: SquidCamp Batch 1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="instansi" class="form-label">Instansi Penyelenggara</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-building"></i></span>
                        <input type="text" class="form-control" id="instansi" name="instansi" value="{{ old('instansi', $event->instansi) }}" placeholder="Contoh: Telkom Indonesia">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="event_date" class="form-label">Tanggal Pelaksanaan</label>
                    <input class="form-control" type="date" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '') }}">
                </div>

                <div class="mt-4 p-3 rounded bg-label-secondary">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $event->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold ms-2" for="is_active">Publikasikan Event (Aktif)</label>
                    </div>
                    <small class="text-muted d-block mt-1 ms-5">Jika nonaktif, peserta tidak dapat mengakses event ini.</small>
                </div>

            </div>
        </div>

        {{-- KOLOM KANAN: Timeline & Timer --}}
        <div class="col-lg-8">
            <div class="gokil-card h-100 p-4">
                <h6 class="fw-bold text-muted mb-4 text-uppercase small">
                    <i class="bx bx-time-five me-1"></i> Konfigurasi Timeline Fase
                </h6>

                @php
                    $formatDateTime = fn($date) => $date ? $date->format('Y-m-d\TH:i') : '';
                @endphp

                {{-- PHASE 1: CHALLENGE --}}
                <div class="phase-card">
                    <div class="phase-icon"><i class="bx bx-joystick"></i></div>
                    <div class="phase-title">Squid Challenge</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Mulai</label>
                            <input class="form-control" type="datetime-local" name="challenge_start_time" value="{{ old('challenge_start_time', $formatDateTime($event->challenge_start_time)) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Selesai</label>
                            <input class="form-control" type="datetime-local" name="challenge_end_time" value="{{ old('challenge_end_time', $formatDateTime($event->challenge_end_time)) }}">
                        </div>
                    </div>
                </div>

                {{-- PHASE 2: CASE --}}
                <div class="phase-card">
                    <div class="phase-icon" style="background: #008f85;"><i class="bx bx-briefcase-alt-2"></i></div>
                    <div class="phase-title">Squid Case</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Mulai</label>
                            <input class="form-control" type="datetime-local" name="case_start_time" value="{{ old('case_start_time', $formatDateTime($event->case_start_time)) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Selesai</label>
                            <input class="form-control" type="datetime-local" name="case_end_time" value="{{ old('case_end_time', $formatDateTime($event->case_end_time)) }}">
                        </div>
                    </div>
                </div>

                {{-- PHASE 3: SHOW --}}
                <div class="phase-card">
                    <div class="phase-icon" style="background: #00796b;"><i class="bx bx-tv"></i></div>
                    <div class="phase-title">Squid Show</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Mulai</label>
                            <input class="form-control" type="datetime-local" name="show_start_time" value="{{ old('show_start_time', $formatDateTime($event->show_start_time)) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Waktu Selesai</label>
                            <input class="form-control" type="datetime-local" name="show_end_time" value="{{ old('show_end_time', $formatDateTime($event->show_end_time)) }}">
                        </div>
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-center mt-4 mb-0" role="alert">
                    <i class="bx bx-info-circle me-2 fs-4"></i>
                    <div>
                        Pastikan pengaturan waktu tidak tumpang tindih antar fase agar alur event berjalan lancar.
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

{{-- Simple Script for Image Preview --}}
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('bannerPreview');
            output.src = reader.result;
            output.classList.remove('d-none');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
