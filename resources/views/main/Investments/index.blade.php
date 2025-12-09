@extends('main.layouts.mobileMaster')

@section('title', 'Squid Show')

@section('styles')
<style>
    /* --- RED THEME HEADER --- */
    .invest-header {
        background: linear-gradient(135deg, #ff3e1d 0%, #c5290c 100%);
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        padding: 40px 25px 120px 25px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(255, 62, 29, 0.4);
    }

    /* --- FLOATING TIMER CARD (RED VARIANT) --- */
    .timer-card-red {
        background: white;
        border-radius: 20px;
        padding: 15px 25px;
        margin: -85px 20px 25px 20px;
        box-shadow: 0 15px 40px rgba(255, 62, 29, 0.15);
        position: relative;
        z-index: 10;
        text-align: center;
        border: 2px solid #ffe5e0;
    }

    .timer-label-red {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: #c5290c;
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    }

    .timer-digits-red {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 900;
        color: #ff3e1d;
        font-size: 2.5rem;
        line-height: 1;
        letter-spacing: -1px;
    }

    /* --- MOTIVATION CARD (PENYEMANGAT) --- */
    .motivation-card {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        position: relative;
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        border: 1px solid rgba(255, 62, 29, 0.1);
        overflow: hidden;
        /* Aksen Gradasi Halus di Background */
        background: linear-gradient(145deg, #ffffff 0%, #fff5f5 100%);
    }

    /* Garis Aksen di Kiri */
    .motivation-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 6px;
        background: linear-gradient(to bottom, #ff9f43, #ff3e1d);
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
    }

    .motivation-icon-bg {
        position: absolute;
        top: -10px; right: -10px;
        font-size: 5rem;
        color: #ff3e1d;
        opacity: 0.05;
        transform: rotate(15deg);
    }

    .motivation-title {
        font-weight: 800;
        color: #333;
        font-size: 1rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .motivation-text {
        font-style: italic;
        color: #555;
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* --- INVESTMENT CARD --- */
    .investment-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #ffe5e0;
        border-left: 5px solid #ff3e1d;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        transition: transform 0.2s;
    }

    .investment-card:active {
        transform: scale(0.98);
    }

    .icon-box {
        width: 50px; height: 50px;
        background: #ffe5e0;
        color: #ff3e1d;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .amount-text {
        font-size: 1.2rem;
        font-weight: 800;
        color: #ff3e1d;
    }
</style>
@endsection

@section('content')

    {{-- 1. HEADER MERAH --}}
    <div class="invest-header">
        <h2 class="fw-bold mb-0 text-white">SquidShow</h2>
        <p class="text-white opacity-75 small mb-0 fw-bold">Sampaikan ide kamu untuk dapat dana dari investor</p>

        {{-- Background Icons --}}
        <i class='bx bx-radar text-white position-absolute' style="font-size: 8rem; top: -10px; left: -20px; opacity: 0.15; transform: rotate(-15deg);"></i>
        <i class='bx bx-money text-white position-absolute' style="font-size: 6rem; bottom: 20px; right: -20px; opacity: 0.15; transform: rotate(15deg);"></i>
    </div>

    {{-- 2. FLOATING TIMER (RED) --}}
    <div class="timer-card-red">
        <span class="timer-label-red">WAKTU TERSISA</span>
        <div id="countdown" class="timer-digits-red">00:00:00</div>
    </div>

    {{-- 3. MOTIVATION CARD (PENGGANTI TOTAL UANG) --}}
    <div class="px-4 mb-4">
        <div class="motivation-card">
            {{-- Icon Background Dekoratif --}}
            <i class='bx bxs-megaphone motivation-icon-bg'></i>

            <h5 class="motivation-title">
                <i class='bx bxs-hot text-danger'></i>
                <span>Pesan Semangat</span>
            </h5>

            <p class="motivation-text">
                "Squidfighter berikan penampilan terbaik kalian untuk mendapatkan squidollars dari investor!"
            </p>
        </div>
    </div>

    {{-- 4. LIST INVESTMENT CARDS --}}
    <div class="px-4 pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <h6 class="fw-bold text-dark m-0 ps-2 border-start border-4 border-danger">Riwayat Dana Masuk</h6>
            <small class="text-muted fw-bold">Total: ${{ number_format($totalInvestment/1000) }}K</small>
        </div>

        @forelse($investments as $inv)
            <div class="investment-card">
                <div class="icon-box">
                    <i class='bx bx-line-chart-down'></i> {{-- Icon chart naik/turun --}}
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold text-dark">Dana Masuk!</h6>
                    <p class="mb-0 text-muted small lh-1">
                        Dari: <strong class="text-dark">{{ $inv->fromUser->name ?? 'Investor' }}</strong>
                    </p>
                    <small class="text-muted" style="font-size: 0.65rem;">{{ $inv->created_at->diffForHumans() }}</small>
                </div>
                <div class="text-end">
                    <span class="amount-text">+$ {{ number_format($inv->amount/1000, 0) }}K</span>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="bg-label-secondary rounded-circle d-inline-flex p-3 mb-3">
                    <i class='bx bx-radar fs-1 text-secondary'></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum ada sinyal</h6>
                <p class="text-muted small">Menunggu investor tertarik pada kelompokmu...</p>
            </div>
        @endforelse
    </div>

@endsection

@push('scripts')
<script>
    // TIMER LOGIC
    @php
        // PERBAIKAN: Mengambil 'show_end_time', bukan 'end_time'
        // Pastikan kolom ini terisi di database
        $isoEndTime = $event && $event->show_end_time
            ? \Carbon\Carbon::parse($event->show_end_time)->toIso8601String()
            : null;
    @endphp

    const endTimeStr = "{{ $isoEndTime }}";

    // Debugging (Opsional: Cek di Console Browser apakah waktu terbaca)
    console.log("Target Waktu:", endTimeStr);

    if(endTimeStr) {
        const endTime = new Date(endTimeStr).getTime();

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            // Jika waktu habis
            if (distance < 0) {
                clearInterval(timer);
                const el = document.getElementById("countdown");
                if(el) {
                    el.innerHTML = "SELESAI";
                    el.style.fontSize = "2rem";
                }
                return;
            }

            // Hitung mundur
            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            // Format 2 digit (01, 02, dst)
            const hh = h < 10 ? "0" + h : h;
            const mm = m < 10 ? "0" + m : m;
            const ss = s < 10 ? "0" + s : s;

            const el = document.getElementById("countdown");
            if(el) el.innerHTML = hh + ":" + mm + ":" + ss;
        }, 1000);
    } else {
        // Fallback jika waktu tidak di-set admin
        const el = document.getElementById("countdown");
        if(el) {
            el.innerHTML = "--:--:--";
            el.style.fontSize = "2rem";
        }
    }
</script>
@endpush
