@extends('main.layouts.mobileMaster')

@section('title', 'Mission Center')

@section('styles')
<style>
    :root {
        /* PALET WARNA KUNING HARMONIS */
        --bee-pale: #fffde7;   /* Background sangat muda */
        --bee-light: #fff59d;  /* Kuning muda */
        --bee-main: #fdd835;   /* Kuning utama (cerah) */
        --bee-gold: #fbc02d;   /* Emas */
        --bee-dark: #f57f17;   /* Kuning tua/Oranye */
        --bee-text: #4e342e;   /* Coklat tua (lebih enak dilihat di atas kuning drpd hitam) */
    }

    body {
        background-color: #fffbf0; /* Background halaman agak krem */
    }

    /* --- 1. HEADER SECTION (YELLOW GRADIENT) --- */
    .case-header {
        background: linear-gradient(135deg, var(--bee-main) 0%, var(--bee-gold) 100%);
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        padding: 40px 25px 120px 25px;
        color: var(--bee-text);
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(251, 192, 45, 0.3);
    }

    .header-icon {
        font-size: 8rem;
        position: absolute;
        opacity: 0.1;
        color: var(--bee-text);
    }

    /* --- 2. FLOATING TIMER --- */
    .timer-card {
        background: white;
        border-radius: 20px;
        padding: 15px 25px;
        margin: -85px 20px 30px 20px; /* Overlap header */
        box-shadow: 0 15px 40px rgba(249, 168, 37, 0.15);
        position: relative;
        z-index: 10;
        text-align: center;
        border: 2px solid var(--bee-light);
    }

    .timer-label {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: var(--bee-dark);
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    }

    .timer-digits {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 900;
        color: var(--bee-text);
        font-size: 2.5rem;
        line-height: 1;
        letter-spacing: -1px;
    }

    /* --- 3. TICKET BUY (YELLOW THEME) --- */
    .golden-ticket {
        background: radial-gradient(circle at top left, transparent 10px, var(--bee-main) 11px),
                    radial-gradient(circle at bottom left, transparent 10px, var(--bee-main) 11px);
        background-size: 50% 100%;
        background-repeat: no-repeat;
        background-image:
            radial-gradient(circle at top left, transparent 10px, var(--bee-pale) 11px),
            radial-gradient(circle at bottom left, transparent 10px, var(--bee-pale) 11px),
            radial-gradient(circle at top right, transparent 10px, var(--bee-pale) 11px),
            radial-gradient(circle at bottom right, transparent 10px, var(--bee-pale) 11px);
        background-position: 0 0, 0 0, 100% 0, 100% 0;
        background-size: 51% 100%;

        padding: 20px;
        margin: 0 20px 30px 20px;
        filter: drop-shadow(0 5px 15px rgba(251, 192, 45, 0.2));
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px dashed var(--bee-gold);
        transition: transform 0.2s;
    }
    .golden-ticket:active { transform: scale(0.98); }

    .ticket-icon {
        width: 45px; height: 45px;
        background: var(--bee-main);
        color: var(--bee-text);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 10px rgba(251, 192, 45, 0.3);
    }

    /* --- 4. MISSION & INVENTORY CARDS (UNIFIED YELLOW) --- */
    .info-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #fff3e0; /* Border sangat tipis */
        position: relative;
        overflow: hidden;
    }

    /* Aksen Garis Kiri - Semua variasi kuning/emas */
    .accent-active { border-left: 5px solid var(--bee-main); }
    .accent-done { border-left: 5px solid var(--bee-dark); background: #fffdf5; } /* Done = Lebih gelap/emas */
    .accent-locked { border-left: 5px solid #d7ccc8; } /* Abu kecoklatan (netral) */
    .accent-inventory { border-left: 5px solid var(--bee-gold); background: #fffff0; }

    /* Badge Status (Pill) */
    .status-pill {
        position: absolute; top: 15px; right: 15px;
        font-size: 0.65rem; font-weight: 800;
        padding: 5px 12px; border-radius: 30px;
        text-transform: uppercase;
    }
    .pill-yellow { background: var(--bee-pale); color: var(--bee-dark); border: 1px solid var(--bee-light); }
    .pill-gold { background: #fff8e1; color: #ff6f00; border: 1px solid var(--bee-gold); }
    .pill-grey { background: #eceff1; color: #90a4ae; }

    /* Tombol-tombol Nuansa Kuning */
    .btn-bee {
        background: linear-gradient(45deg, var(--bee-main), var(--bee-gold));
        color: var(--bee-text);
        border: none;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(253, 216, 53, 0.4);
    }
    .btn-bee-outline {
        border: 2px solid var(--bee-main);
        color: var(--bee-text);
        background: transparent;
        font-weight: 700;
    }
    .btn-bee-dark {
        background: var(--bee-text);
        color: var(--bee-main);
    }

    /* Upload Zone */
    .upload-box {
        border: 2px dashed var(--bee-gold);
        background: var(--bee-pale);
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
    <div class="case-header">
        <h2 class="fw-bold mb-0" style="color: #3e2723;">SQUID CASE</h2>
        <p class="opacity-75 small mb-0 fw-bold">Pusat Misi & Data Intelijen</p>

        {{-- Dekorasi Latar Belakang --}}
        <i class='bx bx-hive header-icon' style="top: -20px; left: -20px; transform: rotate(-15deg);"></i>
        <i class='bx bx-data header-icon' style="bottom: 10px; right: -20px; font-size: 6rem; transform: rotate(15deg);"></i>
    </div>

    {{-- 2. FLOATING TIMER --}}
    <div class="timer-card">
        @if($isOpened)
            <span class="timer-label">BATAS WAKTU</span>
            <div id="countdown" class="timer-digits">00:00:00</div>
        @else
            <div class="py-1">
                <i class='bx bxs-lock text-muted fs-1'></i>
                <h5 class="fw-bold text-muted mb-0">SESI BERAKHIR</h5>
            </div>
        @endif
    </div>

    {{-- 3. BUY GUIDELINE (TICKET STYLE) --}}
    @if($isOpened)
        <div class="px-4 mb-2 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold m-0" style="color: var(--bee-text);">Secret Shop</h6>
            <span class="badge rounded-pill" style="background: var(--bee-text); color: var(--bee-main);">Saldo: ${{ number_format($group->squid_dollar ?? 0, 0, ',', '.') }}</span>
        </div>

        <form action="{{ route('main.cases.buyGuideline') }}" method="POST">
            @csrf
            <button type="submit" class="w-100 border-0 p-0 bg-transparent text-start" {{ $ownedCount >= $totalGuidelines ? 'disabled' : '' }}>
                <div class="golden-ticket">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ticket-icon">
                            <i class='bx bxs-cart-add'></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="color: var(--bee-text);">BUY GUIDELINE</h6>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">
                                @if($ownedCount >= $totalGuidelines)
                                    <span class="fw-bold" style="color: var(--bee-dark);">SOLD!</span>
                                @else
                                    Unlock 1 dokumen rahasia
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold d-block fs-5" style="color: var(--bee-text);">$150K</span>
                    </div>
                </div>
            </button>
        </form>
    @endif

    {{-- 4. DAFTAR MISI (CASE LIST) --}}
    <div class="px-4 mb-4">
        <h6 class="fw-bold mb-3 ps-2 border-start border-4" style="color: var(--bee-text); border-color: var(--bee-main) !important;">Mission</h6>

        @forelse($cases as $case)
            @php
                $isDone = $case->my_submission ? true : false;

                // Style Logic (Semua Kuning/Emas/Netral)
                $accentClass = 'accent-active';
                $badgeText = 'TERSEDIA';
                $badgeClass = 'pill-yellow';

                if($isDone) {
                    $accentClass = 'accent-done';
                    $badgeText = 'SELESAI';
                    $badgeClass = 'pill-gold';
                } elseif(!$isOpened) {
                    $accentClass = 'accent-locked';
                    $badgeText = 'LOCKED';
                    $badgeClass = 'pill-grey';
                }
            @endphp

            <div class="info-card {{ $accentClass }}">
                {{-- Badge --}}
                <span class="status-pill {{ $badgeClass }}">{{ $badgeText }}</span>

                {{-- ID --}}
                <small class="fw-bold d-block mb-1" style="color: #8d6e63;">CASE #{{ $case->id }}</small>

                {{-- Title & Desc --}}
                <h5 class="fw-bold mb-2 w-75" style="color: var(--bee-text);">{{ $case->title }}</h5>
                <p class="text-muted small mb-3 lh-sm">
                    {{ Str::limit($case->description, 90) }}
                </p>

                {{-- Actions --}}
                <div class="d-flex gap-2 pt-2 border-top mt-3" style="border-color: #fff8e1 !important;">
                    @if($case->file_pdf)
                        <a href="{{ asset('storage/'.$case->file_pdf) }}" target="_blank" class="btn btn-sm btn-bee-outline rounded-pill px-3">
                            <i class='bx bxs-file-pdf'></i> PDF
                        </a>
                    @endif

                    @if($isDone)
                        <button class="btn btn-sm btn-bee-dark rounded-pill px-3 flex-grow-1 fw-bold" disabled>
                            RANKING #{{ $case->my_submission->rank }}
                        </button>
                    @elseif($isOpened)
                        <button class="btn btn-sm btn-bee rounded-pill px-3 flex-grow-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#submitCaseModal{{ $case->id }}">
                            <i class='bx bx-send'></i> KIRIM JAWABAN
                        </button>
                    @else
                        <button class="btn btn-sm btn-secondary rounded-pill px-3 flex-grow-1" disabled>WAKTU HABIS</button>
                    @endif
                </div>
            </div>

            {{-- MODAL SUBMISSION --}}
            @if(!$isDone && $isOpened)
            <div class="modal fade" id="submitCaseModal{{ $case->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold" style="color: var(--bee-text);">Laporan Misi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('main.cases.submit', $case->id) }}" method="POST" enctype="multipart/form-data"
                              onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('.btn-text').classList.add('d-none'); this.querySelector('.btn-loader').classList.remove('d-none');">
                            @csrf
                            <div class="modal-body px-4 pt-3 pb-4">
                                <p class="small text-muted mb-3">Upload file atau kirim link jawaban.</p>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Upload File</label>
                                    <div class="upload-box">
                                        <i class='bx bxs-cloud-upload fs-1 mb-2' style="color: var(--bee-dark);"></i>
                                        <h6 class="fw-bold mb-0" style="color: var(--bee-text);">Tap untuk Upload</h6>
                                        <small class="text-muted">PDF, DOC, ZIP</small>
                                        <input type="file" name="submission_file" class="upload-input" accept=".pdf,.doc,.docx,.zip,.rar"
                                               onchange="document.getElementById('fileName{{$case->id}}').innerText = this.files[0].name; document.getElementById('fileName{{$case->id}}').style.color = '#f57f17';">
                                    </div>
                                    <div id="fileName{{$case->id}}" class="mt-2 text-center small fw-bold"></div>
                                </div>

                                <div class="text-center small text-muted fw-bold mb-3">- ATAU -</div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Link External</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light"><i class='bx bx-link'></i></span>
                                        <input type="url" name="submission_text" class="form-control border-0 bg-light" placeholder="https://...">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-bee w-100 rounded-pill shadow-sm">
                                    <span class="btn-text">KIRIM SEKARANG</span>
                                    <span class="btn-loader d-none"><i class='bx bx-loader-alt bx-spin'></i> SENDING...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        @empty
            <div class="text-center py-5">
                <i class='bx bx-folder-open fs-1 text-muted mb-2'></i>
                <p class="text-muted">Belum ada misi aktif.</p>
            </div>
        @endforelse
    </div>

    {{-- 5. GUIDELINE INVENTORY (CARD STYLE - SAMA SEPERTI CASE) --}}
    @if(count($myGuidelines) > 0)
    <div class="px-4 pb-5">
        <h6 class="fw-bold mb-3 ps-2 border-start border-4" style="color: var(--bee-text); border-color: var(--bee-gold) !important;">Inventory Guideline</h6>

        @foreach($myGuidelines as $gl)
            <div class="info-card accent-inventory">
                {{-- Badge --}}
                <span class="status-pill pill-gold">DATA UNLOCKED</span>

                {{-- Header --}}
                <small class="fw-bold d-block mb-1" style="color: #8d6e63;">DOKUMEN RAHASIA</small>

                {{-- Title & Desc --}}
                <h5 class="fw-bold mb-2 w-75" style="color: var(--bee-text);">{{ $gl->title }}</h5>
                <p class="text-muted small mb-3 lh-sm">
                    {{ Str::limit($gl->description ?? 'Informasi rahasia untuk membantu pengerjaan kasus.', 90) }}
                </p>

                {{-- Footer Actions (Download) --}}
                <div class="pt-2 border-top mt-3" style="border-color: #fff8e1 !important;">
                    <a href="{{ asset('storage/'.$gl->file_pdf) }}" target="_blank" class="btn btn-sm btn-bee w-100 rounded-pill fw-bold shadow-sm">
                        <i class='bx bxs-download me-1'></i> OPEN
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @endif

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
                    el.style.color = "#d32f2f"; // Merah sedikit saat habis
                    setTimeout(() => window.location.reload(), 1500);
                }
                return;
            }

            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            const el = document.getElementById("countdown");
            if(el) el.innerHTML = (h<10?"0"+h:h) + ":" + (m<10?"0"+m:m) + ":" + (s<10?"0"+s:s);
        }, 1000);
    }
</script>
@endpush
