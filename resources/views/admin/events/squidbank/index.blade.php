@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Squid Bank - ' . $event->name)

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-dark: #00796b;
        --squid-light: #e0f2f1;
        --squid-gold: #ffab00;
    }

    /* --- 1. STAT CARDS --- */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 167, 157, 0.15);
    }

    /* Variasi Card */
    .card-primary {
        background: linear-gradient(135deg, var(--squid-primary) 0%, var(--squid-dark) 100%);
        color: white;
    }
    .card-outline {
        border: 2px solid var(--squid-light);
    }
    .card-outline .icon-box {
        background: var(--squid-light);
        color: var(--squid-primary);
    }

    /* Dekorasi Icon di Belakang */
    .bg-icon-decoration {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 8rem;
        opacity: 0.1;
        transform: rotate(-15deg);
        pointer-events: none;
    }

    /* --- 2. MODERN TABLE --- */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table-modern thead th {
        border-bottom: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #b0b0b0;
        padding-bottom: 5px;
        font-weight: 700;
    }
    .table-modern tbody tr {
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        border-radius: 12px;
        transition: all 0.2s;
    }
    .table-modern tbody tr:hover {
        transform: scale(1.005);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .table-modern td {
        border: none;
        padding: 15px 20px;
        vertical-align: middle;
    }
    .table-modern td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .table-modern td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

    /* Avatar Group Initials */
    .avatar-initial {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1rem;
        margin-right: 15px;
    }

    /* Badges */
    .badge-modern {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-soft-danger { background: #ffe0db; color: #ff3e1d; }
    .badge-soft-primary { background: #e7f1ff; color: #007bff; }
</style>
@endsection

@section('content')

    {{-- 1. HEADER (STYLE BARU) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #008f85;">
                <i class="bx bxs-bank me-2"></i>Squid Bank
            </h4>
            <span class="text-muted">Event: {{ $event->name }}</span>
        </div>

        {{-- Tombol Kembali --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- 2. KARTU STATISTIK --}}
    <div class="row g-4 mb-5">

        {{-- CARD 1: TOTAL RESERVE (SALDO DIGITAL ADMIN) --}}
        <div class="col-md-4">
            <div class="stat-card card-primary p-4">
                {{-- Hiasan Background --}}
                <i class='bx bxs-briefcase-alt-2 bg-icon-decoration text-white'></i>

                <div class="d-flex align-items-center gap-3 mb-3">
                    {{-- ICON KOPER (BRIEFCASE) --}}
                    <div class="icon-box p-2 rounded-3">
                        <i class='bx bxs-briefcase-alt-2 fs-4 text-white'></i>
                    </div>
                    <span class="text-uppercase fw-bold opacity-75 small">Total Cadangan Bank</span>
                </div>
                <h2 class="mb-0 fw-bold text-white">$ {{ number_format($adminBankReserve) }}</h2>
                <div class="mt-2 text-white opacity-75 small">
                    <i class='bx bx-trending-up me-1'></i> Saldo digital masuk ke Admin
                </div>
            </div>
        </div>

        {{-- CARD 2: ADMIN VAULT (UANG FISIK ADMIN) --}}
        <div class="col-md-4">
            <div class="stat-card card-outline p-4">
                <i class='bx bx-money bg-icon-decoration' style="color: #00a79d;"></i>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box p-2 rounded-3">
                        <i class='bx bx-money fs-4'></i>
                    </div>
                    <span class="text-uppercase fw-bold text-muted small">Brankas Fisik Admin</span>
                </div>
                <h2 class="mb-0 fw-bold" style="color: #00a79d;">$ {{ number_format($adminPhysicalCash) }}</h2>
                <div class="mt-2 text-muted small">
                    <i class='bx bx-trending-down me-1'></i> Stok uang tunai Admin
                </div>
            </div>
        </div>

        {{-- CARD 3: USER CIRCULATION (UANG PESERTA) --}}
        <div class="col-md-4">
            <div class="stat-card bg-white p-4 border shadow-sm">
                <i class='bx bx-group bg-icon-decoration text-secondary'></i>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-2 rounded-3 bg-light">
                        <i class='bx bx-wallet fs-4 text-warning'></i>
                    </div>
                    <span class="text-uppercase fw-bold text-muted small">Uang Beredar (Peserta)</span>
                </div>
                <h2 class="mb-0 fw-bold text-dark">$ {{ number_format($totalCashCirculation) }}</h2>
                <div class="mt-2 text-muted small">
                    <i class='bx bx-info-circle me-1'></i> Dipegang oleh {{ $event->groups->count() }} Tim
                </div>
            </div>
        </div>
    </div>

    {{-- 3. MODERN TRANSACTION HISTORY TABLE --}}
    <div class="d-flex align-items-center justify-content-between mb-3 px-2">
        <h5 class="fw-bold text-dark mb-0"><i class='bx bx-list-ul me-2' style="color: #00a79d;"></i>Mutasi Terakhir</h5>
        <span class="badge bg-label-primary rounded-pill px-3">{{ $transactions->count() }} Transaksi</span>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th class="ps-4">Waktu</th>
                    <th>Detail Transaksi</th>
                    <th>Tipe</th>
                    <th class="text-end pe-4">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    @php
                        $fromGroup = $trx->from_type == 'group' ? \App\Models\Group::find($trx->from_id) : null;
                        $toGroup   = $trx->to_type == 'group' ? \App\Models\Group::find($trx->to_id) : null;

                        $isWithdrawal = $trx->reason === 'BANK_WITHDRAWAL';
                        $isTransfer   = $trx->reason === 'GROUP_TRANSFER';
                    @endphp
                    <tr>
                        {{-- KOLOM 1: WAKTU --}}
                        <td class="ps-4" style="width: 150px;">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $trx->created_at->format('d M') }}</span>
                                <small class="text-muted">{{ $trx->created_at->format('H:i') }}</small>
                            </div>
                        </td>

                        {{-- KOLOM 2: PELAKU --}}
                        <td>
                            <div class="d-flex align-items-center">
                                @if($isWithdrawal && $toGroup)
                                    {{-- Penarikan: Icon Merah --}}
                                    <div class="avatar-initial" style="background: #fff0ee; color: #ff3e1d;">
                                        {{ substr($toGroup->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $toGroup->name }}</span>
                                        <small class="text-muted"><i class='bx bxs-bank me-1 text-secondary'></i> Bank &rarr; Cash</small>
                                    </div>
                                @elseif($fromGroup && $toGroup)
                                    {{-- Transfer: Icon Biru/Hijau --}}
                                    <div class="avatar-initial" style="background: #e0f2f1; color: #00a79d;">
                                        {{ substr($fromGroup->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $fromGroup->name }}</span>
                                        <small class="text-muted"><i class='bx bx-right-arrow-alt me-1'></i> ke {{ $toGroup->name }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </div>
                        </td>

                        {{-- KOLOM 3: TIPE --}}
                        <td>
                            @if($isWithdrawal)
                                <span class="badge-modern badge-soft-danger">
                                    <i class='bx bx-money-withdraw me-1'></i> Tarik Tunai
                                </span>
                            @elseif($isTransfer)
                                <span class="badge-modern badge-soft-primary">
                                    <i class='bx bx-transfer-alt me-1'></i> Transfer
                                </span>
                            @else
                                <span class="badge-modern bg-label-secondary">{{ $trx->reason }}</span>
                            @endif
                        </td>

                        {{-- KOLOM 4: NOMINAL --}}
                        <td class="text-end pe-4">
                            <h6 class="mb-0 fw-bold {{ $isWithdrawal ? 'text-danger' : 'text-primary' }}">
                                {{ $isWithdrawal ? '-' : '' }} $ {{ number_format($trx->amount) }}
                            </h6>
                            <small class="text-muted fst-italic" style="font-size: 0.7rem;">
                                {{ Str::limit($trx->description, 25) }}
                            </small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 border-0 bg-transparent shadow-none">
                            <div class="d-flex flex-column align-items-center opacity-50">
                                <i class='bx bx-hdd mb-2' style="font-size: 3rem;"></i>
                                <h6 class="text-muted">Belum ada data transaksi.</h6>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
