@extends('mentor.layouts.master')
@section('title', 'My Teams')

@section('styles')
<style>
    /* 1. Header Gradient Hijau (Tetap) */
    .header-teams {
        background: linear-gradient(135deg, #00a79d 0%, #008f87 100%);
        padding: 40px 25px 50px 25px;
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.2);
    }

    .teams-container { padding: 20px; }

    /* 2. Card Style */
    .team-card {
        background: white;
        border-radius: 18px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        border-left: 5px solid transparent;
        transition: transform 0.2s;
    }
    .team-card:active { transform: scale(0.98); }

    /* 3. Ikon Hijau */
    .team-icon {
        width: 50px; height: 50px;
        background: rgba(0, 167, 157, 0.1); /* Hijau Transparan */
        color: #00a79d;                      /* Hijau Solid */
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    /* 4. Stats Box: Background Abu Murni (#f4f4f4) bukan biru muda */
    .stats-box {
        background: #f4f4f4;
        border: 1px dashed #d0d0d0; /* Border abu murni */
        border-radius: 8px;
        padding: 8px;
        text-align: center;
    }

    /* --- ANTI-BIRU COLORS --- */
    /* Mengganti text-muted/secondary yang kebiruan */
    .text-label-gray { color: #888888 !important; } /* Abu-abu label */
    .text-value-dark { color: #333333 !important; } /* Hitam/Abu gelap */
    .text-squid { color: #00a79d !important; }      /* Hijau Squid */

</style>
@endsection

@section('content')

    {{-- HEADER --}}
    <div class="header-teams">
        <h3 class="fw-bold text-white mb-1">My Teams</h3>
        <p class="text-white opacity-75 small mb-0">Memantau progres {{ $groups->count() }} kelompok binaan.</p>
        <i class='bx bx-group position-absolute' style="font-size: 10rem; top: -20px; right: -30px; opacity: 0.1;"></i>
    </div>

    {{-- CONTENT --}}
    <div class="teams-container">
        @forelse($groups as $group)
        {{-- FIX: Menambahkan parameter 'event' => $event->id --}}
        <a href="{{ route('mentor.groups.show', ['event' => $event->id, 'id' => $group->id]) }}" class="text-decoration-none d-block">
            <div class="team-card p-3" style="border-left-color: {{ $group->completed_challenges_count > 0 ? '#00a79d' : '#e0e0e0' }};">

                {{-- Info Utama --}}
                <div class="d-flex align-items-center mb-3">
                    <div class="team-icon me-3">
                        <i class='bx bxs-face-mask'></i>
                    </div>
                    <div>
                        {{-- Nama Group (Hitam Murni) --}}
                        <h6 class="fw-bold mb-0" style="color: #222;">{{ $group->name }}</h6>

                        {{-- Uang $ (Abu-abu Gelap Murni) --}}
                        <small class="fw-bold text-value-dark" style="font-size: 0.8rem;">
                            $ {{ number_format($group->squid_dollar) }}
                        </small>
                    </div>
                    <div class="ms-auto">
                        <i class='bx bx-chevron-right fs-4' style="color: #aaa;"></i>
                    </div>
                </div>

                {{-- Stats Row --}}
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stats-box">
                            {{-- Label MISI SELESAI (Abu-abu Murni) --}}
                            <small class="d-block text-label-gray" style="font-size: 10px; font-weight: 600;">MISI SELESAI</small>
                            {{-- Angka (Hijau) --}}
                            <span class="fw-bold text-squid">{{ $group->completed_challenges_count }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-box">
                            {{-- Label ANGGOTA (Abu-abu Murni) --}}
                            <small class="d-block text-label-gray" style="font-size: 10px; font-weight: 600;">ANGGOTA</small>
                            {{-- Angka (Abu Gelap) --}}
                            <span class="fw-bold text-value-dark">{{ $group->members->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @empty
            <div class="text-center py-5 bg-white rounded-3 shadow-sm mt-3">
                <div class="avatar p-3 mx-auto mb-3" style="background: #f4f4f4; border-radius: 50%;">
                    <i class='bx bx-search-alt fs-1' style="color: #888;"></i>
                </div>
                <h6 class="fw-bold mb-0" style="color: #888;">Belum ada kelompok.</h6>
            </div>
        @endforelse

        <div style="height: 100px;"></div>
    </div>
@endsection
