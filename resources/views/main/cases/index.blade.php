@extends('main.layouts.mobileMaster')

@section('title', 'Squid Cases')

@section('styles')
<style>
    /* --- HEADER SECTION --- */
    .case-header {
        background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%); /* Squid Teal Theme */
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        padding: 40px 25px 90px 25px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Timer Box */
    .timer-box {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 10px 25px;
        display: inline-block;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .timer-digits {
        font-family: 'Public Sans', sans-serif;
        font-weight: 800;
        font-size: 2.2rem;
        letter-spacing: 2px;
        line-height: 1;
        font-variant-numeric: tabular-nums;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* --- GACHA / MARKET DATA CARD --- */
    .gacha-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin: -70px 20px 25px 20px;
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.15);
        position: relative;
        z-index: 10;
        text-align: center;
        border: 1px solid #e0f2f1;
        overflow: hidden;
    }

    /* Tombol Gacha Menarik */
    .btn-gacha {
        background: linear-gradient(45deg, #FFC107 0%, #FF9800 100%);
        color: #fff;
        font-weight: 800;
        border: none;
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        transition: transform 0.2s;
        border-radius: 50px;
        padding: 12px;
        font-size: 1rem;
        position: relative;
        overflow: hidden;
    }
    .btn-gacha:hover {
        background: linear-gradient(45deg, #FF9800 0%, #F57C00 100%);
        transform: translateY(-2px);
    }
    .btn-gacha:active { transform: scale(0.98); }

    /* --- CASE ITEM (STYLE BARU MIRIP CHALLENGE) --- */
    .case-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-left: 5px solid #00a79d; /* Default Active Color */
        transition: all 0.3s ease;
    }

    .case-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .case-card.completed {
        border-left-color: #71dd37; /* Green for Success */
        background-color: #f9fdfd;
    }

    /* Badge Status di Pojok Kanan Atas */
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-label-active { background-color: #e0f2f1; color: #00a79d; }
    .bg-label-success { background-color: #dcfce7; color: #166534; }
    .bg-label-closed { background-color: #f5f5f9; color: #8592a3; }

    /* Guideline Item */
    .guideline-box {
        background: #2b3546; /* Dark Blue Grey */
        color: #fff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #00a79d;
        position: relative;
        overflow: hidden;
    }
    .guideline-box::after {
        content: '';
        position: absolute;
        top: 0; right: 0; width: 50px; height: 50px;
        background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,0.1) 50%);
    }
    .guideline-title {
        color: #00d4c7;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endsection

@section('content')

    {{-- 1. HEADER --}}
    <div class="case-header">
        <h3 class="text-white fw-bold mb-1">BUSINESS CASES</h3>
        <p class="text-white opacity-90 small mb-0">Analisis kasus, berikan solusi terbaik!</p>

        {{-- Timer --}}
        @if($isOpened)
            <div class="timer-box">
                <small class="text-white opacity-90 fw-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">WAKTU TERSISA</small>
                <div id="countdown" class="timer-digits text-white">00:00:00</div>
            </div>
        @else
            <div class="mt-4">
                <span class="badge bg-white text-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class='bx bxs-lock-alt me-1'></i> SESI DITUTUP
                </span>
            </div>
        @endif

        {{-- Background Elements --}}
        <i class='bx bx-briefcase-alt text-white position-absolute' style="font-size: 7rem; top: 10px; left: -20px; opacity: 0.1; transform: rotate(-15deg);"></i>
        <i class='bx bx-bulb text-white position-absolute' style="font-size: 6rem; bottom: 10px; right: -10px; opacity: 0.1; transform: rotate(15deg);"></i>
    </div>

    {{-- 2. MARKET DATA (GACHA) --}}
    <div class="gacha-card">
        <div class="row align-items-center mb-3">
            <div class="col-8 text-start">
                <h6 class="fw-bold text-dark mb-1">
                    <span class="text-primary">GUIDELINES</span> <i class='bx bxs-hot text-danger'></i>
                </h6>
                <p class="text-muted small mb-0 lh-sm">Beli data rahasia untuk membantu analisis.</p>
            </div>
            <div class="col-4 text-end">
                <h2 class="mb-0 fw-bold text-dark">{{ $ownedCount }}<span class="text-muted fs-6">/{{ $totalGuidelines }}</span></h2>
            </div>
        </div>

        @if($isOpened)
            <form action="{{ route('main.cases.buyGuideline') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-gacha w-100 shadow-sm" {{ $ownedCount >= $totalGuidelines ? 'disabled' : '' }}>
                    @if($ownedCount >= $totalGuidelines)
                        <i class='bx bx-check-circle me-1'></i> DATA LENGKAP
                    @else
                        <div class="d-flex justify-content-between align-items-center px-2">
                            <span><i class='bx bxs-cart-add me-1'></i> BELI DATA</span>
                            <span class="bg-white text-warning px-2 py-1 rounded-pill small fw-bold" style="font-size: 0.75rem;">$150K</span>
                        </div>
                    @endif
                </button>
            </form>
        @else
            <button class="btn btn-secondary w-100 rounded-pill py-2" disabled>Market Tutup</button>
        @endif
    </div>

    {{-- 3. DATA TERSIMPAN (GUIDELINE LIST) --}}
    @if(count($myGuidelines) > 0)
        <div class="px-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-info">Petunjuk Tersimpan</h6>
            @foreach($myGuidelines as $gl)
                <div class="guideline-box shadow-sm">
                    <div class="guideline-title"><i class='bx bxs-data'></i> {{ $gl->title }}</div>
                    <p class="mb-0 opacity-75 small">{{ $gl->description }}</p>
                    @if($gl->file_pdf)
                        <a href="{{ asset('storage/'.$gl->file_pdf) }}" target="_blank" class="btn btn-xs btn-outline-light mt-3 rounded-pill px-3 fw-bold">
                            <i class='bx bxs-file-pdf me-1'></i> BUKA DOKUMEN
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- 4. STUDI KASUS (CASE LIST - REVISI TAMPILAN) --}}
    <div class="px-4 pb-5">
        <h6 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-primary">Studi Kasus</h6>

        @forelse($cases as $case)
            {{-- Tentukan Status & Style --}}
            @php
                $statusText = 'TERSEDIA';
                $statusClass = 'bg-label-active';
                $cardClass = '';

                if($case->my_submission) {
                    $statusText = 'SELESAI';
                    $statusClass = 'bg-label-success';
                    $cardClass = 'completed';
                } elseif(!$isOpened) {
                    $statusText = 'DITUTUP';
                    $statusClass = 'bg-label-closed';
                }
            @endphp

            <div class="case-card {{ $cardClass }}">
                {{-- Badge Status --}}
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>

                <h5 class="fw-bold text-dark mb-2 w-75">{{ $case->nama }}</h5>
                <p class="text-muted small mb-3 lh-sm">
                    {{ Str::limit($case->deskripsi, 100) }}
                </p>

                {{-- Action Buttons --}}
                @if($case->my_submission)
                    {{-- Tampilan Jika Sudah Submit --}}
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 mt-2 border border-success border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="d-block fw-bold text-success opacity-75" style="font-size: 0.7rem;">RANKING ANDA</small>
                            <span class="fs-5 fw-bold text-success">#{{ $case->my_submission->rank }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="d-block fw-bold text-success opacity-75" style="font-size: 0.7rem;">REWARD DITERIMA</small>
                            <span class="fs-5 fw-bold text-success">+ ${{ number_format($case->my_submission->reward_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @else
                    {{-- Tampilan Belum Submit --}}
                    <div class="d-flex gap-2 mt-3">
                        @if($case->file_pdf)
                            <a href="{{ asset('storage/'.$case->file_pdf) }}" target="_blank" class="btn btn-sm btn-label-secondary rounded-pill px-3 flex-grow-1 fw-bold">
                                <i class='bx bxs-file-pdf me-1'></i> BACA SOAL
                            </a>
                        @endif

                        @if($isOpened)
                            <button class="btn btn-sm btn-primary rounded-pill px-3 flex-grow-1 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#submitCaseModal{{ $case->id }}">
                                <i class='bx bx-send me-1'></i> KIRIM JAWABAN
                            </button>
                        @else
                             <button class="btn btn-sm btn-secondary rounded-pill flex-grow-1" disabled>
                                <i class='bx bxs-lock-alt me-1'></i> WAKTU HABIS
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Modal Submit (Hanya dirender jika belum submit & buka) -->
            @if(!$case->my_submission && $isOpened)
            <div class="modal fade" id="submitCaseModal{{ $case->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold text-dark">Kirim Jawaban Case</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('main.cases.submit', $case->id) }}" method="POST">
                            @csrf
                            <div class="modal-body px-4 pt-2 pb-4">
                                <p class="text-muted small mb-3">Pastikan link jawaban Anda dapat diakses publik (View Only).</p>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-primary">Link Jawaban (Gdrive/Canva)</label>
                                    <div class="input-group input-group-merge shadow-sm" style="border-radius: 10px; overflow:hidden;">
                                        <span class="input-group-text bg-light border-0"><i class='bx bx-link text-primary'></i></span>
                                        <input type="text" name="submission_text" class="form-control border-0 bg-light" placeholder="https://..." required style="padding: 12px;">
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                                        <i class='bx bx-paper-plane me-1'></i> KIRIM SEKARANG
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        @empty
            <div class="text-center py-5">
                <div class="bg-label-secondary rounded-circle d-inline-flex p-3 mb-3">
                    <i class='bx bx-briefcase-alt-2 fs-1 text-secondary'></i>
                </div>
                <h6 class="fw-bold text-dark">Belum ada kasus tersedia</h6>
                <p class="text-muted small">Nantikan tantangan berikutnya!</p>
            </div>
        @endforelse
    </div>

    <div style="height: 80px;"></div>
@endsection

@push('scripts')
<script>
    @php
        $isoEndTime = $event && $event->case_end_time ? $event->case_end_time->toIso8601String() : null;
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
                    el.innerHTML = "00:00:00";
                    el.classList.add('text-danger');
                }
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const h = hours < 10 ? "0" + hours : hours;
            const m = minutes < 10 ? "0" + minutes : minutes;
            const s = seconds < 10 ? "0" + seconds : seconds;

            const el = document.getElementById("countdown");
            if(el) el.innerHTML = h + ":" + m + ":" + s;
        }, 1000);
    }
</script>
@endpush
