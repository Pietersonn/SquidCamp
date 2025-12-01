@extends('main.layouts.mobileMaster')

@section('title', 'Riwayat Transaksi')

@section('styles')
<style>
    .history-header {
        background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
        padding: 30px 20px 40px 20px;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 167, 157, 0.2);
    }

    .trx-item {
        background: white;
        border-radius: 16px;
        padding: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        cursor: pointer;
        transition: transform 0.1s;
    }
    .trx-item:active { transform: scale(0.98); }

    .trx-icon {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin-right: 15px;
        flex-shrink: 0;
    }
    .icon-in { background: #e8fadf; color: #71dd37; } /* Hijau (Masuk) */
    .icon-out { background: #ffe0db; color: #ff3e1d; } /* Merah (Keluar) */

    .trx-info { flex-grow: 1; overflow: hidden; }
    .trx-title { font-weight: 700; color: #333; font-size: 0.9rem; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .trx-date { font-size: 0.7rem; color: #999; }

    .trx-amount { font-weight: 800; font-size: 0.95rem; white-space: nowrap; }
    .text-in { color: #71dd37; }
    .text-out { color: #ff3e1d; }

    /* --- RECEIPT STYLE (Copy dari Dashboard) --- */
    .receipt-paper {
        background: #fff; padding: 20px; border-radius: 10px; position: relative;
        font-family: 'Courier New', Courier, monospace;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1); border-top: 5px solid #00a79d;
    }
    .receipt-paper::after {
        content: ""; position: absolute; bottom: -5px; left: 0; width: 100%; height: 10px;
        background: radial-gradient(circle, transparent, transparent 50%, #fff 50%, #fff 100%) -7px -8px / 16px 16px repeat-x;
    }
    .receipt-title { text-align: center; font-weight: bold; text-transform: uppercase; border-bottom: 2px dashed #ddd; padding-bottom: 10px; margin-bottom: 10px; color: #333; }
    .receipt-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.85rem; color: #555; }
    .receipt-total { border-top: 2px dashed #ddd; padding-top: 10px; margin-top: 10px; font-weight: bold; font-size: 1.1rem; color: #000; }
    .receipt-footer { text-align: center; margin-top: 15px; font-size: 0.7rem; color: #999; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="history-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Riwayat</h4>
            <p class="opacity-75 small mb-0">Semua transaksi tim</p>
        </div>
        <a href="{{ route('main.dashboard') }}" class="btn btn-sm btn-light bg-opacity-25 text-white border-0">
            <i class='bx bx-home'></i>
        </a>
    </div>
</div>

{{-- List Transaksi --}}
<div class="container px-3 pb-5" style="margin-top: -30px;">
    @forelse ($transactions as $trx)
        @php
            // Logic menentukan Masuk/Keluar
            // Jika 'to_id' adalah grup kita => Uang MASUK (Kecuali withdraw, withdraw itu bank->grup, jadi masuk cash)
            // Jika 'from_id' adalah grup kita => Uang KELUAR

            $isIncoming = false;
            if ($trx->reason == 'BANK_WITHDRAWAL') {
                $isIncoming = true; // Dari Bank ke Cash (Masuk Dompet)
            } elseif ($trx->to_type == 'group' && $trx->to_id == $group->id) {
                $isIncoming = true; // Transferan dari orang lain
            }

            // Siapkan data untuk Modal (JSON)
            $modalData = [
                'id' => 'TRX-' . str_pad($trx->id, 6, '0', STR_PAD_LEFT),
                'date' => $trx->created_at->format('d M Y, H:i'),
                'type' => $trx->reason == 'BANK_WITHDRAWAL' ? 'TARIK TUNAI' : 'TRANSFER',
                'amount' => number_format($trx->amount),
                'desc' => $trx->description,
                'status' => $isIncoming ? 'UANG MASUK' : 'UANG KELUAR'
            ];
        @endphp

        <div class="trx-item" onclick='showReceipt(@json($modalData))'>
            {{-- Icon --}}
            <div class="trx-icon {{ $isIncoming ? 'icon-in' : 'icon-out' }}">
                @if($trx->reason == 'BANK_WITHDRAWAL')
                    <i class='bx bx-money-withdraw'></i>
                @elseif($trx->reason == 'GROUP_TRANSFER')
                    <i class='bx bx-transfer'></i>
                @else
                    <i class='bx bx-dollar-circle'></i>
                @endif
            </div>

            {{-- Info --}}
            <div class="trx-info">
                <div class="trx-title">{{ $trx->description }}</div>
                <div class="trx-date">{{ $trx->created_at->diffForHumans() }}</div>
            </div>

            {{-- Amount --}}
            <div class="trx-amount {{ $isIncoming ? 'text-in' : 'text-out' }}">
                {{ $isIncoming ? '+' : '-' }} ${{ number_format($trx->amount/1000, 0) }}K
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class='bx bx-notepad fs-1 mb-2 opacity-50'></i>
            <p>Belum ada riwayat transaksi.</p>
        </div>
    @endforelse
</div>

{{-- MODAL STRUK (Dinamis) --}}
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-transparent shadow-none border-0">
            <div class="receipt-paper">
                <div class="receipt-title">
                    <i class='bx bxs-receipt fs-1 d-block mb-2 text-primary'></i>
                    BUKTI TRANSAKSI
                </div>

                <div class="receipt-row">
                    <span>Tanggal</span>
                    <span id="rec-date">-</span>
                </div>
                <div class="receipt-row">
                    <span>ID Transaksi</span>
                    <span id="rec-id">-</span>
                </div>
                <div class="receipt-row">
                    <span>Jenis</span>
                    <span id="rec-type">-</span>
                </div>
                <div class="receipt-row">
                    <span>Keterangan</span>
                    <span id="rec-desc" class="text-end" style="max-width: 120px;">-</span>
                </div>

                <div class="receipt-total receipt-row text-dark">
                    <span>NOMINAL</span>
                    <span>$ <span id="rec-amount">0</span></span>
                </div>

                <div class="receipt-footer">
                    <p class="mb-1 fw-bold" id="rec-status">-</p>
                    <p>SQUID BANK SYSTEM</p>
                    <button type="button" class="btn btn-dark btn-sm w-100 mt-2 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showReceipt(data) {
        // Isi data modal
        document.getElementById('rec-id').innerText = data.id;
        document.getElementById('rec-date').innerText = data.date;
        document.getElementById('rec-type').innerText = data.type;
        document.getElementById('rec-desc').innerText = data.desc;
        document.getElementById('rec-amount').innerText = data.amount;
        document.getElementById('rec-status').innerText = data.status;

        // Tampilkan modal
        var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        receiptModal.show();
    }
</script>
@endpush
