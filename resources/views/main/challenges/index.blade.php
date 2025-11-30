@extends('main.layouts.mobileMaster')

@section('title', 'Squid Challenges')

@section('styles')
<style>
    /* --- HEADER SECTION --- */
    .challenge-header {
        background: linear-gradient(135deg, #ff4b4b 0%, #ff7b7b 100%); /* Merah khas Squid Game */
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        padding: 40px 25px 90px 25px; /* Padding bawah besar untuk floating card */
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* --- FLOATING TIMER CARD --- */
    .timer-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin: -70px 20px 25px 20px; /* Overlap ke header */
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 10;
        text-align: center;
        border: 1px solid #ffe5e5;
    }

    .timer-digits {
        font-family: 'Public Sans', sans-serif;
        font-weight: 800;
        color: #232b2b;
        font-size: 2.5rem;
        line-height: 1;
        letter-spacing: 2px;
        font-variant-numeric: tabular-nums; /* Agar angka tidak goyang saat detik berubah */
    }

    /* --- DIFFICULTY GRID (REVISI TAMPILAN) --- */
    .difficulty-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 0 20px;
        margin-bottom: 30px;
    }

    .difficulty-btn {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 15px 5px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.2s;
        cursor: pointer;
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .difficulty-btn:active {
        transform: scale(0.95);
    }

    /* Style Khusus per Tier agar lebih "Berisi" */
    .btn-tier-1 {
        background: linear-gradient(145deg, #f0fdf4 0%, #ffffff 100%);
        color: #71dd37;
        border: 1px solid #dcfce7;
    }
    .btn-tier-2 {
        background: linear-gradient(145deg, #fffbeb 0%, #ffffff 100%);
        color: #ffab00;
        border: 1px solid #fef3c7;
    }
    .btn-tier-3 {
        background: linear-gradient(145deg, #fff2f2 0%, #ffffff 100%);
        color: #ff3e1d;
        border: 1px solid #ffe4e6;
    }

    /* Disabled State */
    .difficulty-btn.disabled {
        opacity: 0.6;
        filter: grayscale(1);
        cursor: not-allowed;
        background: #f5f5f9;
        border: 1px solid #d9dee3;
        color: #a1acb8;
    }

    .tier-icon {
        font-size: 2.2rem;
        margin-bottom: 8px;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); /* Bayangan pada icon */
    }

    .tier-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #566a7f;
        margin-bottom: 4px;
    }

    .tier-price {
        font-size: 0.85rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 50rem;
    }

    /* Warna Badge Harga */
    .btn-tier-1 .tier-price { background: #dcfce7; color: #166534; }
    .btn-tier-2 .tier-price { background: #fef3c7; color: #92400e; }
    .btn-tier-3 .tier-price { background: #ffe4e6; color: #9f1239; }

    /* --- ACTIVE CHALLENGE CARD --- */
    .active-challenge-card {
        margin: 0 20px 15px 20px;
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-left: 5px solid #696cff; /* Default border color */
        position: relative;
        overflow: hidden;
    }

    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .price-tag {
        font-size: 0.8rem;
        font-weight: 800;
        color: #8592a3;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')

    {{-- 1. HEADER --}}
    <div class="challenge-header">
        <h3 class="text-white fw-bold mb-1">SQUID CHALLENGES</h3>
        <p class="text-white opacity-90 small mb-0">Ambil risiko, menangkan hadiahnya!</p>

        {{-- Hiasan Background Abstrak --}}
        <div style="position: absolute; top: -10px; left: -10px; opacity: 0.1; transform: rotate(-15deg);">
            <i class='bx bx-joystick text-white' style="font-size: 8rem;"></i>
        </div>
        <div style="position: absolute; bottom: 10px; right: -20px; opacity: 0.1; transform: rotate(15deg);">
            <i class='bx bx-target-lock text-white' style="font-size: 6rem;"></i>
        </div>
    </div>

    {{-- 2. FLOATING TIMER --}}
    <div class="timer-card">
        @if($isOpened)
            <span class="d-block text-danger small fw-bold mb-1" style="letter-spacing: 1px;">SISA WAKTU FASE INI</span>
            <div id="countdown" class="timer-digits">00:00:00</div>
        @else
            <div class="py-2 text-muted">
                <i class='bx bxs-lock-alt fs-1 mb-2 text-secondary'></i>
                <h5 class="fw-bold text-secondary mb-0">Fase Ditutup</h5>
            </div>
        @endif
    </div>

    @if($isOpened)

        {{-- 3. PILIHAN CHALLENGE (BUTTONS GRID) --}}
        <div class="d-flex justify-content-between align-items-center px-4 mb-3">
            <h6 class="fw-bold text-dark m-0">Pilih Misi Baru</h6>
            <span class="badge bg-label-primary rounded-pill">{{ $canTakeMore ? 'Slot Tersedia' : 'Slot Penuh' }}</span>
        </div>

        <div class="difficulty-grid">
            {{-- TIER 1: 300K --}}
            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="300000">
                <button type="submit" class="difficulty-btn btn-tier-1 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    {{-- Icon Ganti: Target --}}
                    <i class='bx bx-target-lock tier-icon'></i>
                    <span class="tier-price">300K</span>
                </button>
            </form>

            {{-- TIER 2: 500K --}}
            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="500000">
                <button type="submit" class="difficulty-btn btn-tier-2 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    <i class='bx bx-diamond tier-icon'></i>
                    <span class="tier-price">500K</span>
                </button>
            </form>

            {{-- TIER 3: 700K --}}
            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="700000">
                <button type="submit" class="difficulty-btn btn-tier-3 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    {{-- Icon Ganti: Trophy/Crown --}}
                    <i class='bx bx-trophy tier-icon'></i>
                    <span class="tier-price">700K</span>
                </button>
            </form>
        </div>

        {{-- 4. ACTIVE CHALLENGES LIST --}}
        <div class="section-heading px-4 mb-3 d-flex justify-content-between align-items-end">
            <h6 class="fw-bold text-dark m-0">Misi Aktif Saya</h6>
            <small class="text-muted">{{ $myActiveChallenges->count() }} Misi</small>
        </div>

        @forelse($myActiveChallenges as $submission)
            {{-- Logic warna border berdasarkan status --}}
            @php
                $borderColor = '#ffab00'; // Default Warning (Active)
                $statusText = 'BELUM SUBMIT';
                $statusClass = 'bg-label-warning';

                if($submission->status == 'pending') {
                    $borderColor = '#696cff'; // Info Blue
                    $statusText = 'MENUNGGU REVIEW';
                    $statusClass = 'bg-label-info';
                } elseif($submission->status == 'rejected') {
                    $borderColor = '#ff3e1d'; // Red
                    $statusText = 'REVISI DIPERLUKAN';
                    $statusClass = 'bg-label-danger';
                } elseif($submission->status == 'approved') {
                    $borderColor = '#71dd37'; // Green
                    $statusText = 'SELESAI';
                    $statusClass = 'bg-label-success';
                }
            @endphp

            <div class="active-challenge-card" style="border-left-color: {{ $borderColor }};">
                {{-- Status Badge --}}
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>

                <div class="mb-2">
                    <span class="price-tag me-2"><i class='bx bx-coin-stack'></i> ${{ number_format($submission->challenge->price/1000, 0) }}K</span>
                </div>

                <h5 class="fw-bold text-dark mb-2 w-75">{{ $submission->challenge->nama }}</h5>
                <p class="text-muted small mb-3">
                    {{ Str::limit($submission->challenge->deskripsi, 80) }}
                </p>

                <div class="d-flex gap-2">
                    {{-- Tombol PDF --}}
                    @if($submission->challenge->file_pdf)
                        <a href="{{ asset('storage/'.$submission->challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-label-secondary rounded-pill">
                            <i class='bx bxs-file-pdf me-1'></i> Soal
                        </a>
                    @endif

                    {{-- Tombol Action --}}
                    @if($submission->status == 'active')
                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#submitModal{{ $submission->id }}">
                            <i class='bx bx-upload me-1'></i> Submit Jawaban
                        </button>
                    @elseif($submission->status == 'rejected')
                        <button class="btn btn-sm btn-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#submitModal{{ $submission->id }}">
                            <i class='bx bx-redo me-1'></i> Submit Ulang
                        </button>
                    @endif
                </div>

                @if($submission->mentor_feedback)
                    <div class="mt-3 p-2 bg-lighter rounded text-danger small border border-danger border-opacity-25">
                        <i class='bx bxs-message-square-error me-1'></i>
                        <strong>Mentor:</strong> {{ $submission->mentor_feedback }}
                    </div>
                @endif
            </div>

            <!-- Modal Submit untuk setiap item -->
            <div class="modal fade" id="submitModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dark">Kirim Jawaban</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('main.challenges.store', $submission->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body pt-3">
                                <div class="alert alert-primary bg-label-primary mb-3 small">
                                    <i class='bx bx-info-circle me-1'></i> Pastikan link akses dibuka untuk publik (View Only).
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Link Hasil Karya (Gdrive/Canva)</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text border-0 bg-light"><i class="bx bx-link"></i></span>
                                        <input type="text" name="submission_text" class="form-control border-0 bg-light" placeholder="https://..." value="{{ $submission->submission_text }}">
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small fw-bold text-muted">Atau Upload File (PDF/JPG)</label>
                                    <input type="file" name="file" class="form-control border-0 bg-light">
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-label-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="text-center py-5 mx-4">
                <div class="bg-label-secondary rounded-circle d-inline-flex p-3 mb-3">
                    <i class='bx bx-ghost fs-1 text-secondary'></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum ada misi</h6>
                <p class="text-muted small">Ayo ambil tantangan di atas dan kumpulkan poin!</p>
            </div>
        @endforelse

    @endif

    <div style="height: 80px;"></div> {{-- Spacer Bottom Nav --}}
</div>
@endsection

@push('scripts') {{-- PERBAIKAN PENTING: Ganti section jadi push --}}
<script>
    // PERBAIKAN TIMER: Gunakan format ISO 8601 agar terbaca di semua browser (termasuk Mobile)
    @php
        // Mengkonversi Carbon ke format ISO 8601 String agar aman di JS
        $isoEndTime = $event && $event->challenge_end_time ? $event->challenge_end_time->toIso8601String() : null;
    @endphp

    const endTimeStr = "{{ $isoEndTime }}";

    if(endTimeStr) {
        // Parse tanggal dari string ISO
        const endTime = new Date(endTimeStr).getTime();

        // Update timer setiap 1 detik
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            // Jika waktu habis
            if (distance < 0) {
                clearInterval(timer);
                const el = document.getElementById("countdown");
                if(el) {
                    el.innerHTML = "SELESAI";
                    el.classList.add('text-danger');
                }
                // Optional: location.reload();
                return;
            }

            // Kalkulasi waktu
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Tambahkan 0 di depan angka satuan
            const h = hours < 10 ? "0" + hours : hours;
            const m = minutes < 10 ? "0" + minutes : minutes;
            const s = seconds < 10 ? "0" + seconds : seconds;

            // Update elemen HTML
            const el = document.getElementById("countdown");
            if(el) el.innerHTML = h + ":" + m + ":" + s;
        }, 1000);
    }
</script>
@endpush
