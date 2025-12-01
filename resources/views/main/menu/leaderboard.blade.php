@extends('main.layouts.mobileMaster')

@section('title', 'Leaderboard')

@section('styles')
<style>
    /* Background Header */
    .rank-header {
        background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
        padding: 30px 20px 40px 20px;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        text-align: center;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 167, 157, 0.2);
    }

    /* RANK LIST CONTAINER */
    .rank-list {
        background: white;
        border-radius: 20px;
        padding: 10px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    /* RANK ITEM */
    .rank-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f4f4f4;
    }
    .rank-item:last-child { border-bottom: none; }

    /* RANK NUMBER */
    .rank-number {
        width: 35px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #b4bdce;
        text-align: center;
        margin-right: 15px;
    }

    /* Special Rank Colors */
    .rank-item:nth-child(1) .rank-number { color: #FFD700; font-size: 1.3rem; }
    .rank-item:nth-child(2) .rank-number { color: #C0C0C0; font-size: 1.2rem; }
    .rank-item:nth-child(3) .rank-number { color: #CD7F32; font-size: 1.2rem; }

    /* INFO */
    .rank-info { flex-grow: 1; }
    .rank-info h6 { margin: 0; font-weight: 700; color: #333; font-size: 0.95rem; }

    /* MONEY BADGE */
    .rank-money {
        background-color: #e0f2f1; color: #00a79d;
        font-weight: 800; padding: 6px 12px;
        border-radius: 20px; font-size: 0.8rem;
        min-width: 80px; text-align: center;
        display: inline-block;
    }

    /* HIGHLIGHT MY TEAM */
    .my-team-row {
        background-color: #f0fdfa;
        border-left: 4px solid #00a79d;
    }
</style>
@endsection

@section('content')

<div class="rank-header">
    <h4 class="fw-bold text-white mb-1">Papan Peringkat</h4>
    <p class="text-white opacity-75 small mb-0">Top Groups - {{ $event->name }}</p>
    <div class="mt-2 badge bg-white text-primary bg-opacity-25 rounded-pill px-3">
        <i class='bx bxs-trophy'></i> Total Aset (Cash + Bank)
    </div>
</div>

<div class="container px-3 pb-5" style="margin-top: -20px;">

    <div class="rank-list">
        @php
            $allRanks = $topThree->merge($others);
        @endphp

        @forelse($allRanks as $index => $grp)
            <div class="rank-item {{ $grp->id == $myGroupId ? 'my-team-row' : '' }}">
                {{-- Nomor Peringkat --}}
                <div class="rank-number">#{{ $index + 1 }}</div>

                {{-- Nama Tim --}}
                <div class="rank-info">
                    <h6>
                        {{ $grp->name }}
                        @if($grp->id == $myGroupId)
                            <small class="text-primary ms-1" style="font-size: 0.7rem;">(Saya)</small>
                        @endif
                    </h6>
                    {{-- Rincian Cash & Bank Kecil --}}
                    <div style="font-size: 0.65rem; color: #888; margin-top: 2px;">
                        <span class="me-2"><i class='bx bxs-bank'></i> Bank: {{ number_format($grp->squid_dollar ?? 0) }}</span>
                        <span><i class='bx bx-wallet'></i> Cash: {{ number_format($grp->bank_balance ?? 0) }}</span>
                    </div>
                </div>

                {{-- Total Kekayaan --}}
                <div class="text-end">
                    <div class="rank-money">
                        {{-- Menggunakan total_wealth --}}
                        $ {{ number_format($grp->total_wealth ?? 0) }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                <i class='bx bx-ghost fs-1 mb-2 opacity-50'></i>
                <p>Belum ada data peringkat.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
