@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Groups - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-gold: #ffab00; /* Warna Kuning Emas untuk Bank */
    }

    .group-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .group-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.15);
    }

    /* Header Gradient */
    .group-header {
        background: linear-gradient(135deg, #00a79d 0%, #48c6ef 100%);
        padding: 20px;
        color: white;
        position: relative;
    }

    .group-icon-float {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: 0.15;
        color: white;
    }

    /* Info Label */
    .info-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }

    /* Warna Khusus */
    .text-bank { color: var(--squid-gold) !important; }
    .text-cash { color: #28a745 !important; } /* Hijau Cash */

    /* --- CAPTAIN BOX STYLING --- */
    .captain-box {
        margin-top: 15px;
        padding: 12px 15px;
        background-color: #fff;
        border: 1px dashed #d9dee3;
        border-radius: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .group-card:hover .captain-box {
        border-color: var(--squid-gold);
        border-style: solid;
        background-color: #fffdf5;
        transform: translateX(5px);
    }

    .captain-avatar-ring {
        border: 2px solid var(--squid-gold);
        padding: 2px;
        border-radius: 50%;
    }

    /* Tombol */
    .btn-squid-outline {
        color: var(--squid-primary);
        border: 1px solid var(--squid-primary);
        font-weight: 600;
    }
    .btn-squid-outline:hover {
        background-color: var(--squid-primary);
        color: white;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #008f85;"><i class="bx bx-group me-2"></i>Groups</h4>
        <span class="text-muted">Event: {{ $event->name }}</span>
    </div>

    {{-- Tombol Kembali & Buat Kelompok --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.events.groups.create', $event->id) }}" class="btn btn-primary" style="background-color: #00a79d; border:none;">
            <i class="bx bx-plus me-1"></i> Buat Kelompok
        </a>
    </div>
</div>

<div class="row g-4">
  @forelse ($groups as $group)
    <div class="col-md-6 col-lg-4">
      <div class="group-card">

        {{-- Header Gradient --}}
        <div class="group-header">
            <i class="bx bx-group group-icon-float"></i>
            <h5 class="text-white mb-1 text-truncate fw-bold" title="{{ $group->name }}">{{ $group->name }}</h5>
            <span class="badge bg-white text-success rounded-pill fw-bold">
                <i class="bx bx-user me-1"></i> {{ $group->members_count }} Anggota
            </span>
        </div>

        <div class="card-body d-flex flex-column p-4">

            {{-- Baris 1: Keuangan (Cash & Bank) --}}
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="info-label text-muted mb-2">Total Keuangan</span>

                    {{-- Cash (Hijau) --}}
                    <div class="d-flex align-items-center mb-1 text-cash" title="Uang Cash">
                        <i class="bx bx-wallet me-2 fs-5"></i>
                        <span class="fw-bold fs-6">${{ number_format($group->squid_dollar, 0, ',', '.') }}</span>
                        <small class="text-muted ms-1" style="font-size: 0.65rem;">(CASH)</small>
                    </div>

                    {{-- Bank (Kuning Emas) --}}
                    <div class="d-flex align-items-center text-bank" title="Saldo Bank">
                        <i class="bx bxs-bank me-2 fs-5"></i>
                        <span class="fw-bold fs-6">${{ number_format($group->bank_balance, 0, ',', '.') }}</span>
                        <small class="text-muted ms-1" style="font-size: 0.65rem;">(BANK)</small>
                    </div>
                </div>

                {{-- Mentor --}}
                <div class="text-end">
                    <span class="info-label text-muted">Mentor</span>
                    @if($group->mentor)
                        <span class="badge bg-label-info">{{ $group->mentor->name }}</span>
                    @else
                        <span class="badge bg-label-secondary">-</span>
                    @endif
                </div>
            </div>

            {{-- Baris 2: CAPTAIN --}}
            <div class="captain-box">
                <div class="me-3">
                    @if($group->captain && $group->captain->avatar)
                        <img src="{{ asset($group->captain->avatar) }}" class="rounded-circle captain-avatar-ring" width="40" height="40">
                    @else
                        <div class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-warning text-white captain-avatar-ring">
                                <i class="bx bxs-crown"></i>
                            </span>
                        </div>
                    @endif
                </div>

                <div class="overflow-hidden">
                    <span class="info-label text-warning mb-0">Team Captain</span>
                    @if($group->captain)
                        <h6 class="mb-0 text-dark text-truncate fw-bold">{{ $group->captain->name }}</h6>
                    @else
                        <small class="text-muted fst-italic">- Belum ada -</small>
                    @endif
                </div>
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="card-footer border-top p-3 d-flex justify-content-between bg-white mt-auto">
            <a href="{{ route('admin.events.groups.show', ['event' => $event->id, 'group' => $group->id]) }}" class="btn btn-sm btn-squid-outline px-3 rounded-pill">
                <i class="bx bx-show me-1"></i> Detail
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.events.groups.edit', ['event' => $event->id, 'group' => $group->id]) }}" class="btn btn-sm btn-icon btn-label-secondary rounded-circle">
                    <i class="bx bx-edit-alt"></i>
                </a>
                <form action="{{ route('admin.events.groups.destroy', ['event' => $event->id, 'group' => $group->id]) }}" method="POST" onsubmit="return confirm('Hapus kelompok ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger rounded-circle">
                        <i class="bx bx-trash"></i>
                    </button>
                </form>
            </div>
        </div>

      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5">
        <div class="badge p-4 rounded-circle mb-3" style="background-color: #e0f2f1; color: #00a79d;">
            <i class="bx bx-group fs-1"></i>
        </div>
        <h4 class="text-muted">Belum ada Kelompok</h4>
        <p class="text-muted mb-4">Buat kelompok untuk memulai kompetisi.</p>
        <a href="{{ route('admin.events.groups.create', $event->id) }}" class="btn btn-primary" style="background-color: #00a79d; border:none;">
            <i class="bx bx-plus"></i> Buat Kelompok
        </a>
    </div>
  @endforelse
</div>

@endsection
