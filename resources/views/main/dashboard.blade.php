@extends('main.layouts.mobileMaster')

@section('title', 'Dashboard')

@section('styles')
  <style>
    /* --- HEADER SECTION --- */
    .dashboard-header {
      background: linear-gradient(135deg, #00a79d 0%, #00d4c7 100%);
      border-bottom-left-radius: 35px;
      border-bottom-right-radius: 35px;
      padding: 40px 25px 100px 25px;
      color: white;
      position: relative;
    }

    .header-profile-img {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 0.3);
      object-fit: cover;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

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
      margin: -70px 20px 25px 20px;
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
      width: 50px;
      height: 50px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      transition: transform 0.2s;
      cursor: pointer;
      border: 1px solid transparent;
    }

    .btn-action-float:active {
      transform: scale(0.95);
    }

    /* Tombol Transfer */
    .btn-transfer {
      background: linear-gradient(135deg, #e0f2f1 0%, #ffffff 100%);
      color: #00a79d;
      box-shadow: 0 5px 15px rgba(0, 167, 157, 0.1);
      border-color: #f0fdfa;
    }

    /* Tombol Withdraw/Bank */
    .btn-bank {
      background: linear-gradient(135deg, #e7f1ff 0%, #ffffff 100%);
      color: #007bff;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
      border-color: #f0f8ff;
    }

    /* --- QUICK MENU GRID --- */
    .quick-menu-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      padding: 0 25px;
      margin-bottom: 30px;
    }

    .quick-menu-item {
      text-align: center;
      text-decoration: none;
      color: #566a7f;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .quick-icon {
      width: 55px;
      height: 55px;
      background: white;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      font-size: 1.6rem;
      margin-bottom: 8px;
      transition: transform 0.2s;
    }

    .quick-menu-item:active .quick-icon {
      transform: scale(0.95);
    }

    /* --- SECTION TITLE & MISSION CARD --- */
    .section-heading {
      padding: 0 25px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .section-heading h6 { font-weight: 800; margin: 0; color: #232b2b; }
    .section-heading a { font-size: 0.8rem; color: #00a79d; text-decoration: none; }
    .mission-card {
      margin: 0 25px 20px 25px;
      background: white;
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      border-left: 5px solid #00a79d;
    }
  </style>
@endsection

@section('content')

  {{-- 1. HEADER --}}
  <div class="dashboard-header d-flex justify-content-between align-items-start">
    <div>
      <p class="mb-0 opacity-75 small">Selamat Datang,</p>
      <h4 class="text-white fw-bold mb-1">{{ Auth::user()->name }}</h4>

      {{-- INFORMASI --}}
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

    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/img/avatars/1.png') }}"
      alt="User" class="header-profile-img shadow-sm mt-1">
  </div>

  {{-- 2. FLOATING BALANCE CARD --}}
  <div class="balance-card">
    {{-- Sisi Kiri: Informasi Saldo --}}
    <div class="d-flex flex-column">

      {{-- LABEL: SQUID BANK (UTAMA) --}}
      <span class="d-block text-muted small fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px; text-transform:uppercase;">
        SQUID BANK (TABUNGAN)
      </span>

      {{-- SALDO: MENGGUNAKAN squid_dollar (Sekarang jadi Bank) --}}
      <h2 class="mb-0 balance-amount text-primary">
        <span class="currency-symbol text-primary">$</span>{{ number_format($group->squid_dollar ?? 0, 0, ',', '.') }}
      </h2>

      {{-- SUB-SALDO: CASH (Menggunakan bank_balance) --}}
      <div class="mt-1">
        <span class="badge bg-label-success rounded-pill" style="font-size: 0.7rem;">
            {{-- bank_balance sekarang dianggap CASH --}}
            <i class='bx bx-wallet me-1'></i>Cash: ${{ number_format($group->bank_balance ?? 0, 0, ',', '.') }}
        </span>
      </div>
    </div>

    {{-- Sisi Kanan: Tombol Action --}}
    <div class="d-flex gap-2">
        {{-- TOMBOL WITHDRAW (Tarik dari Bank) --}}
        <div class="btn-action-float btn-bank" data-bs-toggle="modal" data-bs-target="#withdrawModal">
            <i class='bx bx-money-withdraw'></i>
        </div>

        {{-- TOMBOL TRANSFER (Pakai Cash) --}}
        <div class="btn-action-float btn-transfer" data-bs-toggle="modal" data-bs-target="#transferModal">
            <i class='bx bx-paper-plane'></i>
        </div>
    </div>
  </div>

  {{-- 3. QUICK MENU --}}
  <div class="quick-menu-grid">
    <a href="{{ route('main.leaderboard.index') }}" class="quick-menu-item">
      <div class="quick-icon text-warning"><i class='bx bx-crown'></i></div>
      <span class="small fw-bold">Rank</span>
    </a>
    <a href="{{ route('main.group.index') }}" class="quick-menu-item">
      <div class="quick-icon text-info"><i class='bx bx-group'></i></div>
      <span class="small fw-bold">Tim</span>
    </a>
    <a href="#" class="quick-menu-item">
      <div class="quick-icon text-danger"><i class='bx bx-bell'></i></div>
      <span class="small fw-bold">Info</span>
    </a>
    <a href="#" class="quick-menu-item">
      <div class="quick-icon text-primary"><i class='bx bx-support'></i></div>
      <span class="small fw-bold">Bantuan</span>
    </a>
  </div>

  {{-- 4. ACTIVE MISSION --}}
  <div class="section-heading">
    <h6>Misi Terbaru</h6>
    <a href="#">Lihat Semua</a>
  </div>
  <div class="mission-card">
    <span class="badge bg-label-warning mb-2">PENDING</span>
    <h5 class="fw-bold text-dark mb-1">Belum ada misi aktif</h5>
    <p class="text-muted small mb-3">Tunggu instruksi mentor untuk tantangan selanjutnya.</p>
    <a href="#" class="btn btn-sm btn-primary w-100 rounded-pill" style="background-color: #00a79d; border:none;">
      Cek Arena Lomba
    </a>
  </div>

  {{-- ================= MODALS ================= --}}

  {{-- MODAL 1: TRANSFER (Menggunakan CASH / bank_balance) --}}
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

                {{-- TRANSFER PAKE CASH (bank_balance) --}}
                <input type="number" name="amount" class="form-control border-0 bg-transparent fw-bold text-dark"
                       placeholder="0" min="100" max="{{ $group->bank_balance ?? 0 }}" required>
              </div>
              <small class="text-muted mt-1 d-block" style="font-size: 0.7rem;">
                {{-- Label Cash, Variable bank_balance --}}
                Saldo Cash Tersedia: ${{ number_format($group->bank_balance ?? 0, 0, ',', '.') }}
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

  {{-- MODAL 2: WITHDRAW FROM BANK (Tarik dari squid_dollar ke bank_balance/Cash) --}}
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
              Uang akan dipindahkan dari <strong>Squid Bank</strong> ke <strong>Dompet Cash</strong> agar bisa digunakan.
            </div>
          </div>

          <form action="{{ route('main.transaction.withdrawFromBank') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label class="form-label small fw-bold text-muted">Jumlah Penarikan (SQ$)</label>
              <div class="input-group input-group-lg bg-light rounded-3">
                <span class="input-group-text border-0 bg-transparent text-primary fw-bold">$</span>

                {{-- WITHDRAW DARI BANK (squid_dollar) --}}
                <input type="number" name="amount" class="form-control border-0 bg-transparent fw-bold text-dark"
                       placeholder="0" min="1" max="{{ $group->squid_dollar ?? 0 }}" required>
              </div>

              <div class="d-flex justify-content-between mt-1">
                 <small class="text-muted" style="font-size: 0.7rem;">Sumber: Squid Bank</small>
                 {{-- Label Bank, Variable squid_dollar --}}
                 <small class="fw-bold text-dark" style="font-size: 0.7rem;">Tersedia: ${{ number_format($group->squid_dollar ?? 0) }}</small>
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

@endsection
