@extends('admin.layouts.contentNavbarLayout')

@section('title', "Add Mentor - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
    }

    /* --- GROUP SELECTION CARD --- */
    .group-select-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid transparent;
        cursor: pointer;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .group-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 167, 157, 0.15);
        border-color: rgba(0, 167, 157, 0.3);
    }

    .group-select-card.selected {
        border-color: var(--squid-primary);
        background-color: #f0fdfa;
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.2);
    }

    /* Indicator Centang */
    .check-indicator {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--squid-primary);
        color: #fff;
        width: 40px;
        height: 40px;
        border-bottom-left-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: translate(100%, -100%);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
        box-shadow: -2px 2px 5px rgba(0,0,0,0.1);
    }

    .group-select-card.selected .check-indicator {
        opacity: 1;
        transform: translate(0, 0);
    }

    .check-indicator i { font-size: 1.6rem; margin-bottom: 3px; margin-left: 3px; }

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

    /* Stats Box */
    .stats-box {
        background: linear-gradient(135deg, var(--squid-primary) 0%, #4db6ac 100%);
        color: white;
    }

    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
        transition: 0.3s;
    }
    .btn-squid:hover {
        background-color: #008f85;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">

            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white py-3">
                <div>
                    <h5 class="mb-1 fw-bold" style="color: var(--squid-primary);">Tambah Mentor Baru</h5>
                    <small class="text-muted">Event: {{ $event->name }}</small>
                </div>
                <a href="{{ route('admin.events.mentors.index', $event->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body pt-4">

                @if($availableMentors->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl bg-label-warning rounded-circle mx-auto mb-3">
                            <i class="bx bx-info-circle fs-1"></i>
                        </div>
                        <h5 class="mb-2">Semua Mentor Sudah Terdaftar</h5>
                        <p class="text-muted">Tidak ada user dengan role 'Mentor' yang tersedia untuk ditambahkan ke event ini.</p>
                        <a href="{{ route('admin.events.mentors.index', $event->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                @else
                    <form action="{{ route('admin.events.mentors.store', $event->id) }}" method="POST">
                        @csrf

                        {{-- STEP 1: PILIH USER MENTOR --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase text-muted small">Langkah 1: Pilih User</label>
                            <div class="input-group">
                                <span class="input-group-text bg-label-primary border-primary text-primary"><i class="bx bx-user"></i></span>
                                <select class="form-select form-select-lg border-primary" id="user_id" name="user_id" required>
                                    <option value="" selected disabled>-- Pilih Kandidat Mentor --</option>
                                    @foreach($availableMentors as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        {{-- STEP 2: PILIH KELOMPOK --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <label class="form-label fw-bold text-uppercase text-muted small mb-0">Langkah 2: Assign Kelompok (Opsional)</label>
                                <span class="badge bg-label-primary" id="selected-badge">0 Dipilih</span>
                            </div>

                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <i class="bx bx-bulb me-2 fs-4"></i>
                                <div>
                                    Hanya kelompok yang <strong>belum memiliki mentor</strong> yang ditampilkan di sini.
                                </div>
                            </div>

                            @if($groups->isEmpty())
                                <div class="text-center py-4 border border-dashed rounded bg-light">
                                    <small class="text-muted">Belum ada kelompok sama sekali di event ini.</small>
                                </div>
                            @else
                                <div class="row g-3" style="max-height: 400px; overflow-y: auto; padding: 2px;">
                                    @php $hasAvailableGroup = false; @endphp

                                    @foreach($groups as $group)
                                        {{-- LOGIC FILTER: Hide group if already has mentor --}}
                                        @if($group->mentor_id)
                                            @continue
                                        @endif

                                        @php
                                            $hasAvailableGroup = true;
                                            // Random Colors for Icons
                                            $colors = ['primary', 'success', 'warning', 'info', 'danger', 'dark'];
                                            $bgColors = ['#e7e7ff', '#e8fadf', '#fff2d6', '#d7f5fc', '#ffe0db', '#444'];
                                            $textColors = ['#696cff', '#71dd37', '#ffab00', '#03c3ec', '#ff3e1d', '#fff'];
                                            $idx = $group->id % count($colors);
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="p-3 group-select-card h-100" onclick="toggleCheckbox('group_{{ $group->id }}')">

                                                {{-- CHECK INDICATOR --}}
                                                <div class="check-indicator">
                                                    <i class='bx bx-check'></i>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    {{-- Hidden Checkbox --}}
                                                    <div class="d-none">
                                                        <input class="form-check-input group-checkbox" type="checkbox" name="group_ids[]" value="{{ $group->id }}" id="group_{{ $group->id }}" />
                                                    </div>

                                                    {{-- Icon --}}
                                                    <div class="group-icon me-3" style="background-color: {{ $bgColors[$idx] }}; color: {{ $textColors[$idx] }};">
                                                        <i class='bx bx-group'></i>
                                                    </div>

                                                    {{-- Text --}}
                                                    <div>
                                                        <span class="fw-bold d-block text-dark">{{ $group->name }}</span>
                                                        <small class="text-muted">{{ $group->members->count() ?? 0 }} Anggota</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if(!$hasAvailableGroup)
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="bx bx-check-double fs-1 text-success mb-2"></i>
                                                <p class="text-muted mb-0">Semua kelompok di event ini sudah memiliki mentor.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.events.mentors.index', $event->id) }}" class="btn btn-label-secondary">Batal</a>
                            <button type="submit" class="btn btn-squid px-4 shadow-sm">Simpan Data</button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
    function toggleCheckbox(id) {
        const checkbox = document.getElementById(id);
        if(!checkbox) return;

        const card = checkbox.closest('.group-select-card');

        checkbox.checked = !checkbox.checked;

        if(checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        updateCount();
    }

    function updateCount() {
        const count = document.querySelectorAll('.group-checkbox:checked').length;
        const badge = document.getElementById('selected-badge');
        if(badge) badge.innerText = count + ' Dipilih';
    }
</script>
@endsection
