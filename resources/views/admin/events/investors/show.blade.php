@extends('admin.layouts.contentNavbarLayout')

@section('title', "Portfolio - " . $investor->user->name)

@section('styles')
<style>
    :root { --squid-primary: #00a79d; }

    /* Profile Card */
    .profile-header {
        height: 120px;
        background: linear-gradient(135deg, #00a79d 0%, #48c6ef 100%);
        border-radius: 16px 16px 0 0;
    }
    .profile-avatar {
        width: 90px; height: 90px;
        border-radius: 50%; border: 4px solid #fff;
        margin-top: -45px;
        background: #fff; object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Stats Card */
    .stat-card {
        background: #fff; border-radius: 12px; padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex; align-items: center; height: 100%;
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-3px); border: 1px solid var(--squid-primary); }

    /* History List */
    .trx-item {
        transition: 0.2s; border-left: 4px solid transparent;
    }
    .trx-item:hover {
        background-color: #f8f9fa; border-left-color: var(--squid-primary);
    }
    .btn-squid { background-color: var(--squid-primary); color: #fff; border: none; }
    .btn-squid:hover { background-color: #008f85; color: #fff; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--squid-primary);">
        <span class="text-muted fw-light">Investor /</span> Portfolio
    </h4>
    <a href="{{ route('admin.events.investors.index', $event->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    {{-- LEFT: Profile & Saldo --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="profile-header"></div>
            <div class="card-body text-center pt-0">
                @if($investor->user->avatar)
                    <img src="{{ asset($investor->user->avatar) }}" class="profile-avatar mb-3">
                @else
                    <div class="profile-avatar d-flex align-items-center justify-content-center mx-auto text-primary fs-2 fw-bold bg-label-primary">
                        {{ substr($investor->user->name, 0, 1) }}
                    </div>
                @endif

                <h5 class="fw-bold text-dark mb-1">{{ $investor->user->name }}</h5>
                <p class="text-muted small mb-3">{{ $investor->user->email }}</p>
                <span class="badge bg-label-dark">Investor</span>

                <hr class="my-4">

                <div class="text-start bg-label-secondary p-3 rounded">
                    <small class="text-uppercase text-muted fw-bold">Sisa Wallet</small>
                    <h2 class="mb-0 text-primary fw-bold">${{ number_format($investor->investment_balance, 0, ',', '.') }}</h2>
                </div>

                <a href="{{ route('admin.events.investors.edit', ['event' => $event->id, 'investor' => $investor->id]) }}" class="btn btn-squid w-100 mt-3 shadow-sm">
                    <i class="bx bx-edit-alt me-1"></i> Top Up / Edit Saldo
                </a>
            </div>
        </div>
    </div>

    {{-- RIGHT: Stats & History --}}
    <div class="col-md-8">

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="stat-card">
                    <div class="avatar avatar-md bg-label-success rounded me-3">
                        <i class="bx bx-money fs-3 p-2"></i>
                    </div>
                    <div>
                        <small class="d-block text-muted fw-bold">Total Disalurkan</small>
                        <h4 class="mb-0 fw-bold text-dark">${{ number_format($totalInvested, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="stat-card">
                    <div class="avatar avatar-md bg-label-warning rounded me-3">
                        <i class="bx bx-group fs-3 p-2"></i>
                    </div>
                    <div>
                        <small class="d-block text-muted fw-bold">Kelompok Didanai</small>
                        <h4 class="mb-0 fw-bold text-dark">{{ $groupsFundedCount }} Tim</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bx bx-history me-2"></i>Riwayat Investasi</h5>
            </div>

            @if($investments->isEmpty())
                <div class="text-center py-5">
                    <i class="bx bx-ghost fs-1 text-muted mb-2"></i>
                    <p class="text-muted">Belum ada investasi yang dilakukan ke kelompok manapun.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($investments as $trx)
                        <div class="list-group-item trx-item p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-primary text-white">
                                            <i class="bx bx-group"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $trx->group->name ?? 'Kelompok Dihapus' }}</h6>
                                        <small class="text-muted">{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 fw-bold text-success">+ ${{ number_format($trx->amount, 0, ',', '.') }}</h6>
                                    @if($trx->note)
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">"{{ Str::limit($trx->note, 30) }}"</small>
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
@endsection
