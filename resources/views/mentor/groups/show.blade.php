@extends('mentor.layouts.master')
@section('title', $group->name)

@section('styles')
<style>
    /* --- COLOR PALETTE --- */
    .bg-squid-soft { background-color: rgba(0, 167, 157, 0.1) !important; color: #00a79d !important; }
    .text-squid { color: #00a79d !important; }
    .border-squid { border-color: #00a79d !important; }

    .detail-header {
        background: white;
        padding: 20px 20px 30px 20px;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        position: relative;
    }
    .balance-badge {
        background: linear-gradient(45deg, #00a79d, #00d2c6);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
        display: inline-block;
        margin-top: 10px;
    }
    .section-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #b0b0b0;
        text-transform: uppercase;
        margin-bottom: 15px;
        letter-spacing: 1px;
        margin-top: 20px;
        padding-left: 5px;
        border-left: 3px solid #00a79d;
    }

    /* Active Card Style */
    .active-card {
        background: white;
        border: 1px solid #eee;
        border-left: 4px solid #ffab00; /* Kuning Warning */
        border-radius: 12px;
        padding: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .active-card:active { transform: scale(0.98); }

    .history-item {
        background: white;
        border-radius: 12px;
        margin-bottom: 10px;
        padding: 15px;
        border-left: 4px solid #eee;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .history-item.approved { border-left-color: #00a79d; }
    .history-item.rejected { border-left-color: #ff3e1d; }

    /* Member Card Updated */
    .member-card {
        background: white;
        padding: 15px 10px;
        border-radius: 16px;
        text-align: center;
        min-width: 90px;
        margin-right: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        border: 1px solid #f8f9fa;
    }
    /* Avatar Fix Positioning */
    .custom-avatar {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px auto;
        font-size: 1.2rem;
        /* Ganti warna default (biasanya biru) jadi abu/hijau soft */
        background-color: #f0f2f5;
        color: #555;
    }
    .custom-avatar.is-captain {
        background-color: rgba(0, 167, 157, 0.1);
        color: #00a79d;
        border: 1px solid rgba(0, 167, 157, 0.2);
    }
</style>
@endsection

@section('content')

    {{-- HEADER INFO --}}
    <div class="detail-header text-center pt-4">
        {{-- FIX ROUTE: Kembali ke list group event ini --}}
        <a href="{{ route('mentor.groups.index', $event->id) }}" class="btn btn-sm btn-light rounded-circle position-absolute start-0 top-0 mt-4 ms-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
            <i class='bx bx-chevron-left fs-4'></i>
        </a>

        {{-- Icon Group (Green Theme) --}}
        <div class="avatar bg-squid-soft p-3 rounded-circle mx-auto mb-2" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
            <span class="bx bxs-group fs-1"></span>
        </div>

        <h4 class="fw-bold text-dark mb-0">{{ $group->name }}</h4>
        <div class="balance-badge">
            <span style="opacity: 0.8; font-size: 0.7rem; font-weight: 400;">TOTAL SALDO</span> <br>
            $ {{ number_format($group->squid_dollar) }}
        </div>

        <div class="row mt-4 pt-3 border-top mx-2">
            <div class="col-4 border-end">
                <h5 class="mb-0 fw-bold text-dark">{{ $group->members->count() }}</h5>
                <small class="text-muted fw-bold" style="font-size: 0.65rem;">MEMBER</small>
            </div>
            <div class="col-4 border-end">
                {{-- Text Success is usually green, ok --}}
                <h5 class="mb-0 fw-bold text-success">{{ $group->completedChallenges->count() }}</h5>
                <small class="text-muted fw-bold" style="font-size: 0.65rem;">SELESAI</small>
            </div>
            <div class="col-4">
                <h5 class="mb-0 fw-bold text-warning">{{ $group->activeChallenges->count() }}</h5>
                <small class="text-muted fw-bold" style="font-size: 0.65rem;">AKTIF</small>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-4">

        {{-- SECTION 1: ANGGOTA TIM --}}
        <div class="section-title">👥 Anggota Tim</div>
        <div class="d-flex overflow-auto pb-3 ps-1" style="margin-left: -5px;">
            @foreach($group->members as $member)
                @php $isCaptain = $member->user->id == $group->captain_id; @endphp
                <div class="member-card">
                    {{-- Avatar diperbaiki (Ganti tema biru ke hijau/neutral) --}}
                    <div class="custom-avatar rounded-circle fw-bold {{ $isCaptain ? 'is-captain' : '' }}">
                        {{ substr($member->user->name, 0, 1) }}
                    </div>
                    <small class="d-block fw-bold text-dark text-truncate" style="font-size: 0.75rem; max-width: 70px;">
                        {{ explode(' ', $member->user->name)[0] }}
                    </small>
                    <small class="text-muted d-block" style="font-size: 0.6rem;">
                        {{ $isCaptain ? 'Captain' : 'Member' }}
                    </small>
                </div>
            @endforeach
        </div>

        {{-- SECTION 2: CHALLENGE AKTIF --}}
        @if($group->activeChallenges->count() > 0)
            <div class="section-title mt-2">⚡ Sedang Dikerjakan</div>
            @foreach($group->activeChallenges as $active)
                <div class="active-card mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-label-{{ $active->status == 'pending' ? 'warning' : 'secondary' }} rounded-pill mb-1" style="font-size: 0.65rem;">
                                {{ strtoupper($active->status) }}
                            </span>
                            <h6 class="fw-bold text-dark mb-0">{{ $active->challenge->nama }}</h6>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block" style="font-size: 0.6rem;">Reward</small>
                            {{-- Ganti text-primary (biru) jadi text-squid (hijau) --}}
                            <span class="fw-bold text-squid">$ {{ number_format($active->challenge->price) }}</span>
                        </div>
                    </div>

                    {{-- FILE CHALLENGE (SOAL) --}}
                    @if($active->challenge->file_pdf)
                        <a href="{{ asset('storage/'.$active->challenge->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-dark w-100 mb-2 d-flex align-items-center justify-content-center gap-2">
                            <i class='bx bxs-file-pdf text-danger'></i> Lihat Soal (PDF)
                        </a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary w-100 mb-2" disabled>Tidak ada file soal</button>
                    @endif

                    {{-- LIHAT SUBMISI (Jika Status Pending) --}}
                    @if($active->status == 'pending')
                        <hr class="my-2 border-dashed">
                        <div class="bg-label-warning p-2 rounded text-center">
                            <small class="fw-bold text-dark d-block mb-1">🔥 Menunggu Review Anda!</small>
                            <a href="{{ route('mentor.dashboard', $event->id) }}" class="btn btn-xs btn-warning rounded-pill px-3">
                                Ke Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="alert alert-light border-dashed text-center">
                <small class="text-muted">Kelompok ini sedang tidak mengerjakan challenge.</small>
            </div>
        @endif

        {{-- SECTION 3: RIWAYAT PENGERJAAN --}}
        <div class="section-title mt-4">🕒 Riwayat Pengerjaan</div>

        @forelse($group->completedChallenges as $done)
            <div class="history-item approved d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="max-width: 70%;">
                    <div class="me-3">
                        <i class='bx bxs-check-shield text-success fs-3'></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.85rem;">{{ $done->challenge->nama }}</h6>
                        <small class="text-muted" style="font-size: 0.65rem;">{{ $done->updated_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success rounded-pill shadow-sm">+{{ number_format($done->challenge->price) }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-4 bg-white rounded-3 shadow-sm border border-dashed">
                <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="80" class="mb-2 opacity-50">
                <br>
                <small class="text-muted fst-italic">Belum ada challenge yang selesai.</small>
            </div>
        @endforelse

    </div>
    <div style="height: 50px;"></div>
@endsection
