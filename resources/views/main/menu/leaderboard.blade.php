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

    /* MONEY */
    .rank-money {
        background-color: #e0f2f1; color: #00a79d;
        font-weight: 800; padding: 6px 12px;
        border-radius: 20px; font-size: 0.8rem;
        min-width: 80px; text-align: center;
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
</div>

<div class="container px-3 pb-5" style="margin-top: -20px;">

    <div class="rank-list">
        @php
            $allRanks = $topThree->merge($others);
        @endphp

        @forelse($allRanks as $index => $grp)
            <div class="rank-item {{ $grp->id == $myGroupId ? 'my-team-row' : '' }}">
                <div class="rank-number">#{{ $index + 1 }}</div>
                <div class="rank-info">
                    <h6>
                        {{ $grp->name }}
                        @if($grp->id == $myGroupId)
                            <i class='bx bxs-user-circle ms-1 text-primary'></i>
                        @endif
                    </h6>
                </div>
                <div class="rank-money">
                    ${{ number_format($grp->squid_dollar/1000, 0) }}K
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">Belum ada data.</div>
        @endforelse
    </div>
</div>
@endsection
