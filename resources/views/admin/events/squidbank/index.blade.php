@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Squid Bank - ' . $event->name)

@section('styles')
<style>
    /* Gradient Card untuk Total */
    .card-reserve {
        background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
        color: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 167, 157, 0.2);
    }
    .card-cash {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        color: #333;
    }

    /* Tabel Styling */
    .table-responsive {
        border-radius: 12px;
    }
    .avatar-initial {
        font-weight: bold;
    }

    /* Transaction Badges */
    .badge-trx {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-withdraw { background: #ffe0db; color: #ff3e1d; }
    .badge-deposit { background: #e8fadf; color: #71dd37; }
</style>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Header & Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #00a79d;">
                <span class="text-muted fw-light">Event / {{ $event->name }} /</span> Squid Bank
            </h4>
            <small class="text-muted">Pantau pergerakan uang (squid_dollar) di event ini.</small>
        </div>
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali ke Event
        </a>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="row g-4 mb-4">
        {{-- KARTU 1: TOTAL RESERVE (BANK) --}}
        <div class="col-md-6">
            <div class="card card-reserve h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-white text-uppercase opacity-75 fw-bold mb-2">Total Cadangan Bank</h6>
                        <h2 class="text-white display-5 fw-bold mb-0">$ {{ number_format($totalBankReserve) }}</h2>
                        <small class="text-white opacity-75 mt-1 d-block"><i class='bx bxs-lock-alt me-1'></i> Uang aman di sistem</small>
                    </div>
                    <div class="p-3 bg-white bg-opacity-25 rounded-3">
                        <i class='bx bxs-bank fs-1 text-white'></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU 2: MONEY CIRCULATION (CASH) --}}
        <div class="col-md-6">
            <div class="card card-cash h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-2">Uang Beredar (Cash)</h6>
                        <h2 class="text-dark display-5 fw-bold mb-0">$ {{ number_format($totalCashCirculation) }}</h2>
                        <small class="text-muted mt-1 d-block"><i class='bx bx-wallet me-1'></i> Uang di tangan peserta</small>
                    </div>
                    <div class="p-3 bg-light rounded-3">
                        <i class='bx bx-money fs-1 text-success'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL MUTASI --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 fw-bold"><i class='bx bx-list-ul me-2 text-primary'></i>Mutasi Rekening</h5>
            <span class="badge bg-label-primary">{{ $transactions->count() }} Transaksi</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Kelompok</th>
                        <th>Tipe Transaksi</th>
                        <th class="text-end">Nominal</th>
                        <th class="pe-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($transactions as $trx)
                        @php
                            $isWithdrawal = $trx->from_type === 'bank';
                            $groupId = $isWithdrawal ? $trx->to_id : $trx->from_id;
                            $group = \App\Models\Group::find($groupId);
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-bold text-dark">{{ $trx->created_at->format('d M Y') }}</span>
                                <small class="text-muted">{{ $trx->created_at->format('H:i') }} WITA</small>
                            </td>
                            <td>
                                @if($group)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                {{ substr($group->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $group->name }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-label-secondary">Unknown Group</span>
                                @endif
                            </td>
                            <td>
                                @if($isWithdrawal)
                                    <span class="badge-trx badge-withdraw">
                                        <i class='bx bx-up-arrow-alt'></i> Penarikan
                                    </span>
                                @else
                                    <span class="badge-trx badge-deposit">
                                        <i class='bx bx-down-arrow-alt'></i> Setoran
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="fw-bold fs-6 {{ $isWithdrawal ? 'text-danger' : 'text-success' }}">
                                    {{ $isWithdrawal ? '-' : '+' }} $ {{ number_format($trx->amount) }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <span class="text-muted small fst-italic">{{ $trx->description }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                    <i class='bx bx-hdd mb-3' style="font-size: 4rem;"></i>
                                    <h5>Belum ada data transaksi bank.</h5>
                                    <p class="small">Data penarikan atau setoran akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination jika diperlukan nanti --}}
        {{-- <div class="card-footer bg-white border-top">
            {{ $transactions->links() }}
        </div> --}}
    </div>
</div>
@endsection
