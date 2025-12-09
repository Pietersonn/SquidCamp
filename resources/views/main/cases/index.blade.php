@extends('main.layouts.mobileMaster')

@section('title', 'Mission Center')

@section('styles')
<style>
    /* CSS Root & Basic Setup */
    :root {
        --bee-pale: #fffde7; --bee-light: #fff59d;
        --bee-main: #fdd835; --bee-gold: #fbc02d;
        --bee-dark: #f57f17; --bee-text: #4e342e;
    }
    body { background-color: #fffbf0; }

    /* ... STYLE LAINNYA SAMA SEPERTI SEBELUMNYA ... */

    .case-header { background: linear-gradient(135deg, var(--bee-main) 0%, var(--bee-gold) 100%); border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; padding: 40px 25px 120px 25px; color: var(--bee-text); text-align: center; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(251, 192, 45, 0.3); }
    .header-icon { font-size: 8rem; position: absolute; opacity: 0.1; color: var(--bee-text); }

    .timer-card { background: white; border-radius: 20px; padding: 15px 25px; margin: -85px 20px 30px 20px; box-shadow: 0 15px 40px rgba(249, 168, 37, 0.15); position: relative; z-index: 10; text-align: center; border: 2px solid var(--bee-light); }
    .timer-label { font-size: 0.7rem; font-weight: 800; letter-spacing: 2px; color: var(--bee-dark); text-transform: uppercase; display: block; margin-bottom: 5px; }
    .timer-digits { font-family: 'Courier New', Courier, monospace; font-weight: 900; color: var(--bee-text); font-size: 2.5rem; line-height: 1; letter-spacing: -1px; }

    /* SHOP GRID */
    .shop-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 0 20px 30px 20px; }
    .shop-card { background: white; border: 2px solid var(--bee-main); border-radius: 16px; padding: 15px; text-align: center; position: relative; transition: transform 0.2s; display: flex; flex-direction: column; justify-content: space-between; height: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; }
    .shop-card:active { transform: scale(0.98); }
    .shop-icon { width: 50px; height: 50px; background: var(--bee-pale); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--bee-dark); font-size: 1.5rem; }
    .shop-title { font-weight: 800; font-size: 0.85rem; color: var(--bee-text); line-height: 1.2; margin-bottom: 5px; }
    .shop-price { font-size: 0.9rem; font-weight: 900; color: var(--bee-dark); margin-bottom: 10px; display: block; }

    .btn-buy { background: var(--bee-main); color: var(--bee-text); border: none; border-radius: 20px; font-size: 0.75rem; font-weight: 800; width: 100%; padding: 8px 0; }
    .btn-sold { background: #e0e0e0; color: #9e9e9e; cursor: not-allowed; width: 100%; border:none; border-radius: 20px; padding: 8px 0; font-size: 0.75rem; font-weight: 800;}

    .shop-owned { background: #eee; border-color: #ddd; opacity: 0.8; }
    .shop-owned .shop-icon { background: #ddd; color: #999; }
    .shop-owned .shop-title { color: #888; }

    /* Stock Badge */
    .badge-stock {
        position: absolute; top: 10px; left: 10px; font-size: 0.6rem;
        background: rgba(255, 255, 255, 0.9); border: 1px solid var(--bee-dark); color: var(--bee-dark);
        padding: 2px 8px; border-radius: 10px; font-weight: 800; z-index: 2;
    }
    .badge-soldout {
        position: absolute; top: 10px; left: 10px; font-size: 0.6rem;
        background: #ff3e1d; color: white; border: none;
        padding: 2px 8px; border-radius: 10px; font-weight: 800; z-index: 2;
    }

    /* Missions & Inventory */
    .info-card { background: white; border-radius: 18px; padding: 20px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #fff3e0; position: relative; overflow: hidden; }
    .accent-active { border-left: 5px solid var(--bee-main); }
    .accent-done { border-left: 5px solid var(--bee-dark); background: #fffdf5; }
    .accent-locked { border-left: 5px solid #d7ccc8; }
    .accent-inventory { border-left: 5px solid var(--bee-gold); background: #fffff0; }
    .status-pill { position: absolute; top: 15px; right: 15px; font-size: 0.65rem; font-weight: 800; padding: 5px 12px; border-radius: 30px; text-transform: uppercase; }
    .pill-yellow { background: var(--bee-pale); color: var(--bee-dark); border: 1px solid var(--bee-light); }
    .pill-gold { background: #fff8e1; color: #ff6f00; border: 1px solid var(--bee-gold); }
    .pill-grey { background: #eceff1; color: #90a4ae; }

    .btn-bee { background: linear-gradient(45deg, var(--bee-main), var(--bee-gold)); color: var(--bee-text); border: none; font-weight: 700; box-shadow: 0 4px 10px rgba(253, 216, 53, 0.4); }
    .btn-bee-outline { border: 2px solid var(--bee-main); color: var(--bee-text); background: transparent; font-weight: 700; }
    .btn-bee-dark { background: var(--bee-text); color: var(--bee-main); }
    .upload-box { border: 2px dashed var(--bee-gold); background: var(--bee-pale); border-radius: 15px; padding: 25px; text-align: center; position: relative; }
    .upload-input { position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; }
</style>
@endsection

@section('content')

    {{-- HEADER --}}
    <div class="case-header">
        <h2 class="fw-bold mb-0" style="color: #3e2723;">SQUID CASE</h2>
        <p class="opacity-75 small mb-0 fw-bold">Selesaikan case untuk dapat menuju ke Squid Show</p>
        <i class='bx bx-hive header-icon' style="top: -20px; left: -20px; transform: rotate(-15deg);"></i>
        <i class='bx bx-data header-icon' style="bottom: 10px; right: -20px; font-size: 6rem; transform: rotate(15deg);"></i>
    </div>

    {{-- TIMER --}}
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

    {{-- SHOP SECTION --}}
    @if($isOpened)
        <div class="px-4 mb-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold m-0" style="color: var(--bee-text);">
                <i class='bx bxs-store-alt me-1'></i>Secret Shop
            </h6>
            {{-- UPDATED: MENAMPILKAN SALDO BANK --}}
            <span class="badge rounded-pill" style="background: var(--bee-text); color: var(--bee-main);">
                Bank: ${{ number_format($group->bank_balance ?? 0, 0, ',', '.') }}
            </span>
        </div>

        <div class="shop-grid">
            @foreach($allGuidelines as $index => $item)
                @php
                    $displayName = $item->is_owned ? $item->title : "Guideline " . ($index + 1);
                    $hasStock = $item->stock > 0;
                @endphp

                @if($item->is_owned)
                    {{-- ITEM SUDAH DIBELI --}}
                    <div class="shop-card shop-owned">
                        <div class="shop-icon"><i class='bx bxs-folder-open'></i></div>
                        <div class="shop-title">{{ $displayName }}</div>
                        <span class="badge bg-secondary text-white rounded-pill mt-2">OWNED</span>
                    </div>
                @else
                    {{-- ITEM BELUM DIBELI --}}
                    <div class="shop-card">

                        {{-- INDIKATOR STOK --}}
                        @if($hasStock)
                            <span class="badge-stock">STOK: {{ $item->stock }}/5</span>
                        @else
                            <span class="badge-soldout">SOLD OUT</span>
                        @endif

                        <div class="shop-icon"><i class='bx bxs-lock-alt'></i></div>
                        <div class="shop-title">{{ $displayName }}</div>

                        <div>
                            <span class="shop-price">$ {{ number_format($item->price) }}</span>

                            @if($hasStock)
                                <button type="button" class="btn-buy"
                                    onclick="openBuyModal('{{ $item->id }}', '{{ $displayName }}', '{{ $item->price }}')">
                                    BELI
                                </button>
                            @else
                                <button type="button" class="btn-sold" disabled>HABIS</button>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- MISSION LIST --}}
    <div class="px-4 mb-4">
        <h6 class="fw-bold mb-3 ps-2 border-start border-4" style="color: var(--bee-text); border-color: var(--bee-main) !important;">Mission List</h6>

        @forelse($cases as $case)
            @php
                $isDone = $case->my_submission ? true : false;
                $accentClass = 'accent-active'; $badgeText = 'TERSEDIA'; $badgeClass = 'pill-yellow';
                if($isDone) { $accentClass = 'accent-done'; $badgeText = 'SELESAI'; $badgeClass = 'pill-gold'; }
                elseif(!$isOpened) { $accentClass = 'accent-locked'; $badgeText = 'LOCKED'; $badgeClass = 'pill-grey'; }
            @endphp

            <div class="info-card {{ $accentClass }}">
                <span class="status-pill {{ $badgeClass }}">{{ $badgeText }}</span>
                <small class="fw-bold d-block mb-1" style="color: #8d6e63;">CASE #{{ $case->id }}</small>
                <h5 class="fw-bold mb-2 w-75" style="color: var(--bee-text);">{{ $case->title }}</h5>
                <p class="text-muted small mb-3 lh-sm">{{ Str::limit($case->description, 90) }}</p>

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
                        <form action="{{ route('main.cases.submit', $case->id) }}" method="POST" enctype="multipart/form-data">
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
                                <button type="submit" class="btn btn-bee w-100 rounded-pill shadow-sm">KIRIM SEKARANG</button>
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

    {{-- 5. INVENTORY --}}
    @if(count($myGuidelines) > 0)
    <div class="px-4 pb-5">
        <h6 class="fw-bold mb-3 ps-2 border-start border-4" style="color: var(--bee-text); border-color: var(--bee-gold) !important;">Inventory Guideline</h6>
        @foreach($myGuidelines as $gl)
            <div class="info-card accent-inventory">
                <span class="status-pill pill-gold">DATA UNLOCKED</span>
                <small class="fw-bold d-block mb-1" style="color: #8d6e63;">DOKUMEN RAHASIA</small>
                <h5 class="fw-bold mb-2 w-75" style="color: var(--bee-text);">{{ $gl->title }}</h5>
                <p class="text-muted small mb-3 lh-sm">
                    {{ Str::limit($gl->description ?? 'Informasi rahasia untuk membantu pengerjaan kasus.', 90) }}
                </p>
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

    {{-- MODAL KONFIRMASI --}}
    <div class="modal fade" id="buyConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <div style="width: 70px; height: 70px; background: #fff8e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class='bx bxs-cart-alt text-warning' style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: var(--bee-text);">Konfirmasi Pembelian</h5>
                    {{-- Updated Confirmation Text --}}
                    <p class="text-muted small mb-4">Apakah Anda yakin ingin membeli dokumen ini menggunakan <b>Saldo Bank</b>?</p>

                    <div class="bg-light p-3 rounded-3 mb-4 text-start">
                        <table class="w-100 small">
                            <tr>
                                <td class="text-muted">Item</td>
                                <td class="fw-bold text-end text-dark" id="modalItemName">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga</td>
                                <td class="fw-bold text-end text-danger" id="modalItemPrice">-</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('main.cases.buyGuideline') }}" method="POST">
                        @csrf
                        <input type="hidden" name="guideline_id" id="modalInputId">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-bee w-100 rounded-pill fw-bold">BELI SEKARANG</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    @php $isoEndTime = $event && $event->case_end_time ? $event->case_end_time->toIso8601String() : null; @endphp
    const endTimeStr = "{{ $isoEndTime }}";

    if(endTimeStr) {
        const endTime = new Date(endTimeStr).getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;
            if (distance < 0) {
                clearInterval(timer);
                const el = document.getElementById("countdown");
                if(el) { el.innerHTML = "00:00:00"; el.style.color = "#d32f2f"; setTimeout(() => window.location.reload(), 1500); }
                return;
            }
            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);
            const el = document.getElementById("countdown");
            if(el) el.innerHTML = (h<10?"0"+h:h) + ":" + (m<10?"0"+m:m) + ":" + (s<10?"0"+s:s);
        }, 1000);
    }

    function openBuyModal(id, title, price) {
        document.getElementById('modalInputId').value = id;
        document.getElementById('modalItemName').innerText = title;
        let formattedPrice = '$ ' + new Intl.NumberFormat('en-US').format(price);
        document.getElementById('modalItemPrice').innerText = formattedPrice;
        var myModal = new bootstrap.Modal(document.getElementById('buyConfirmModal'));
        myModal.show();
    }
</script>
@endpush
