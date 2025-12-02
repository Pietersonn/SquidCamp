@extends('main.layouts.mobileMaster')

@section('title', 'Squid Challenges')

@section('styles')
<style>
    /* --- 1. HEADER SECTION (SQUID TEAL THEME) --- */
    .challenge-header {
        background: linear-gradient(135deg, #00a79d 0%, #00897b 100%);
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        padding: 40px 25px 120px 25px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 167, 157, 0.4);
    }

    /* --- 2. FLOATING TIMER CARD --- */
    .timer-card {
        background: white;
        border-radius: 20px;
        padding: 15px 25px;
        margin: -85px 20px 30px 20px;
        box-shadow: 0 15px 40px rgba(0, 167, 157, 0.15);
        position: relative;
        z-index: 10;
        text-align: center;
        border: 2px solid #e0f2f1;
    }

    .timer-label {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: #00695c;
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    }

    .timer-digits {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 900;
        color: #004d40;
        font-size: 2.5rem;
        line-height: 1;
        letter-spacing: -1px;
    }

    /* --- DIFFICULTY GRID --- */
    .difficulty-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0 20px;
        margin-bottom: 30px;
    }

    .difficulty-btn {
        background: white;
        border: none;
        border-radius: 18px;
        padding: 15px 5px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
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

    .difficulty-btn:active { transform: scale(0.95); }

    .btn-tier-1 { background: linear-gradient(145deg, #f0fdf4 0%, #fff 100%); border: 1px solid #bbf7d0; color: #166534; }
    .btn-tier-2 { background: linear-gradient(145deg, #fffbeb 0%, #fff 100%); border: 1px solid #fde68a; color: #b45309; }
    .btn-tier-3 { background: linear-gradient(145deg, #fef2f2 0%, #fff 100%); border: 1px solid #fecaca; color: #991b1b; }

    .difficulty-btn.disabled {
        opacity: 0.6;
        filter: grayscale(1);
        cursor: not-allowed;
        background: #f5f5f9;
        border: 1px solid #d9dee3;
    }

    .tier-icon {
        font-size: 2rem;
        margin-bottom: 5px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .tier-price {
        font-size: 0.8rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* --- ACTIVE CHALLENGE CARD --- */
    .active-challenge-card {
        margin: 0 20px 15px 20px;
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #e0f2f1;
        border-left: 5px solid #00a79d;
        position: relative;
        overflow: hidden;
    }

    .status-badge {
        position: absolute; top: 15px; right: 15px;
        font-size: 0.65rem; font-weight: 800;
        padding: 5px 12px; border-radius: 30px;
        text-transform: uppercase;
    }

    .price-tag {
        font-size: 0.75rem; font-weight: 800; color: #00695c;
        text-transform: uppercase; letter-spacing: 0.5px;
        background: #e0f2f1; padding: 4px 8px; border-radius: 6px;
    }

    /* Upload Box Style */
    .upload-box {
        border: 2px dashed #00a79d;
        background: #e0f2f1;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        position: relative;
    }
    .upload-input { position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; }

</style>
@endsection

@section('content')

    {{-- 1. HEADER --}}
    <div class="challenge-header">
        <h2 class="fw-bold mb-0 text-white">SQUID CHALLENGES</h2>
        <p class="text-white opacity-75 small mb-0 fw-bold">High Risk, High Reward.</p>

        <i class='bx bx-joystick text-white position-absolute' style="font-size: 8rem; top: -10px; left: -20px; opacity: 0.1; transform: rotate(-15deg);"></i>
        <i class='bx bx-target-lock text-white position-absolute' style="font-size: 6rem; bottom: 20px; right: -20px; opacity: 0.1; transform: rotate(15deg);"></i>
    </div>

    {{-- 2. FLOATING TIMER --}}
    <div class="timer-card">
        @if($isOpened)
            <span class="timer-label">SISA WAKTU</span>
            <div id="countdown" class="timer-digits">00:00:00</div>
        @else
            <div class="py-1">
                <i class='bx bxs-lock-alt text-muted fs-1'></i>
                <h5 class="fw-bold text-muted mb-0">FASE DITUTUP</h5>
            </div>
        @endif
    </div>

    @if($isOpened)

        {{-- 3. PILIHAN CHALLENGE --}}
        <div class="d-flex justify-content-between align-items-center px-4 mb-3">
            <h6 class="fw-bold text-dark m-0 ps-2 border-start border-4 border-primary">Ambil Misi Baru</h6>
            <span class="badge bg-label-primary rounded-pill">{{ $canTakeMore ? 'Slot Tersedia' : 'Slot Penuh' }}</span>
        </div>

        <div class="difficulty-grid">
            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="300000">
                <button type="submit" class="difficulty-btn btn-tier-1 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    <i class='bx bx-target-lock tier-icon'></i>
                    <span class="tier-price">300K</span>
                </button>
            </form>

            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="500000">
                <button type="submit" class="difficulty-btn btn-tier-2 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    <i class='bx bx-diamond tier-icon'></i>
                    <span class="tier-price">500K</span>
                </button>
            </form>

            <form action="{{ route('main.challenges.take') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="price" value="700000">
                <button type="submit" class="difficulty-btn btn-tier-3 {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}" {{ (!$canTakeMore || !$isCaptain) ? 'disabled' : '' }}>
                    <i class='bx bx-trophy tier-icon'></i>
                    <span class="tier-price">700K</span>
                </button>
            </form>
        </div>

        {{-- 4. ACTIVE CHALLENGES LIST --}}
        <div class="px-4 mb-3 d-flex justify-content-between align-items-end">
            <h6 class="fw-bold text-dark m-0 ps-2 border-start border-4 border-info">Progress Saya</h6>
            <small class="text-muted fw-bold">{{ $myActiveChallenges->count() }} Misi</small>
        </div>

        @forelse($myActiveChallenges as $submission)
            @php
                $borderColor = '#00a79d';
                $statusText = 'ON PROGRESS';
                $statusClass = 'bg-label-info';

                if($submission->status == 'pending') {
                    $borderColor = '#ffab00';
                    $statusText = 'REVIEWING';
                    $statusClass = 'bg-label-warning';
                } elseif($submission->status == 'rejected') {
                    $borderColor = '#ff3e1d';
                    $statusText = 'REVISI';
                    $statusClass = 'bg-label-danger';
                } elseif($submission->status == 'approved') {
                    $borderColor = '#71dd37';
                    $statusText = 'SELESAI';
                    $statusClass = 'bg-label-success';
                }
            @endphp

            <div class="active-challenge-card" style="border-left-color: {{ $borderColor }};">
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>

                <div class="mb-2">
                    <span class="price-tag me-2"><i class='bx bx-coin-stack'></i> ${{ number_format($submission->challenge->price/1000, 0) }}K</span>
                </div>

                <h5 class="fw-bold text-dark mb-2 w-75">{{ $submission->challenge->nama }}</h5>
                <p class="text-muted small mb-3">
                    {{ Str::limit($submission->challenge->deskripsi, 80) }}
                </p>

                <div class="d-flex gap-2">
                    @if($submission->challenge->file_pdf)
                        <a href="{{ asset('storage/'.$submission->challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-label-secondary rounded-pill fw-bold">
                            <i class='bx bxs-file-pdf me-1'></i> SOAL
                        </a>
                    @endif

                    @if($submission->status == 'active')
                        <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#submitModal{{ $submission->id }}">
                            <i class='bx bx-upload me-1'></i> SUBMIT JAWABAN
                        </button>
                    @elseif($submission->status == 'rejected')
                        <button class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#submitModal{{ $submission->id }}">
                            <i class='bx bx-redo me-1'></i> SUBMIT ULANG
                        </button>
                    @endif
                </div>

                @if($submission->mentor_feedback)
                    <div class="mt-3 p-3 bg-lighter rounded small border border-danger border-opacity-25" style="background: #fff2f2;">
                        <div class="d-flex gap-2">
                            <i class='bx bxs-message-square-error text-danger fs-5'></i>
                            <div>
                                <strong class="text-danger d-block mb-1">Catatan Mentor:</strong>
                                <span class="text-dark opacity-75">{{ $submission->mentor_feedback }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="modal fade" id="submitModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold text-dark">Kirim Jawaban</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('main.challenges.store', $submission->id) }}" method="POST" enctype="multipart/form-data"
                              onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('.btn-text').classList.add('d-none'); this.querySelector('.btn-loader').classList.remove('d-none');">
                            @csrf
                            <div class="modal-body px-4 pt-3 pb-4">
                                <p class="small text-muted mb-3">Upload jawaban Anda berupa file (Gambar/PDF) atau link GDrive.</p>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Upload File</label>
                                    <div class="upload-box">
                                        <i class='bx bxs-cloud-upload fs-1 text-primary mb-2'></i>
                                        <h6 class="fw-bold text-dark mb-0">Tap untuk Upload</h6>
                                        {{-- UPDATE LABEL --}}
                                        <small class="text-muted">PDF, DOC, ZIP, JPG, PNG</small>
                                        {{-- UPDATE ACCEPT ATTRIBUTE --}}
                                        <input type="file" name="file" class="upload-input" accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png">
                                    </div>
                                </div>

                                <div class="text-center small text-muted fw-bold mb-3">- ATAU -</div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Link Jawaban (Canva/GDrive)</label>
                                    <div class="input-group input-group-merge shadow-sm" style="border-radius: 10px; overflow:hidden;">
                                        <span class="input-group-text border-0 bg-light"><i class="bx bx-link"></i></span>
                                        <input type="text" name="submission_text" class="form-control border-0 bg-light" placeholder="https://..." value="{{ $submission->submission_text }}" style="padding:12px;">
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">*Pastikan link Public/View Only</small>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow">
                                        <span class="btn-text">KIRIM SEKARANG</span>
                                        <span class="btn-loader d-none"><i class='bx bx-loader-alt bx-spin'></i> SENDING...</span>
                                    </button>
                                </div>
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
                <p class="text-muted small">Ambil tantangan di atas untuk memulai!</p>
            </div>
        @endforelse

    @endif

    <div style="height: 80px;"></div>
</div>
@endsection

@push('scripts')
<script>
    @php
        $isoEndTime = $event && $event->challenge_end_time ? $event->challenge_end_time->toIso8601String() : null;
    @endphp

    const endTimeStr = "{{ $isoEndTime }}";

    if(endTimeStr) {
        const endTime = new Date(endTimeStr).getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timer);
                const el = document.getElementById("countdown");
                if(el) {
                    el.innerHTML = "WAKTU HABIS";
                    el.style.color = "#ff3e1d";
                    el.style.fontSize = "2rem";
                }
                return;
            }

            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            const hh = h < 10 ? "0" + h : h;
            const mm = m < 10 ? "0" + m : m;
            const ss = s < 10 ? "0" + s : s;

            const el = document.getElementById("countdown");
            if(el) el.innerHTML = hh + ":" + mm + ":" + ss;
        }, 1000);
    }
</script>
@endpush
