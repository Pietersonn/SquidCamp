@extends('admin.layouts.contentNavbarLayout')

@section('title', "Edit Mentor - $mentor->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-secondary: #23d2c3;
        --squid-light: #e0f2f1;
    }

    /* Custom Card Selection Styling */
    .group-select-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid transparent;
        cursor: pointer;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border-radius: 12px;
        position: relative;
        overflow: hidden; /* Penting agar indicator tidak keluar card */
    }

    /* Hover Effect */
    .group-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 167, 157, 0.15);
        border-color: rgba(0, 167, 157, 0.3);
    }

    /* Selected State (Active) */
    .group-select-card.selected {
        border-color: var(--squid-primary);
        background-color: #f0fdfa; /* Very Light Teal */
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.2);
    }

    /* --- PERBAIKAN INDIKATOR CENTANG --- */
    .check-indicator {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--squid-primary);
        color: white;
        width: 40px;
        height: 40px;
        border-bottom-left-radius: 16px; /* Lengkungan estetik di pojok */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0; /* Sembunyi default */
        transform: translate(100%, -100%); /* Geser keluar default */
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
        box-shadow: -2px 2px 5px rgba(0,0,0,0.1);
    }

    /* Saat Selected, Tampilkan Indicator */
    .group-select-card.selected .check-indicator {
        opacity: 1;
        transform: translate(0, 0); /* Masuk ke posisi */
    }

    .check-indicator i {
        font-size: 1.6rem;
        margin-bottom: 3px;
        margin-left: 3px;
    }

    /* Disabled/Other Mentor State */
    .group-select-card.taken {
        background-color: #fff5f5; /* Light Red */
        border-color: #ffe0e0;
        opacity: 0.9;
    }
    .group-select-card.taken:hover {
        border-color: #ff5b5c;
        box-shadow: 0 5px 15px rgba(255, 91, 92, 0.15);
    }

    /* Icon Styling */
    .group-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.4rem;
        transition: 0.3s;
    }

    .group-select-card:hover .group-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Tombol Custom */
    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
    }
    .btn-squid:hover {
        background-color: #008f85;
        border-color: #008f85;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
    }

    /* Stats Box */
    .stats-box {
        background: linear-gradient(135deg, var(--squid-primary) 0%, #4db6ac 100%);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-9">
        <div class="card mb-4 border-0 shadow-sm">

            {{-- Header Card --}}
            <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white py-3">
                <div>
                    <h5 class="mb-1 fw-bold" style="color: var(--squid-primary);">Edit Penugasan</h5>
                    <div class="d-flex align-items-center text-muted small">
                        <span class="badge bg-label-teal me-2" style="color: var(--squid-primary); background-color: var(--squid-light);">
                            <i class="bx bx-user-voice"></i> Mentor
                        </span>
                        <strong class="text-dark fs-6">{{ $mentor->name }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.events.mentors.index', $event->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('admin.events.mentors.update', ['event' => $event->id, 'mentor' => $mentor->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body pt-4" style="background-color: #fdfdfd;">

                    {{-- Statistik Seleksi --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded-3 stats-box shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-initial rounded-circle bg-white" style="color: var(--squid-primary);">
                                    <i class='bx bx-check fs-3'></i>
                                </span>
                            </div>
                            <div>
                                <small class="d-block fw-bold text-uppercase opacity-75">Kelompok Dipilih</small>
                                <div class="d-flex align-items-baseline">
                                    <span class="fs-3 fw-bold me-1" id="selected-count">0</span>
                                    <span class="opacity-75">/ {{ $groups->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-uppercase opacity-75">Total Binaan Saat Ini</small>
                            <strong class="fs-4">{{ count($assignedGroupIds) }}</strong> <small>Groups</small>
                        </div>
                    </div>

                    <h6 class="text-muted text-uppercase fw-bold mb-3 small ps-1">
                        <i class="bx bx-grid-alt me-1"></i> Daftar Kelompok Tersedia
                    </h6>

                    @if($groups->isEmpty())
                        <div class="text-center p-5 border border-dashed rounded bg-white">
                            <div class="mb-3">
                                <span class="badge p-3 rounded-circle" style="background-color: var(--squid-light); color: var(--squid-primary);">
                                    <i class="bx bx-group fs-1"></i>
                                </span>
                            </div>
                            <h6 class="text-muted mb-0">Belum ada kelompok di event ini.</h6>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($groups as $group)
                                @php
                                    $isChecked = in_array($group->id, $assignedGroupIds);
                                    $hasOtherMentor = $group->mentor_id && $group->mentor_id != $mentor->id;

                                    // Warna Icon Random
                                    $colors = ['primary', 'success', 'warning', 'info', 'danger', 'dark'];
                                    $bgColors = ['#e7e7ff', '#e8fadf', '#fff2d6', '#d7f5fc', '#ffe0db', '#444'];
                                    $textColors = ['#696cff', '#71dd37', '#ffab00', '#03c3ec', '#ff3e1d', '#fff'];

                                    $idx = $group->id % count($colors);
                                    $bgColor = $bgColors[$idx];
                                    $textColor = $textColors[$idx];
                                @endphp

                                <div class="col-md-6">
                                    <div class="p-3 group-select-card h-100 {{ $isChecked ? 'selected' : '' }} {{ $hasOtherMentor ? 'taken' : '' }}"
                                         onclick="toggleCheckbox('group_{{ $group->id }}')">

                                        {{-- HTML INDICATOR YANG BENAR --}}
                                        <div class="check-indicator">
                                            <i class='bx bx-check'></i>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            {{-- Checkbox Hidden (Controlled by JS/Click) --}}
                                            <div class="d-none">
                                                <input class="form-check-input group-checkbox" type="checkbox"
                                                    name="group_ids[]"
                                                    value="{{ $group->id }}"
                                                    id="group_{{ $group->id }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                />
                                            </div>

                                            {{-- Group Content --}}
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="group-icon me-3" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                            <i class='bx bx-group'></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold d-block text-dark fs-6">{{ $group->name }}</span>
                                                            <small class="text-muted">{{ $group->members_count ?? 0 }} Anggota</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Status Badges --}}
                                                <div class="mt-2 ps-1">
                                                    @if($isChecked)
                                                        <span class="badge rounded-pill status-badge" style="background-color: var(--squid-primary);">
                                                            <i class="bx bx-check me-1"></i> Milik Mentor Ini
                                                        </span>
                                                    @elseif($hasOtherMentor)
                                                        <span class="badge bg-label-danger rounded-pill" data-bs-toggle="tooltip" title="Mentor: {{ $group->mentor->name }}">
                                                            <i class="bx bx-x-circle me-1"></i> Dibimbing: {{ strtok($group->mentor->name, ' ') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-label-success rounded-pill">
                                                            <i class="bx bx-check-circle me-1"></i> Available
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-white d-flex justify-content-end border-top py-3">
                    <a href="{{ route('admin.events.mentors.index', $event->id) }}" class="btn btn-label-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-squid px-4 shadow-sm">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Interaktif --}}
<script>
    function toggleCheckbox(id) {
        const checkbox = document.getElementById(id);
        if(!checkbox) return;

        // Toggle Checkbox
        checkbox.checked = !checkbox.checked;

        // Cari Card Parent
        // Kita cari elemen .group-select-card terdekat (naik ke atas)
        // Karena input hidden ada di dalam .d-none, kita perlu naik beberapa level
        // Struktur: card -> flex -> d-none -> input
        const card = checkbox.closest('.group-select-card');

        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        updateUI();
    }

    function updateUI() {
        const checkboxes = document.querySelectorAll('.group-checkbox');
        const counterDisplay = document.getElementById('selected-count');
        let count = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                count++;
                // Pastikan class 'selected' ada (untuk inisialisasi awal)
                const card = cb.closest('.group-select-card');
                if(card) card.classList.add('selected');
            }
        });

        if(counterDisplay) {
            counterDisplay.innerText = count;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize UI saat halaman dimuat
        updateUI();

        // Event listener tambahan untuk klik langsung pada card (double safety)
        const cards = document.querySelectorAll('.group-select-card');
        cards.forEach(card => {
            // Prevent click propagation dari elemen dalam jika diperlukan
        });
    });
</script>
@endsection
