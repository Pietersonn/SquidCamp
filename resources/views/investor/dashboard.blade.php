@extends('investor.layouts.master')

@section('title', 'Market Investasi')

@section('styles')
<style>
    /* --- HEADER SECTION --- */
    .investor-header {
        background: white;
        padding: 20px 25px 85px 25px;
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.03);
        position: relative;
        z-index: 1;
    }

    /* --- CREDIT CARD STYLE BALANCE --- */
    .balance-card {
        background: linear-gradient(135deg, #00a79d 0%, #00695c 100%);
        border-radius: 20px;
        padding: 25px;
        margin: -70px 20px 25px 20px;
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.35);
        position: relative;
        z-index: 10;
        color: white;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-decoration {
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .card-decoration-2 {
        position: absolute;
        bottom: -30px; left: -30px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .balance-label {
        font-size: 0.75rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .balance-amount {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0;
        line-height: 1;
    }

    .card-chip {
        width: 35px; height: 25px;
        background: linear-gradient(135deg, #ffdb76 0%, #d4af37 100%);
        border-radius: 6px;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    /* --- INVESTMENT MARKET GRID --- */
    .market-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding-bottom: 20px;
    }

    .invest-card {
        background: white;
        border-radius: 18px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f0;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .invest-card:active { transform: scale(0.96); }

    .group-avatar {
        width: 45px; height: 45px;
        background: #e0f2f1;
        color: #00a79d;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
        margin-bottom: 10px;
        box-shadow: 0 4px 8px rgba(0, 167, 157, 0.15);
    }

    .group-name {
        font-weight: 700;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 12px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.6em;
    }

    /* --- TABS --- */
    .nav-pills { margin-bottom: 20px; }
    .nav-pills .nav-link {
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 10px 20px;
        color: #888;
        background: transparent;
        transition: all 0.2s;
    }
    .nav-pills .nav-link.active {
        background-color: #e0f2f1;
        color: #00a79d;
    }
</style>
@endsection

@section('content')

    {{-- 1. HEADER AREA --}}
    <div class="investor-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted fw-bold d-block mb-1">Welcome back,</small>
                <h5 class="fw-bold text-dark m-0">{{ Auth::user()->name }}</h5>
            </div>

            <a href="{{ route('investor.select-event') }}" class="btn btn-sm btn-label-secondary rounded-pill fw-bold">
                <i class='bx bx-transfer-alt me-1'></i> Ganti Event
            </a>
        </div>
    </div>

    {{-- 2. CREDIT CARD STYLE BALANCE --}}
    <div class="balance-card">
        <div class="card-decoration"></div>
        <div class="card-decoration-2"></div>

        <div class="d-flex justify-content-between align-items-start">
            <div class="card-chip"></div>
            <i class='bx bx-wifi fs-4 opacity-75'></i>
        </div>

        <div>
            <div class="balance-label">SISA SALDO ANDA</div>
            {{-- PERBAIKAN: Menggunakan investment_balance --}}
            <div class="balance-amount">$ {{ number_format($investorData->investment_balance ?? 0) }}</div>
        </div>
    </div>

    {{-- 3. MAIN CONTENT AREA --}}
    <div class="container px-3">

        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-market">
                    Pasar Investasi
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-history">
                    Riwayat
                </button>
            </li>
        </ul>

        <div class="tab-content p-0 bg-transparent shadow-none">

            {{-- TAB 1: MARKET (GRID) --}}
            <div class="tab-pane fade show active" id="tab-market" role="tabpanel">

                <div class="market-grid">
                    @forelse($groups as $group)
                        <div class="invest-card" onclick="openInvestModal('{{ $group->id }}', '{{ $group->name }}')">

                            <div class="group-avatar">
                                {{ substr($group->name, 0, 1) }}
                            </div>

                            <div class="group-name">
                                {{ $group->name }}
                            </div>

                            <button class="btn btn-sm btn-squid w-100 rounded-pill fw-bold">
                                Investasi
                            </button>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-5" style="grid-column: span 2;">
                            <i class='bx bx-ghost fs-1 text-muted mb-2'></i>
                            <p class="text-muted small">Belum ada kelompok di event ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TAB 2: RIWAYAT --}}
            <div class="tab-pane fade" id="tab-history" role="tabpanel">
                <div class="d-flex flex-column gap-2">
                    @forelse($transactions as $trx)
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-label-danger p-2 rounded-circle me-3 text-danger">
                                        <i class='bx bx-trending-down fs-4'></i>
                                    </div>
                                    <div style="line-height: 1.2;">
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">Dana Keluar</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">
                                            To: {{ $trx->toGroup?->name ?? 'Unknown' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="fw-bold text-danger">-${{ number_format($trx->amount) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-label-secondary rounded-circle d-inline-flex p-3 mb-3">
                                <i class='bx bx-notepad fs-1 text-secondary'></i>
                            </div>
                            <p class="text-muted small">Belum ada riwayat investasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL INVESTASI --}}
    <div class="modal fade" id="globalInvestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="bg-label-primary p-3 rounded-circle mb-2">
                        <i class='bx bx-money-withdraw fs-1 text-primary'></i>
                    </div>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <h5 class="fw-bold text-dark mb-1">Suntik Dana</h5>
                    <p class="text-muted small mb-4">Target: <strong id="modalGroupName" class="text-primary">Group</strong></p>

                    <form action="{{ route('investor.invest', $event->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="group_id" id="modalGroupId">

                        <div class="form-floating mb-3">
                            {{-- PERBAIKAN: Max value menggunakan investment_balance --}}
                            <input type="number" name="amount" class="form-control text-center fw-bold fs-3 text-squid border-0 bg-light"
                                   id="investAmount" placeholder="0" min="1" max="{{ $investorData->investment_balance ?? 0 }}" required>
                            <label class="text-center w-100">Nominal ($)</label>
                        </div>

                        <div class="d-flex justify-content-between small text-muted mb-4 px-2">
                            <span>Sisa Saldo:</span>
                            {{-- PERBAIKAN: Menampilkan investment_balance --}}
                            <span class="fw-bold text-dark">${{ number_format($investorData->investment_balance ?? 0) }}</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-squid fw-bold rounded-pill py-2">KIRIM DANA</button>
                            <button type="button" class="btn btn-light fw-bold rounded-pill text-muted" data-bs-dismiss="modal">BATAL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 60px;"></div>

@endsection

@push('scripts')
<script>
    function openInvestModal(id, name) {
        document.getElementById('modalGroupId').value = id;
        document.getElementById('modalGroupName').innerText = name;
        document.getElementById('investAmount').value = '';

        var myModal = new bootstrap.Modal(document.getElementById('globalInvestModal'));
        myModal.show();
    }
</script>
@endpush
