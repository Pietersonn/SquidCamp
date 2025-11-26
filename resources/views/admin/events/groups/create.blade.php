@extends('admin.layouts.contentNavbarLayout')

@section('title', "Create Group - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
    }

    /* --- USER SELECTION CARD --- */
    .user-select-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
        position: relative;
    }
    .user-select-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: var(--squid-primary);
    }
    .user-select-card.selected {
        background-color: #f0fdfa;
        border-color: var(--squid-primary);
        box-shadow: inset 0 0 0 1px var(--squid-primary);
    }

    /* Indicator Centang */
    .check-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--squid-primary);
        font-size: 1.2rem;
        opacity: 0;
        transform: scale(0.5);
        transition: 0.2s;
    }
    .user-select-card.selected .check-icon {
        opacity: 1;
        transform: scale(1);
    }

    /* Scroll Area */
    .member-scroll {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .member-scroll::-webkit-scrollbar { width: 5px; }
    .member-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
        transition: 0.3s;
    }
    .btn-squid:hover {
        background-color: #008f85;
        border-color: #008f85;
        color: white;
    }
</style>
@endsection

@section('content')

<form action="{{ route('admin.events.groups.store', $event->id) }}" method="POST">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: var(--squid-primary);">
            <span class="text-muted fw-light">Group /</span> Buat Baru
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.groups.index', $event->id) }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-squid px-4">
                <i class="bx bx-save me-1"></i> Simpan Kelompok
            </button>
        </div>
    </div>

    <div class="row g-4">

        {{-- KOLOM KIRI: Detail Kelompok --}}
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header border-bottom bg-light py-3">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bx bx-info-circle me-1"></i> Informasi Kelompok</h5>
                </div>
                <div class="card-body pt-4">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kelompok</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-group"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Contoh: Team Alpha" required />
                        </div>
                    </div>

                    {{-- Squid Dollar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Squid Dollar Awal ($)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text text-success">$</span>
                            <input type="number" class="form-control text-success fw-bold" name="squid_dollar" value="0" min="0" required />
                        </div>
                    </div>

                    {{-- Mentor --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mentor Pembimbing</label>
                        <select class="form-select" name="mentor_id">
                            <option value="">-- Pilih Mentor --</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">

                    {{-- Captains --}}
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-warning"><i class="bx bx-crown me-1"></i> Captain</label>
                            <select class="form-select" name="captain_id">
                                <option value="">-- Belum Ada --</option>
                                @foreach($candidates as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text small">Pilih dari daftar kandidat anggota.</div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-secondary"><i class="bx bx-star me-1"></i> Co-Captain</label>
                            <select class="form-select" name="cocaptain_id">
                                <option value="">-- Belum Ada --</option>
                                @foreach($candidates as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Manajemen Anggota --}}
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header border-bottom bg-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bx bx-user-plus me-1"></i> Pilih Anggota</h5>
                    <span class="badge bg-label-primary" id="member-count-badge">0 Dipilih</span>
                </div>

                <div class="card-body pt-3">
                    {{-- Search Box (FIXED) --}}
                    <div class="mb-3 position-relative">
                        <i class="bx bx-search fs-4 lh-0 position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index: 10;"></i>
                        <input type="text"
                               class="form-control rounded-pill bg-lighter border-0"
                               id="searchMember"
                               placeholder="Cari nama atau email..."
                               style="padding-left: 3.5rem;">
                    </div>

                    <div class="member-scroll pe-2">
                        @if($candidates->isEmpty())
                            <div class="text-center py-5">
                                <i class="bx bx-user-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2">
                                    Tidak ada peserta tersedia.<br>
                                    <small>Semua user sudah memiliki kelompok di event ini.</small>
                                </p>
                            </div>
                        @else
                            <div class="row g-2" id="memberList">
                                @foreach($candidates as $user)
                                    <div class="col-md-6 user-item">
                                        <div class="user-select-card h-100 d-flex align-items-center"
                                             onclick="toggleMember('user_{{ $user->id }}')">

                                            {{-- Checkbox Hidden --}}
                                            <input type="checkbox" class="d-none member-checkbox"
                                                name="member_ids[]"
                                                value="{{ $user->id }}"
                                                id="user_{{ $user->id }}">

                                            {{-- Avatar --}}
                                            <div class="avatar avatar-sm me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset($user->avatar) }}" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($user->name, 0, 1) }}</span>
                                                @endif
                                            </div>

                                            {{-- Info --}}
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="mb-0 text-truncate user-name">{{ $user->name }}</h6>
                                                <small class="text-muted text-truncate d-block user-email" style="font-size: 0.75rem;">{{ $user->email }}</small>
                                            </div>

                                            {{-- Centang --}}
                                            <div class="check-icon">
                                                <i class='bx bxs-check-circle'></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pesan jika pencarian tidak ditemukan --}}
                            <div id="no-results" class="text-center py-4 d-none">
                                <i class="bx bx-search-alt fs-1 text-muted mb-2"></i>
                                <p class="text-muted">Peserta tidak ditemukan.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-3 p-2 bg-label-info rounded d-flex align-items-start">
                        <i class="bx bx-info-circle me-2 mt-1"></i>
                        <small>Daftar di atas hanya menampilkan user yang <strong>belum memiliki kelompok</strong> pada event ini.</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Fungsi Toggle Checkbox & Style Card (Global)
        window.toggleMember = function(id) {
            const checkbox = document.getElementById(id);
            const card = checkbox.closest('.user-select-card');

            checkbox.checked = !checkbox.checked;

            if(checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
            updateCount();
        }

        // Update Badge Jumlah
        function updateCount() {
            const count = document.querySelectorAll('.member-checkbox:checked').length;
            const badge = document.getElementById('member-count-badge');
            if(badge) badge.innerText = count + ' Dipilih';
        }

        // Fitur Pencarian Cepat (Robust)
        const searchInput = document.getElementById('searchMember');
        const memberList = document.getElementById('memberList');
        const noResultsMsg = document.getElementById('no-results');

        if(searchInput && memberList) {
            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase().trim();
                const items = memberList.querySelectorAll('.user-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const nameText = item.querySelector('.user-name').textContent.toLowerCase();
                    const emailText = item.querySelector('.user-email').textContent.toLowerCase();

                    if(nameText.includes(value) || emailText.includes(value)) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if(noResultsMsg) {
                    if(visibleCount === 0) {
                        noResultsMsg.classList.remove('d-none');
                    } else {
                        noResultsMsg.classList.add('d-none');
                    }
                }
            });
        }
    });
</script>

@endsection
