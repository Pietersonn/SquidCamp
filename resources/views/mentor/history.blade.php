@extends('mentor.layouts.master')
@section('title', 'Activity History')

@section('styles')
<style>
    .header-history {
        background: white;
        padding: 20px 25px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    /* Timeline Style */
    .timeline-list {
        position: relative;
        padding-left: 30px;
        border-left: 2px solid #e0e0e0;
        margin-left: 10px;
        margin-top: 20px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    .timeline-dot {
        width: 16px; height: 16px;
        background: #fff;
        border: 4px solid #ccc;
        border-radius: 50%;
        position: absolute;
        left: -39px;
        top: 0px;
        z-index: 2;
    }
    .timeline-item.approved .timeline-dot { border-color: #00a79d; background: #e0f2f1; }
    .timeline-item.rejected .timeline-dot { border-color: #ff3e1d; background: #ffebee; }

    .history-card {
        background: white;
        border-radius: 16px;
        padding: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
</style>
@endsection

@section('content')

    <div class="header-history d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-dark">Activity Log</h5>
            <small class="text-muted">Aktivitas review anda</small>
        </div>
        <div class="badge bg-primary rounded-pill px-3">{{ $histories->total() }} Actions</div>
    </div>

    <div class="container-fluid px-4 pb-4">

        <div class="timeline-list">
            @forelse($histories as $history)
                <div class="timeline-item {{ $history->status }}">
                    <div class="timeline-dot"></div>

                    <div class="mb-2">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">
                            {{ $history->updated_at->format('d F Y') }} • {{ $history->updated_at->format('H:i') }}
                        </small>
                    </div>

                    <div class="history-card">
                        {{-- Card Header --}}
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <small class="fw-bold text-primary">
                                <i class='bx bx-group'></i> {{ $history->group->name }}
                            </small>
                            @if($history->status == 'approved')
                                <span class="badge bg-label-success rounded-pill" style="font-size: 0.65rem;">APPROVED</span>
                            @else
                                <span class="badge bg-label-danger rounded-pill" style="font-size: 0.65rem;">REJECTED</span>
                            @endif
                        </div>

                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $history->challenge->nama }}</h6>

                        @if($history->status == 'approved')
                            <p class="mb-2 text-muted small bg-light p-2 rounded mt-2">
                                <i class='bx bx-coin-stack text-warning me-1'></i> Reward <strong class="text-dark">SQ$ {{ number_format($history->challenge->price) }}</strong> telah dikirim.
                            </p>
                        @else
                            <div class="bg-label-danger p-2 rounded mt-2 mb-2">
                                <small class="text-danger fw-bold d-block mb-1">Alasan Penolakan:</small>
                                <p class="mb-0 small text-dark fst-italic">"{{ $history->mentor_feedback ?? 'Tidak ada feedback' }}"</p>
                            </div>
                        @endif

                        {{-- TOMBOL TOGGLE UNTUK LIHAT SUBMISSION --}}
                        <button class="btn btn-xs btn-outline-secondary w-100 rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $history->id }}" aria-expanded="false">
                            <i class='bx bx-show me-1'></i> Lihat Pekerjaan Mereka
                        </button>

                        {{-- ISI SUBMISSION (COLLAPSE) --}}
                        <div class="collapse mt-2" id="detail{{ $history->id }}">
                            <div class="card card-body bg-lighter p-2 border shadow-none">
                                <small class="fw-bold text-muted mb-1 d-block">Jawaban / Link:</small>
                                <p class="small text-dark mb-2 fst-italic border-bottom pb-2">
                                    {{ $history->submission_text ? Str::limit($history->submission_text, 100) : '-' }}
                                </p>

                                @if($history->file_path)
                                    <a href="{{ asset('storage/'.$history->file_path) }}" target="_blank" class="btn btn-xs btn-primary w-100">
                                        <i class='bx bxs-file-pdf'></i> Buka File Lampiran
                                    </a>
                                @else
                                    <small class="text-muted text-center d-block">Tidak ada file lampiran.</small>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-5" style="margin-left: -20px;">
                    <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="120" class="mb-3" style="opacity: 0.7;">
                    <h6 class="fw-bold text-muted">Belum ada riwayat.</h6>
                    <p class="small text-muted">Ayo mulai review tugas kelompok!</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $histories->links('pagination::simple-bootstrap-5') }}
        </div>

    </div>
    <div style="height: 50px;"></div>
@endsection
