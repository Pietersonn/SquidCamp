@extends('mentor.layouts.master')

@section('title', 'Mentor Command')

@section('styles')
<style>
    :root {
        --squid-teal: #00a79d;
        --squid-dark: #232b2b;
        --bg-light: #f4f6f8;
    }

    body { background-color: var(--bg-light); }

    /* --- HEADER --- */
    .mentor-header {
        background: linear-gradient(135deg, #00a79d 0%, #008f87 100%);
        padding: 30px 25px 80px 25px;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        color: white;
        position: relative;
        z-index: 1;
        box-shadow: 0 10px 20px rgba(0, 167, 157, 0.3);
    }

    /* --- WRAPPER UTAMA (PENTING) --- */
    .content-wrapper-fix {
        /* Tarik konten ke atas agar meniban header */
        margin-top: -40px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
        min-height: 100vh; /* Agar background tetap konsisten */
    }

    /* --- STATS CARDS --- */
    .stats-container {
        display: flex;
        gap: 10px;
        margin-bottom: 0px;
        width: 100%;
    }
    .stat-card {
        flex: 1;
        background: white;
        padding: 15px 5px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-bottom: 4px solid #fff;
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 60px;
    }
    .stat-card:active { transform: scale(0.98); }
    .stat-card.active { border-bottom-color: #ffab00; }
    .stat-card.done { border-bottom-color: #00a79d; }

    .stat-num { font-size: 1.4rem; font-weight: 800; color: var(--squid-dark); line-height: 1; margin-bottom: 2px; }
    .stat-label { font-size: 0.6rem; text-transform: uppercase; color: #888; font-weight: 700; }

    /* --- QUEUE SECTION (Nempel Stats) --- */
    .queue-section {
        /* Beri jarak sedikit dari stats card, tapi tetap terasa satu kesatuan */
        margin-top: 20px;
    }
    .queue-header {
        display: flex;
        align-items: center;
        padding: 0 5px;
        margin-bottom: 15px;
    }
    .queue-indicator {
        width: 4px; height: 18px; background: var(--squid-teal);
        border-radius: 10px; margin-right: 10px;
    }

    /* --- REVIEW CARD --- */
    .review-card {
        background: white;
        border-radius: 18px;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #eee;
    }
    .review-header {
        padding: 12px 15px;
        background: #fcfcfc;
        border-bottom: 1px dashed #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .group-name { font-weight: 800; color: var(--squid-dark); font-size: 0.85rem; }
    .submit-time { font-size: 0.65rem; color: #999; }

    .review-body { padding: 15px; }

    /* File Preview Button */
    .file-preview-btn {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 10px 12px;
        background: #f8f9fa;
        border: 1px dashed #ced4da;
        border-radius: 10px;
        color: #555;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        gap: 10px;
        margin-top: 15px;
        transition: all 0.2s;
    }
    .file-preview-btn:active { background: #e9ecef; transform: scale(0.98); }
    .file-preview-btn i { font-size: 1.3rem; }

    /* Action Buttons */
    .action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 0 15px 15px 15px;
    }
    .btn-action {
        border: none;
        padding: 10px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        display: flex; align-items: center; justify-content: center; gap: 5px;
        transition: transform 0.2s;
        cursor: pointer;
        width: 100%;
        text-decoration: none;
        color: white;
    }
    .btn-action:active { transform: scale(0.97); }
    .btn-approve { background: var(--squid-teal); box-shadow: 0 4px 15px rgba(0, 167, 157, 0.2); }
    .btn-reject { background: #fff2f2; color: #ff3e1d; border: 1px solid #ffdbdb; }

    .empty-state { text-align: center; padding: 40px 20px; opacity: 0.6; }
</style>
@endsection

@section('content')

    {{-- 1. HEADER --}}
    <div class="mentor-header">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h4 class="fw-bold text-white mb-0">MENTOR PANEL</h4>
                <p class="text-white opacity-75 small mb-0">Hi, {{ Auth::user()->name }}</p>
            </div>
        </div>
        <i class='bx bx-check-shield position-absolute' style="font-size: 8rem; top: 10px; right: -20px; opacity: 0.1; color: white;"></i>
    </div>

    {{-- WRAPPER UTAMA (OVERLAP) --}}
    <div class="content-wrapper-fix">

        {{-- 2. STATS CARDS (Nempel Header) --}}
        <div class="stats-container">
            <div class="stat-card active">
                <div class="stat-num text-warning">{{ $stats['pending_review'] ?? 0 }}</div>
                <div class="stat-label">Need Review</div>
            </div>
            <div class="stat-card done">
                <div class="stat-num text-success">{{ $stats['total_approved'] ?? 0 }}</div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-num text-primary">{{ $stats['total_groups'] ?? 0 }}</div>
                <div class="stat-label">Teams</div>
            </div>
        </div>

        {{-- 3. SUBMISSION QUEUE (Nempel Stats) --}}
        <div class="queue-section">
            <div class="queue-header">
                <div class="queue-indicator"></div>
                <h6 class="fw-bold text-dark mb-0">Submission Queue</h6>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3">
                    <small><i class='bx bx-check-circle me-1'></i> {{ session('success') }}</small>
                </div>
            @endif

            @forelse($pendingSubmissions as $sub)
                <div class="review-card">
                    <div class="review-header">
                        <span class="group-name d-flex align-items-center">
                            <div class="avatar avatar-xs bg-label-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <span class="fw-bold" style="font-size: 10px;">{{ substr($sub->group->name ?? 'G', 0, 1) }}</span>
                            </div>
                            {{ $sub->group->name ?? 'Unknown' }}
                        </span>
                        <span class="submit-time d-flex align-items-center">
                            <i class='bx bx-time-five me-1'></i> {{ $sub->updated_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="review-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.6rem;">MISSION</small>
                                <h5 class="text-dark fw-bold mb-1" style="font-size: 0.9rem;">{{ $sub->challenge->nama ?? '-' }}</h5>
                            </div>
                            <div class="badge bg-label-success rounded-pill">
                                +{{ number_format($sub->challenge->price ?? 0) }}
                            </div>
                        </div>

                        {{-- LINK --}}
                        @if($sub->submission_text)
                            <a href="{{ $sub->submission_text }}" target="_blank" class="file-preview-btn text-primary border-primary bg-label-primary">
                                <i class='bx bx-link'></i>
                                <span class="text-truncate">Buka Link Jawaban</span>
                            </a>
                        @endif

                        {{-- FILE --}}
                        @if($sub->file_path)
                            @php
                                $ext = pathinfo($sub->file_path, PATHINFO_EXTENSION);
                                $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
                                $icon = $isImg ? 'bxs-image-alt text-warning' : 'bxs-file-pdf text-danger';
                                $txt = $isImg ? 'Lihat Foto Bukti' : 'Download File';
                            @endphp
                            <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank" class="file-preview-btn">
                                <i class='bx {{ $icon }} fs-4'></i>
                                <div class="d-flex flex-column text-start overflow-hidden">
                                    <span class="text-dark">{{ $txt }}</span>
                                    <small class="text-muted fw-light text-truncate" style="font-size: 0.65rem;">{{ basename($sub->file_path) }}</small>
                                </div>
                                <i class='bx bx-chevron-right ms-auto text-muted'></i>
                            </a>
                        @endif
                    </div>

                    <div class="action-grid">
                        <button type="button" class="btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $sub->id }}">
                            <i class='bx bx-x-circle fs-5'></i> REJECT
                        </button>
                        <form action="{{ route('mentor.submission.approve', $sub->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn-action btn-approve w-100">
                                <i class='bx bx-check-circle fs-5'></i> APPROVE
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="120" class="mb-3" style="opacity: 0.5;">
                    <h6 class="text-dark fw-bold mb-1">Semua Beres!</h6>
                    <p class="small text-muted">Tidak ada submission yang perlu direview.</p>
                </div>
            @endforelse
        </div>

        <div style="height: 80px;"></div>
    </div>

    {{-- MODALS --}}
    @foreach($pendingSubmissions as $sub)
        <div class="modal fade" id="rejectModal{{ $sub->id }}" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0 pt-4 px-4 justify-content-center">
                        <div class="text-center">
                            <div class="avatar bg-label-danger rounded-circle mb-2 mx-auto p-2" style="width:50px; height:50px;">
                                <i class='bx bx-x fs-1'></i>
                            </div>
                            <h5 class="modal-title fw-bold text-dark">Tolak Misi?</h5>
                        </div>
                    </div>
                    <form action="{{ route('mentor.submission.reject', $sub->id) }}" method="POST">
                        @csrf
                        <div class="modal-body px-4 pt-2 pb-4">
                            <p class="text-muted small text-center mb-3">Berikan alasan agar kelompok bisa memperbaiki.</p>
                            <textarea name="feedback" class="form-control bg-light border-0" rows="3" placeholder="Alasan penolakan..." required></textarea>
                            <div class="d-grid mt-3 gap-2">
                                <button type="submit" class="btn btn-danger rounded-pill fw-bold">Kirim Penolakan</button>
                                <button type="button" class="btn btn-label-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
<script>
    // FIX MODAL HP
    $(document).ready(function() {
        $('.modal').appendTo("body");
    });
</script>
@endpush
