@extends('admin.layouts.contentNavbarLayout')

@section('title', "Detail Kelompok - $group->name")

@section('styles')
<style>
    :root { --squid-primary: #00a79d; --squid-light: #e0f2f1; }

    /* --- NEW PROFILE HEADER STYLE --- */
    .profile-header-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }

    .profile-cover {
        height: 140px; /* Tinggi cover lebih proporsional */
        background: linear-gradient(120deg, #00a79d, #23d2c3);
        position: relative;
    }

    /* Pattern Dot Halus di Cover */
    .profile-cover::before {
        content: '';
        position: absolute;
        width: 100%; height: 100%;
        background-image: radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.7;
    }

    .profile-body {
        position: relative;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 20px;
    }

    .avatar-container {
        position: absolute;
        top: -60px; /* Membuat avatar naik ke atas cover */
        left: 30px;
        padding: 4px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .group-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        background-color: var(--squid-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--squid-primary);
    }

    .profile-info {
        margin-left: 140px; /* Memberi jarak dari avatar */
        margin-top: -10px; /* Penyesuaian vertikal */
    }

    .profile-name {
        font-size: 1.75rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 0.2rem;
        line-height: 1.2;
    }

    .profile-meta {
        display: flex;
        gap: 15px;
        color: #697a8d;
        font-size: 0.9rem;
        align-items: center;
    }

    /* Saldo Box yang Elegan */
    .wallet-box {
        text-align: right;
        background: #f8f9fa;
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid #eee;
    }
    .wallet-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        display: block;
        margin-bottom: 2px;
    }
    .wallet-value {
        font-size: 1.8rem;
        font-weight: 900;
        color: var(--squid-primary);
        font-family: 'Courier New', monospace;
        line-height: 1;
    }

    /* Transaction & Tabs Styles (Tetap sama) */
    .trx-item { border-left: 4px solid transparent; transition: 0.2s; }
    .trx-in { border-left-color: #71dd37; background: #f0fdf4; }
    .trx-out { border-left-color: #ff3e1d; background: #fff5f5; }

    .nav-pills .nav-link {
        border-radius: 50px; padding: 8px 20px; color: #566a7f; font-weight: 600;
    }
    .nav-pills .nav-link.active {
        background-color: var(--squid-primary); color: white; box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
    }
    .avatar-ring { border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

    /* Responsiveness */
    @media (max-width: 768px) {
        .profile-body { flex-direction: column; align-items: flex-start; text-align: center; padding-top: 60px; }
        .avatar-container { left: 50%; transform: translateX(-50%); top: -55px; }
        .profile-info { margin-left: 0; width: 100%; margin-top: 10px; }
        .profile-meta { justify-content: center; }
        .wallet-box { width: 100%; text-align: center; margin-top: 10px; }
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--squid-primary);">
        <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Detail Tim
    </h4>
    <a href="{{ route('admin.events.groups.index', $event->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

{{-- NEW HEADER PROFILE CARD --}}
<div class="card profile-header-card mb-4">
    <div class="profile-cover"></div>

    <div class="profile-body">

        {{-- Avatar Mengambang --}}
        <div class="avatar-container">
            @if(false) {{-- Ganti logic ini jika grup punya logo/avatar sendiri --}}
                <img src="" class="group-avatar">
            @else
                <div class="group-avatar">
                    <i class="bx bx-group"></i>
                </div>
            @endif
        </div>

        {{-- Info Utama --}}
        <div class="profile-info">
            <h2 class="profile-name">{{ $group->name }}</h2>
            <div class="profile-meta">
                <span class="badge bg-label-primary rounded-pill">
                    <i class="bx bx-user me-1"></i> {{ $group->members->count() }} Anggota
                </span>
                <span class="text-muted">
                    <i class="bx bx-user-voice me-1 text-info"></i> Mentor:
                    <strong>{{ $group->mentor->name ?? 'Belum ada' }}</strong>
                </span>
            </div>
        </div>

        {{-- Saldo Wallet (Pojok Kanan) --}}
        <div class="wallet-box">
            <span class="wallet-label"><i class="bx bx-wallet me-1"></i> Squid Wallet</span>
            <div class="wallet-value">
                ${{ number_format($group->squid_dollar, 0, ',', '.') }}
            </div>
        </div>

    </div>
</div>

<div class="row g-4">

    {{-- KIRI: INFORMASI TIM --}}
    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header border-bottom py-3">
                <h6 class="fw-bold m-0 text-dark"><i class="bx bx-crown me-1 text-warning"></i> Struktur Tim</h6>
            </div>
            <div class="card-body pt-4">

                <div class="d-flex align-items-center mb-3 p-2 rounded bg-lighter border border-dashed">
                    <div class="avatar me-3">
                        @if($group->captain && $group->captain->avatar)
                            <img src="{{ asset($group->captain->avatar) }}" class="rounded-circle avatar-ring">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-warning text-warning fw-bold">C</span>
                        @endif
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem;">Captain</small>
                        <span class="fw-bold text-dark">{{ $group->captain->name ?? 'Belum Ditentukan' }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center p-2 rounded bg-lighter border border-dashed">
                    <div class="avatar me-3">
                         @if($group->cocaptain && $group->cocaptain->avatar)
                            <img src="{{ asset($group->cocaptain->avatar) }}" class="rounded-circle avatar-ring">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-secondary text-secondary fw-bold">Co</span>
                        @endif
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem;">Co-Captain</small>
                        <span class="fw-bold text-dark">{{ $group->cocaptain->name ?? 'Belum Ditentukan' }}</span>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <a href="{{ route('admin.events.groups.edit', ['event' => $event->id, 'group' => $group->id]) }}" class="btn btn-primary shadow-sm" style="background-color: var(--squid-primary); border:none;">
                        <i class="bx bx-edit-alt me-1"></i> Edit & Kelola Anggota
                    </a>
                </div>
            </div>
        </div>

        {{-- STATUS PROGRESS --}}
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom bg-white">
                <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-target-lock me-1 text-danger"></i> Game Progress</h6>
            </div>
            <div class="card-body pt-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted">Challenge Phase</span>
                        <span class="small fw-bold text-primary">{{ $challengeProgress['completed'] }}/{{ $challengeProgress['total'] }} Selesai</span>
                    </div>
                    <div class="progress bg-label-primary" style="height: 8px; border-radius: 10px;">
                        @php $percent = $challengeProgress['total'] > 0 ? ($challengeProgress['completed'] / $challengeProgress['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-primary shadow-sm" role="progressbar" style="width: {{ $percent }}%; border-radius: 10px;"></div>
                    </div>
                </div>

                <div class="p-3 rounded bg-label-secondary d-flex justify-content-between align-items-center">
                    <span class="small fw-bold text-dark">Business Case</span>
                    <span class="badge bg-white text-warning shadow-sm border">{{ $caseStatus }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: TABS (Anggota & Keuangan) --}}
    <div class="col-xl-8 col-lg-7">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-members">
                        <i class="bx bx-user me-1"></i> Anggota Tim
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-finance">
                        <i class="bx bx-money me-1"></i> Riwayat Keuangan
                    </button>
                </li>
            </ul>

            <div class="tab-content shadow-sm border-0 rounded-3 p-0 bg-white">

                {{-- TAB 1: ANGGOTA --}}
                <div class="tab-pane fade show active" id="navs-members" role="tabpanel">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4">Nama</th>
                                    <th class="py-3">Email</th>
                                    <th class="py-3">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($group->members as $member)
                                @php
                                    $u = $member->user;
                                    $roleBadge = 'Anggota';
                                    $badgeColor = 'bg-label-secondary';

                                    if($group->captain_id == $u->id) { $roleBadge = 'Captain'; $badgeColor = 'bg-warning'; }
                                    elseif($group->cocaptain_id == $u->id) { $roleBadge = 'Co-Captain'; $badgeColor = 'bg-info'; }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                @if($u->avatar)
                                                    <img src="{{ asset($u->avatar) }}" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary fw-bold">{{ substr($u->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <span class="fw-bold text-dark">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $u->email }}</td>
                                    <td><span class="badge {{ $badgeColor }} rounded-pill">{{ $roleBadge }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted">Belum ada anggota di kelompok ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: KEUANGAN --}}
                <div class="tab-pane fade" id="navs-finance" role="tabpanel">
                    @if($transactions->isEmpty())
                        <div class="text-center py-5">
                            <div class="avatar avatar-lg bg-label-secondary rounded-circle mx-auto mb-3">
                                <i class="bx bx-ghost fs-1"></i>
                            </div>
                            <h6 class="text-dark mb-1">Belum Ada Transaksi</h6>
                            <p class="text-muted small">Riwayat keuangan kelompok akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($transactions as $trx)
                                @php
                                    // Tentukan arah uang (Masuk/Keluar)
                                    $isIncoming = ($trx->to_type == 'group' && $trx->to_id == $group->id);
                                    $colorClass = $isIncoming ? 'trx-in' : 'trx-out';
                                    $icon = $isIncoming ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt';
                                    $textVariant = $isIncoming ? 'text-success' : 'text-danger';
                                    $sign = $isIncoming ? '+' : '-';
                                @endphp

                                <div class="list-group-item {{ $colorClass }} p-3 border-bottom-0 mb-1 rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center">
                                                <i class="bx {{ $icon }} {{ $textVariant }} fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">
                                                    {{ $trx->type }}
                                                    @if($trx->type == 'INVESTMENT' && $trx->sender)
                                                        <span class="text-muted fw-normal" style="font-size: 0.8rem;">dari {{ $trx->sender->name }}</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted"><i class="bx bx-time-five me-1"></i> {{ $trx->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold fs-5 {{ $textVariant }}">
                                                {{ $sign }} ${{ number_format($trx->amount, 0, ',', '.') }}
                                            </span>
                                            @if($trx->note)
                                                <small class="d-block text-muted fst-italic" style="font-size: 0.75rem;">"{{ Str::limit($trx->note, 30) }}"</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
