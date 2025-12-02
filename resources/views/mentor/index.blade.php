@extends('mentor.layouts.master')

@section('title', 'Mentor Command')

@section('styles')
<style>
    :root {
        --squid-teal: #00a79d;
        --squid-dark: #232b2b;
        --bg-light: #f4f6f8;
    }

    /* --- HEADER --- */
    .mentor-header {
        background: linear-gradient(135deg, #00a79d 0%, #008f87 100%);
        padding: 30px 25px 90px 25px;
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 167, 157, 0.3);
    }

    /* --- STATS CARDS (Floating) --- */
    .stats-container {
        display: flex;
        gap: 15px;
        margin: -60px 20px 25px 20px;
        position: relative;
        z-index: 10;
    }
    .stat-card {
        flex: 1;
        background: white;
        padding: 15px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-bottom: 4px solid #fff;
        transition: transform 0.2s;
    }
    .stat-card:active { transform: scale(0.98); }
    .stat-card.active { border-bottom-color: #ffab00; } /* Pending - Kuning */
    .stat-card.done { border-bottom-color: #00a79d; } /* Done - Hijau */

    .stat-num { font-size: 1.5rem; font-weight: 800; color: var(--squid-dark); line-height: 1; }
    .stat-label { font-size: 0.65rem; text-transform: uppercase; color: #888; font-weight: 700; margin-top: 5px; }

    /* --- REVIEW CARD --- */
    .review-card {
        background: white;
        border-radius: 20px;
        margin: 0 20px 20px 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #eee;
    }
    .review-header {
        padding: 15px 20px;
        background: #fcfcfc;
        border-bottom: 1px dashed #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .group-name { font-weight: 800; color: var(--squid-dark); font-size: 0.9rem; }
    .submit-time { font-size: 0.7rem; color: #999; }

    .review-body { padding: 20px; }

    .file-preview {
        background: #f0fdf4; /* Light Green Bg */
        border: 1px dashed var(--squid-teal);
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Action Buttons */
    .action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 0 20px 20px 20px;
    }
    .btn-action {
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .btn-action:active { transform: scale(0.97); }
    .btn-approve { background: var(--squid-teal); color: white; box-shadow: 0 4px 15px rgba(0, 167, 157, 0.3); }
    .btn-reject { background: #fff2f2; color: #ff3e1d; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        opacity: 0.6;
    }
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

            {{-- Profile Icon --}}
            <div class="avatar bg-white bg-opacity-25 rounded-circle p-1">
                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
            </div>
        </div>

        {{-- Decoration --}}
        <i class='bx bx-check-shield position-absolute' style="font-size: 8rem; top: 10px; right: -20px; opacity: 0.1; color: white;"></i>
    </div>

    {{-- 2. STATS (FLOATING) --}}
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

    {{-- 3. QUEUE LIST --}}
    <div class="mb-5">
        <div class="d-flex align-items-center px-4 mb-3">
            <div class="bg-primary rounded-pill me-2" style="width: 4px; height: 20px;"></div>
            <h6 class="fw-bold text-dark mb-0">Submission Queue</h6>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-4 mb-3 border-0 shadow-sm rounded-3">
                <small><i class='bx bx-check-circle me-1'></i> {{ session('success') }}</small>
            </div>
        @endif

        @forelse($pendingSubmissions as $sub)
            <div class="review-card">
                {{-- Header Card --}}
                <div class="review-header">
                    <span class="group-name d-flex align-items-center">
                        <div class="avatar avatar-xs bg-label-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                            <span class="fw-bold" style="font-size: 10px;">{{ substr($sub->group->name ?? 'G', 0, 1) }}</span>
                        </div>
                        {{ $sub->group->name ?? 'Unknown Group' }}
                    </span>
                    <span class="submit-time d-flex align-items-center">
                        <i class='bx bx-time-five me-1'></i> {{ $sub->updated_at->diffForHumans() }}
                    </span>
                </div>

                {{-- Body Card --}}
                <div class="review-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem;">MISSION TITLE</small>
                            <h5 class="text-dark fw-bold mb-1" style="font-size: 1rem;">{{ $sub->challenge->nama ?? 'Challenge Title' }}</h5>
                        </div>
                        <div class="badge bg-label-success rounded-pill">
                            + SQ$ {{ number_format($sub->challenge->price ?? 0) }}
                        </div>
                    </div>

                    {{-- FILE / LINK PREVIEW --}}
                    @if($sub->submission_text)
                        <div class="bg-lighter p-3 rounded-3 mb-3 border">
                            <small class="d-block text-muted fw-bold mb-1" style="font-size: 0.7rem;">JAWABAN / LINK:</small>
                            @if(filter_var($sub->submission_text, FILTER_VALIDATE_URL))
                                <a href="{{ $sub->submission_text }}" target="_blank" class="fw-bold text-primary text-decoration-none d-flex align-items-center">
                                    <i class='bx bx-link me-1'></i> Buka Link Submission
                                </a>
                            @else
                                <p class="mb-0 text-dark small">{{ $sub->submission_text }}</p>
                            @endif
                        </div>
                    @endif

                    @if($sub->file_path)
                        @php
                            $extension = pathinfo($sub->file_path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImage)
                            {{-- TAMPILAN JIKA FILE ADALAH GAMBAR (FOTO HP) --}}
                            <div class="mt-3">
                                <small class="d-block text-muted fw-bold mb-2" style="font-size: 0.7rem;">FOTO BUKTI:</small>
                                <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$sub->file_path) }}"
                                         class="img-fluid rounded border shadow-sm"
                                         style="max-height: 350px; width: 100%; object-fit: cover;"
                                         alt="Bukti Submission">
                                </a>
                                <small class="text-center d-block mt-1 text-muted" style="font-size: 0.65rem;">(Klik gambar untuk memperbesar)</small>
                            </div>
                        @else
                            {{-- TAMPILAN JIKA FILE ADALAH DOKUMEN (PDF/ZIP) --}}
                            <div class="file-preview">
                                <div class="bg-white p-2 rounded shadow-sm text-danger">
                                    <i class='bx bxs-file-pdf fs-2'></i>
                                </div>
                                <div class="overflow-hidden">
                                    <small class="d-block text-muted fw-bold" style="font-size: 0.7rem;">FILE DOKUMEN</small>
                                    <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank" class="fw-bold text-dark text-decoration-none d-block text-truncate">
                                        Download / Lihat File
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Footer Action --}}
                <div class="action-grid">
                    <button class="btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $sub->id }}">
                        <i class='bx bx-x-circle fs-5'></i> REJECT
                    </button>

                    {{-- Form Approve Langsung --}}
                    <form action="{{ route('mentor.submission.approve', $sub->id) }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn-action btn-approve w-100">
                            <i class='bx bx-check-circle fs-5'></i> APPROVE
                        </button>
                    </form>
                </div>
            </div>

            {{-- MODAL REJECT (Unique ID per Item) --}}
            <div class="modal fade" id="rejectModal{{ $sub->id }}" tabindex="-1" aria-hidden="true">
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
                                <textarea name="feedback" class="form-control bg-light border-0" rows="3" placeholder="Contoh: Foto buram / Link error..." required></textarea>

                                <div class="d-grid mt-3 gap-2">
                                    <button type="submit" class="btn btn-danger rounded-pill fw-bold">Kirim Penolakan</button>
                                    <button type="button" class="btn btn-label-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="empty-state">
                <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="150" class="mb-3" alt="Relax">
                <h6 class="text-dark fw-bold mb-1">All Caught Up!</h6>
                <p class="small text-muted">Kerjaan beres, saatnya ngopi ☕.</p>
            </div>
        @endforelse
    </div>
@endsection
