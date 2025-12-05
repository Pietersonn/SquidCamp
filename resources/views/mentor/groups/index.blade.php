@extends('mentor.layouts.master')
@section('title', 'My Teams')

@section('styles')
<style>
    /* --- 1. HEADER DENGAN DEKORASI --- */
    .header-teams {
        background: linear-gradient(135deg, #00a79d 0%, #008f87 100%);
        padding: 40px 25px 60px 25px; /* Padding bawah lebih besar untuk efek floating card */
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 167, 157, 0.2);
    }

    /* Dekorasi Simbol (Background) */
    .squid-deco {
        position: absolute;
        color: white;
        opacity: 0.1;
        z-index: 0;
    }

    /* --- 2. LAYOUT CONTAINER --- */
    .teams-container {
        padding: 0 20px;
        margin-top: -30px; /* Kartu "Menusuk" ke Header sedikit */
        position: relative;
        z-index: 10;
    }

    /* --- 3. KARTU TEAM (LEBIH CLEAN) --- */
    .team-card {
        background: white;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.03); /* Border sangat halus */
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .team-card:active { transform: scale(0.97); }

    /* Garis Indikator Status di Kiri */
    .status-line {
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 6px;
    }

    /* Ikon Kelompok */
    .team-icon {
        width: 48px; height: 48px;
        background: rgba(0, 167, 157, 0.08);
        color: #00a79d;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
    }

    /* --- 4. KOMPONEN MIKRO --- */

    /* Badge Uang (Tampilan Digital) */
    .badge-money {
        background: #f4f4f4;
        color: #333;
        font-family: 'Courier New', Courier, monospace; /* Font kesan digital */
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        letter-spacing: -0.5px;
        border: 1px solid #e0e0e0;
    }

    /* Stats Box (Grid) */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #f0f0f0;
    }

    .stat-item {
        text-align: center;
    }
    .stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .stat-value {
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1;
    }

    /* Member Stack (Tumpukan Avatar) */
    .avatar-stack {
        display: flex;
        padding-left: 10px;
    }
    .avatar-stack .avatar-circle {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        background: #eee;
        color: #777;
        font-size: 0.65rem;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        margin-left: -10px; /* Efek tumpuk */
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .avatar-circle.more {
        background: #333;
        color: white;
    }

</style>
@endsection

@section('content')

    {{-- HEADER --}}
    <div class="header-teams">
        <div class="position-relative z-1">
            <h3 class="fw-bold text-white mb-1">My Teams</h3>
            <p class="text-white opacity-80 small mb-0">Monitor {{ $groups->count() }} kelompok aktif.</p>
        </div>

        {{-- Dekorasi Simbol Squid Game (Floating Shapes) --}}
        <i class='bx bx-radio-circle squid-deco' style="font-size: 6rem; top: -10px; right: -10px;"></i>
        <i class='bx bx-bounding-box squid-deco' style="font-size: 4rem; bottom: 10px; right: 60px; opacity: 0.05;"></i>
        <i class='bx bx-change squid-deco' style="font-size: 5rem; top: 40px; left: -20px; transform: rotate(45deg);"></i>
    </div>

    {{-- CONTENT --}}
    <div class="teams-container">
        @forelse($groups as $group)
        <a href="{{ route('mentor.groups.show', $group->id) }}" class="text-decoration-none d-block">
            <div class="team-card p-3">

                {{-- Indikator Status Warna (Kiri) --}}
                <div class="status-line" style="background: {{ $group->completed_challenges_count > 0 ? '#00a79d' : '#e0e0e0' }};"></div>

                <div class="ps-2"> {{-- Padding left agar tidak nabrak garis status --}}

                    {{-- HEADER KARTU --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="team-icon me-3">
                                <i class='bx bxs-group'></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">{{ $group->name }}</h6>
                                {{-- Avatar Stack (Wajah Anggota) --}}
                                <div class="avatar-stack">
                                    @foreach($group->members->take(4) as $m)
                                        <div class="avatar-circle" style="background: #f4f6f8; color: #555;">
                                            {{ substr($m->user->name, 0, 1) }}
                                        </div>
                                    @endforeach
                                    @if($group->members->count() > 4)
                                        <div class="avatar-circle more">
                                            +{{ $group->members->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Panah --}}
                        <div class="text-end">
                            <i class='bx bx-right-arrow-alt fs-3' style="color: #ccc;"></i>
                        </div>
                    </div>

                    {{-- BAGIAN STATISTIK (Modern Grid) --}}
                    <div class="stats-grid">
                        {{-- Saldo (Tampil beda) --}}
                        <div class="stat-item border-end">
                            <div class="stat-label">TABUNGAN</div>
                            <div class="badge-money d-inline-block">
                                SQ$ {{ number_format($group->squid_dollar) }}
                            </div>
                        </div>

                        {{-- Misi Selesai --}}
                        <div class="stat-item">
                            <div class="stat-label">PROGRESS MISI</div>
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <i class='bx bxs-check-circle' style="color: #00a79d; font-size: 1rem;"></i>
                                <span class="stat-value" style="color: #333;">{{ $group->completed_challenges_count }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </a>
        @empty
            <div class="text-center py-5 bg-white rounded-3 shadow-sm border border-dashed mt-4">
                <div class="avatar p-3 mx-auto mb-3" style="background: #f9f9f9; border-radius: 50%;">
                    <i class='bx bx-ghost fs-1' style="color: #bbb;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: #555;">Belum ada tim.</h6>
                <small class="text-muted">Kelompok belum dibentuk.</small>
            </div>
        @endforelse

        <div style="height: 100px;"></div>
    </div>
@endsection
