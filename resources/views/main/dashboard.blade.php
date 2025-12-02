@extends('main.layouts.mobileMaster')

@section('title', 'Dashboard')

@section('styles')
  <style>
    /* --- HEADER SECTION --- */
    .dashboard-header {
      background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
      border-bottom-left-radius: 35px;
      border-bottom-right-radius: 35px;
      padding: 40px 25px 110px 25px;
      color: white;
      position: relative;
    }

    /* Tombol Header (Riwayat) */
    .btn-header-icon {
        width: 45px; height: 45px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 1.4rem;
        transition: 0.2s;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-header-icon:active { transform: scale(0.95); background: rgba(255, 255, 255, 0.3); }


    /* Badge Event & Role */
    .event-info-badge {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(5px);
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      display: inline-flex;
      align-items: center;
      margin-top: 5px;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* --- FLOATING BALANCE CARD --- */
    .balance-card {
      background: white;
      border-radius: 24px;
      padding: 20px 25px;
      margin: -80px 20px 25px 20px; /* Posisi floating */
      box-shadow: 0 15px 35px rgba(169, 173, 181, 0.15);
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .balance-amount {
      font-family: 'Public Sans', sans-serif;
      font-weight: 800;
      color: #232b2b;
      font-size: 1.8rem;
      line-height: 1;
      letter-spacing: -0.5px;
    }

    .currency-symbol {
      color: #00a79d;
      font-size: 1.5rem;
      margin-right: 2px;
      vertical-align: top;
      font-weight: 600;
    }

    /* Tombol Action Float */
    .btn-action-float {
      width: 50px; height: 50px;
      border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; cursor: pointer;
      transition: transform 0.2s;
      border: 1px solid transparent;
    }
    .btn-action-float:active { transform: scale(0.95); }

    .btn-transfer {
      background: linear-gradient(135deg, #e0f2f1 0%, #ffffff 100%);
      color: #00a79d; border-color: #f0fdfa;
      box-shadow: 0 5px 15px rgba(0, 167, 157, 0.1);
    }
    .btn-bank {
      background: linear-gradient(135deg, #e7f1ff 0%, #ffffff 100%);
      color: #007bff; border-color: #f0f8ff;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
    }

    /* --- PHASE MENU GRID --- */
    .phase-menu-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      padding: 0 25px;
      margin-bottom: 30px;
    }

    .phase-card {
      background: white;
      border-radius: 18px;
      padding: 15px 10px;
      text-align: center;
      text-decoration: none;
      color: #566a7f;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      position: relative;
      overflow: hidden;
      transition: transform 0.2s;
      border: 1px solid #f4f4f4;
    }
    .phase-card:active { transform: scale(0.98); }

    .phase-icon {
      font-size: 1.8rem;
      margin-bottom: 5px;
      display: block;
    }

    .phase-name {
      font-weight: 800;
      font-size: 0.85rem;
      display: block;
      color: #333;
    }

    .phase-status {
      font-size: 0.65rem;
      padding: 2px 8px;
      border-radius: 10px;
      margin-top: 5px;
      display: inline-block;
      font-weight: 700;
      text-transform: uppercase;
    }

    /* Status Colors */
    .status-running { background: #e8fadf; color: #71dd37; animation: pulse 2s infinite; }
    .status-upcoming { background: #fff2d6; color: #ffab00; }
    .status-ended { background: #f2f2f2; color: #b4bdce; }
    .status-locked { background: #f2f2f2; color: #b4bdce; filter: grayscale(1); opacity: 0.7; }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(113, 221, 55, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(113, 221, 55, 0); }
        100% { box-shadow: 0 0 0 0 rgba(113, 221, 55, 0); }
    }

    /* --- INTELLIGENCE CARD (GREEN THEME) --- */
    .custom-section-title {
        padding: 0 25px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .title-icon-box {
        width: 35px; height: 35px;
        /* Green Gradient for Title Icon */
        background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
    }
    .custom-section-title h6 {
        font-weight: 800;
        margin: 0;
        color: #232b2b;
        font-size: 1rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .radar-animation {
        width: 10px; height: 10px;
        background-color: #00a79d;
        border-radius: 50%;
        animation: radar-pulse 1.5s infinite;
    }
    @keyframes radar-pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 167, 157, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(0, 167, 157, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 167, 157, 0); }
    }

    /* Intel Card (Green Gradient) */
    .intel-card {
      margin: 0 25px 25px 25px;
      /* Background HIJAU/TEAL */
      background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
      border-radius: 20px;
      padding: 20px;
      position: relative;
      overflow: hidden;
      color: white;
      box-shadow: 0 10px 25px rgba(0, 167, 157, 0.3);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .intel-card::before {
        content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;
    }
    .intel-card::after {
        content: ''; position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%;
    }

    .intel-content { position: relative; z-index: 2; }
    .intel-label {
        font-size: 0.65rem; letter-spacing: 1px; text-transform: uppercase;
        color: rgba(255,255,255,0.9); font-weight: bold; margin-bottom: 5px;
        background: rgba(0,0,0,0.1); padding: 4px 10px; border-radius: 30px; display: inline-block;
    }
    .intel-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 5px; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .intel-desc { font-size: 0.85rem; opacity: 0.9; margin-bottom: 15px; font-weight: 300; }

    /* Button inside card */
    .btn-intel {
        background: white; color: #00796b; border: none; font-weight: 800;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* --- RECEIPT STYLE --- */
    .receipt-paper {
        background: #fff; padding: 20px; border-radius: 10px; position: relative;
        font-family: 'Courier New', Courier, monospace;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1); border-top: 5px solid #007bff;
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

  {{-- 1. HEADER --}}
  <div class="dashboard-header d-flex justify-content-between align-items-start">
    {{-- Left: Text & Badges --}}
    <div class="d-flex flex-column" style="max-width: 65%;">
      <p class="mb-0 opacity-75 small">Selamat Datang,</p>
      <h4 class="text-white fw-bold mb-2">{{ Auth::user()->name }}</h4>

      <div class="d-flex flex-column gap-1">
        <span class="badge bg-white text-primary rounded-pill px-3 py-1 shadow-sm me-auto"
          style="color: #00a79d !important; font-weight:700;">
          @if ($event)
            <i class='bx bx-calendar-event me-1'></i> {{ $event->name }}
          @else
            <i class='bx bx-error-circle me-1'></i> No Event
          @endif
        </span>

        @if ($group)
          <div class="event-info-badge text-white">
            <i class='bx bx-group me-1'></i>
            <span class="fw-bold me-1">{{ $group->name }}</span>
            <span class="opacity-75 mx-1">|</span>
            @php
              $myRole = 'Member';
              if ($group->captain_id == Auth::id()) $myRole = 'Captain';
              elseif ($group->cocaptain_id == Auth::id()) $myRole = 'Co-Captain';
            @endphp
            <span class="fw-light">{{ $myRole }}</span>
          </div>
        @else
          <div class="event-info-badge text-white">
            <span class="fw-light">Belum Ada Tim</span>
          </div>
        @endif
      </div>
    </div>

    {{-- Right: History & Profile --}}
    <div class="d-flex align-items-center gap-2">
        {{-- TOMBOL RIWAYAT (DI HEADER) --}}
        <a href="{{ route('main.transaction.history') }}" class="btn-header-icon" title="Riwayat Transaksi">
            <i class='bx bx-history'></i>
        </a>
    </div>
  </div>

  {{-- 2. FLOATING BALANCE CARD --}}
  <div class="balance-card">
    <div class="d-flex flex-column">
      {{-- Label --}}
      <span class="d-block text-muted small fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px; text-transform:uppercase;">
        SQUID BANK (TABUNGAN)
      </span>

      {{-- [PERBAIKAN] SQUID BANK ADALAH bank_balance --}}
      <h2 class="mb-0 balance-amount text-primary">
        <span class="currency-symbol text-primary">$</span>{{ number_format($group->bank_balance ?? 0, 0, ',', '.') }}
      </h2>

      {{-- [PERBAIKAN] CASH ADALAH squid_dollar --}}
      <div class="mt-1">
        <span class="badge bg-label-success rounded-pill" style="font-size: 0.7rem;">
            <i class='bx bx-wallet me-1'></i>Cash: ${{ number_format($group->squid_dollar ?? 0, 0, ',', '.') }}
        </span>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2">
        <div class="btn-action-float btn-bank" data-bs-toggle="modal" data-bs-target="#withdrawModal">
            <i class='bx bx-money-withdraw'></i>
        </div>
        <div class="btn-action-float btn-transfer" data-bs-toggle="modal" data-bs-target="#transferModal">
            <i class='bx bx-paper-plane'></i>
        </div>
    </div>
  </div>

  {{-- 3. PHASE MENU (CHALLENGE, CASE, SHOW) --}}
  @php
      $now = now();
      $phases = [
          [
              'name' => 'Challenge',
              'icon' => 'bx-joystick',
              'start' => $event->challenge_start_time ?? null,
              'end' => $event->challenge_end_time ?? null,
              'route' => route('main.challenges.index')
          ],
          [
              'name' => 'Case',
              'icon' => 'bx-briefcase-alt-2',
              'start' => $event->case_start_time ?? null,
              'end' => $event->case_end_time ?? null,
              'route' => route('main.cases.index')
          ],
          [
              'name' => 'Show',
              'icon' => 'bx-tv',
              'start' => $event->show_start_time ?? null,
              'end' => $event->show_end_time ?? null,
              'route' => '#'
          ]
      ];
  @endphp

  <div class="phase-menu-grid">
      @foreach($phases as $phase)
          @php
              $status = 'locked';
              $statusLabel = 'Locked';

              if ($phase['start'] && $phase['end']) {
                  if ($now < $phase['start']) {
                      $status = 'upcoming';
                      $statusLabel = 'Upcoming';
                  } elseif ($now >= $phase['start'] && $now <= $phase['end']) {
                      $status = 'running';
                      $statusLabel = 'Running';
                  } else {
                      $status = 'ended';
                      $statusLabel = 'Ended';
                  }
              } else {
                  $status = 'upcoming'; // Default
                  $statusLabel = 'Wait';
              }
          @endphp

          <a href="{{ $phase['route'] }}" class="phase-card {{ $status == 'locked' ? 'disabled' : '' }}">
              <i class='bx {{ $phase['icon'] }} phase-icon text-{{ $status == 'running' ? 'primary' : 'secondary' }}'></i>
              <span class="phase-name">{{ $phase['name'] }}</span>
              <span class="phase-status status-{{ $status }}">{{ $statusLabel }}</span>
          </a>
      @endforeach
  </div>

  {{-- 5. EVENT INTELLIGENCE CARD (GREEN THEME) --}}
  <div class="custom-section-title">
    <div class="title-icon-box">
        <i class='bx bx-radar fs-4'></i>
    </div>
    <div class="d-flex align-items-center w-100 justify-content-between">
        <h6>Event Intelligence</h6>
        <div class="radar-animation"></div>
    </div>
  </div>

  <div class="intel-card">
    <div class="intel-content">
        @php
            $activePhase = null;
            $nextPhase = null;

            foreach($phases as $p) {
                if ($now >= $p['start'] && $now <= $p['end']) {
                    $activePhase = $p;
                    break;
                }
                if ($now < $p['start'] && ($nextPhase == null || $p['start'] < $nextPhase['start'])) {
                    $nextPhase = $p;
                }
            }
        @endphp

        @if($activePhase)
            <div class="intel-label"><i class='bx bx-broadcast me-1'></i> LIVE NOW</div>
            <div class="intel-title">{{ $activePhase['name'] }} Phase</div>
            <p class="intel-desc">Fase ini sedang berlangsung aktif! Tingkatkan skor tim kamu sekarang juga.</p>
            <a href="{{ $activePhase['route'] }}" class="btn btn-sm btn-intel w-100 rounded-pill">
                Masuk Arena <i class='bx bx-right-arrow-alt'></i>
            </a>
        @elseif($nextPhase)
            <div class="intel-label"><i class='bx bx-time-five me-1'></i> UPCOMING</div>
            <div class="intel-title">{{ $nextPhase['name'] }} Phase</div>
            <p class="intel-desc">Bersiaplah! Fase selanjutnya akan dimulai pada <strong>{{ \Carbon\Carbon::parse($nextPhase['start'])->format('d M, H:i') }}</strong>.</p>
            <button class="btn btn-sm btn-outline-light w-100 rounded-pill" style="opacity: 0.8;" disabled>
                Menunggu Dimulai...
            </button>
        @else
            <div class="intel-label">EVENT STATUS</div>
            <div class="intel-title">Break Time</div>
            <p class="intel-desc">Tidak ada fase aktif saat ini. Cek leaderboard atau atur strategi dengan tim.</p>
        @endif
    </div>
  </div>

  {{-- ================= MODALS ================= --}}

  {{-- MODAL 1: TRANSFER (PAKE BANK BALANCE) --}}
  <div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark">Kirim ke Kelompok Lain</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-4">
          <form action="{{ route('main.transaction.transfer') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">Penerima</label>
              <select class="form-select form-select-lg border-0 bg-light" name="to_group_id" required style="border-radius: 15px;">
                <option value="" selected disabled>Pilih Kelompok...</option>
                @if (isset($allGroups) && count($allGroups) > 0)
                  @foreach ($allGroups as $targetGroup)
                    <option value="{{ $targetGroup->id }}">{{ $targetGroup->name }}</option>
                  @endforeach
                @else
                  <option disabled>Tidak ada kelompok lain</option>
                @endif
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-bold text-muted">Nominal (SQ$)</label>
              <div class="input-group input-group-lg bg-light rounded-3">
                <span class="input-group-text border-0 bg-transparent text-primary fw-bold">$</span>
                {{-- MAX: BANK BALANCE --}}
                <input type="number" name="amount" class="form-control border-0 bg-transparent fw-bold text-dark"
                       placeholder="0" min="100" max="{{ $group->bank_balance ?? 0 }}" required>
              </div>
              <small class="text-muted mt-1 d-block" style="font-size: 0.7rem;">
                {{-- LABEL: BANK BALANCE --}}
                Saldo Bank Tersedia: ${{ number_format($group->bank_balance ?? 0, 0, ',', '.') }}
              </small>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" style="background-color: #00a79d; border:none;">
              Kirim Uang <i class='bx bx-paper-plane ms-1'></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL 2: WITHDRAW FROM BANK --}}
  <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-primary"><i class='bx bx-money-withdraw me-1'></i> Tarik Uang dari Bank</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-3">
          <div class="alert alert-warning d-flex align-items-center" role="alert" style="font-size: 0.8rem;">
            <i class='bx bx-info-circle me-2 fs-5'></i>
            <div>
              Uang akan dipindahkan dari <strong>Squid Bank</strong> ke <strong>Dompet Cash</strong>.
            </div>
          </div>

          <form action="{{ route('main.transaction.withdrawFromBank') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label class="form-label small fw-bold text-muted">Jumlah Penarikan (SQ$)</label>
              <div class="input-group input-group-lg bg-light rounded-3">
                <span class="input-group-text border-0 bg-transparent text-primary fw-bold">$</span>
                {{-- MAX: BANK BALANCE --}}
                <input type="number" name="amount" class="form-control border-0 bg-transparent fw-bold text-dark"
                       placeholder="0" min="1" max="{{ $group->bank_balance ?? 0 }}" required>
              </div>
              <div class="d-flex justify-content-between mt-1">
                 <small class="text-muted" style="font-size: 0.7rem;">Sumber: Squid Bank</small>
                 {{-- SUMBER: BANK BALANCE --}}
                 <small class="fw-bold text-dark" style="font-size: 0.7rem;">Tersedia: ${{ number_format($group->bank_balance ?? 0) }}</small>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" style="background-color: #007bff; border:none;">
              Tarik ke Dompet <i class='bx bx-download ms-1'></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL 3: STRUK PENARIKAN (RECEIPT) --}}
  <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-transparent shadow-none border-0">
            @if(session('withdrawal_receipt'))
                @php $receipt = session('withdrawal_receipt'); @endphp
                <div class="receipt-paper">
                    <div class="receipt-title">
                        <i class='bx bxs-bank fs-1 d-block mb-2 text-primary'></i>
                        SQUID BANK<br>RECEIPT
                    </div>
                    <div class="receipt-row">
                        <span>Tanggal</span>
                        <span>{{ $receipt['date'] }}</span>
                    </div>
                    <div class="receipt-row">
                        <span>ID Transaksi</span>
                        <span>{{ $receipt['trx_id'] }}</span>
                    </div>
                    <div class="receipt-row">
                        <span>Jenis</span>
                        <span>WITHDRAWAL</span>
                    </div>
                    <div class="receipt-total receipt-row text-dark">
                        <span>TOTAL</span>
                        <span>$ {{ number_format($receipt['amount']) }}</span>
                    </div>
                    <hr style="border-style: dashed; margin: 10px 0;">
                    <div class="receipt-row">
                        <span>Sisa Bank</span>
                        <span>$ {{ number_format($receipt['balance_bank']) }}</span>
                    </div>
                    <div class="receipt-row">
                        <span>Total Cash</span>
                        <span>$ {{ number_format($receipt['balance_cash']) }}</span>
                    </div>
                    <div class="receipt-footer">
                        <p class="mb-1">TRANSAKSI BERHASIL</p>
                        <p>Simpan struk ini sebagai bukti.</p>
                        <button type="button" class="btn btn-dark btn-sm w-100 mt-2 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('withdrawal_receipt'))
        var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        receiptModal.show();
      @endif
    });
  </script>
@endpush
