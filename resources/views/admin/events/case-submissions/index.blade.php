@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Daftar Submisi Peserta')

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
        --squid-dark: #00796b;
    }

    /* --- PAGE HEADER STYLES (Dari Contoh Gokil) --- */
    .btn-squid {
        background-color: var(--squid-primary);
        color: #fff;
        border: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-squid:hover {
        background-color: var(--squid-dark);
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.3);
        transform: translateY(-2px);
    }

    /* --- COMPACT CARD STYLES --- */
    .submission-card {
        border: none;
        border-radius: 12px;
        background: #fff;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        overflow: hidden;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .submission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 167, 157, 0.15);
        border-bottom: 3px solid var(--squid-primary);
    }

    /* Header Kartu Compact */
    .compact-header {
        height: 60px;
        background: linear-gradient(135deg, var(--squid-light) 0%, #fff 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.2rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .rank-badge {
        font-weight: 800;
        font-size: 0.9rem;
        color: var(--squid-primary);
        background: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .reward-text {
        font-weight: bold;
        color: #28a745;
        font-size: 0.85rem;
    }

    .card-body {
        padding: 1.2rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .group-name {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .submitter-info {
        font-size: 0.75rem;
        color: #777;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .case-tag {
        font-size: 0.7rem;
        background-color: #f5f5f9;
        color: #697a8d;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 10px;
    }

    /* Tombol Action Kecil */
    .btn-action-sm {
        padding: 6px 12px;
        font-size: 0.75rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 100%;
        transition: 0.2s;
        text-decoration: none !important;
    }

    .btn-link-sub {
        background: rgba(0, 167, 157, 0.1);
        color: var(--squid-primary);
    }
    .btn-link-sub:hover {
        background: var(--squid-primary);
        color: #fff;
    }

    .btn-file-sub {
        background: #f8f9fa;
        color: #566a7f;
        border: 1px solid #d9dee3;
    }
    .btn-file-sub:hover {
        background: #e9ecef;
        color: #333;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #d9dee3;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- HEADER HALAMAN (Gaya Gokil) --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">
                <i class="bx bx-list-check fs-3 me-2"></i>Case Submissions
            </h4>
            <span class="text-muted">Event: <strong class="text-dark">{{ $event->name }}</strong></span>
            <span class="text-muted ms-2">|</span>
            <span class="text-muted ms-2">Total: <strong>{{ $submissions->count() }}</strong> jawaban</span>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary shadow-sm btn-lg">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
            {{-- Tombol Export (Opsional, tampilan saja) --}}
            <button class="btn btn-squid shadow-sm btn-lg" onclick="alert('Fitur Export Coming Soon!')">
                <i class="bx bx-export me-1"></i> Export Data
            </button>
        </div>
    </div>

    {{-- GRID CARD (Gaya Compact) --}}
    <div class="row g-3">
        @forelse($submissions as $submission)
            {{-- Responsif Grid: 4 kolom di layar besar --}}
            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                <div class="submission-card">

                    {{-- Header Kartu (Rank & Reward) --}}
                    <div class="compact-header">
                        <div class="rank-badge">
                            <i class='bx bx-trophy'></i> #{{ $submission->rank }}
                        </div>
                        <div class="reward-text">
                            +${{ number_format($submission->reward_amount) }}
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Tag Judul Case --}}
                        <div>
                            <span class="case-tag">
                                <i class="bx bx-briefcase-alt-2 me-1" style="font-size: 0.7rem;"></i>
                                {{ \Illuminate\Support\Str::limit($submission->case_title, 25) }}
                            </span>
                        </div>

                        {{-- Nama Group --}}
                        <h5 class="group-name" title="{{ $submission->group_name }}">
                            {{ $submission->group_name }}
                        </h5>

                        {{-- Info Pengirim & Waktu --}}
                        <div class="submitter-info">
                            <div class="avatar avatar-xs me-2">
                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                    {{ substr($submission->submitter_name, 0, 1) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-truncate" style="max-width: 120px;">
                                    {{ $submission->submitter_name }}
                                </span>
                                <span style="font-size: 0.65rem;">
                                    {{ \Carbon\Carbon::parse($submission->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Footer Kartu (Tombol) --}}
                        <div class="mt-auto pt-3 border-top">
                            <div class="row g-2">
                                {{-- Tombol Link --}}
                                <div class="col-6">
                                    @if($submission->submission_text)
                                        <a href="{{ $submission->submission_text }}" target="_blank" class="btn-action-sm btn-link-sub" title="Buka Link">
                                            <i class="bx bx-link"></i> Link
                                        </a>
                                    @else
                                        <span class="btn-action-sm text-muted bg-light" style="cursor: not-allowed; opacity: 0.6;">
                                            <i class="bx bx-x"></i> No Link
                                        </span>
                                    @endif
                                </div>

                                {{-- Tombol File --}}
                                <div class="col-6">
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn-action-sm btn-file-sub" title="Download File">
                                            <i class="bx bx-download"></i> File
                                        </a>
                                    @else
                                        <span class="btn-action-sm text-muted bg-light" style="cursor: not-allowed; opacity: 0.6;">
                                            <i class="bx bx-x"></i> No File
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center py-5 shadow-none bg-transparent">
                    <div class="card-body">
                        <i class="bx bx-folder-open empty-state-icon"></i>
                        <h5 class="text-muted fw-semibold mt-3">Belum ada submisi masuk</h5>
                        <p class="text-muted small">Peserta belum mengumpulkan jawaban untuk case apapun.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 
