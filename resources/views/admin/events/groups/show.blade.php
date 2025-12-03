  @extends('admin.layouts.contentNavbarLayout')

  @section('title', "Detail Kelompok - $group->name")

  @section('styles')
  <style>
      :root {
          --squid-primary: #00a79d;  /* Hijau Squid */
          --squid-gold: #ffab00;     /* Kuning Bank */
          --squid-light: #e0f2f1;
      }

      /* --- HEADER STYLE --- */
      .profile-header-card {
          border: none; border-radius: 16px; overflow: hidden;
          background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      }
      .profile-cover {
          height: 150px;
          /* Tetap Hijau Nuansa SquidCamp */
          background: linear-gradient(120deg, #00a79d, #4db6ac);
          position: relative;
      }
      .profile-body {
          padding: 1.5rem 2rem; position: relative;
          display: flex; justify-content: space-between; align-items: flex-end;
          flex-wrap: wrap; gap: 20px;
      }
      .group-avatar {
          width: 100px; height: 100px; border-radius: 50%;
          background: #fff; border: 4px solid #fff;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          position: absolute; top: -50px; left: 30px;
          display: flex; align-items: center; justify-content: center;
          font-size: 2.5rem; color: var(--squid-primary);
      }
      .profile-info { margin-left: 130px; margin-top: -10px; }
      .profile-name { font-size: 1.6rem; font-weight: 800; color: #333; margin-bottom: 5px; }

      /* --- FINANCIAL BOXES (CASH & BANK) --- */
      .finance-stats { display: flex; gap: 15px; }
      .stat-box {
          background: #fff; padding: 10px 20px; border-radius: 12px;
          border: 1px solid #f0f0f0; text-align: right;
          box-shadow: 0 2px 10px rgba(0,0,0,0.02);
      }
      .stat-label { font-size: 0.65rem; text-transform: uppercase; color: #999; font-weight: 700; display: block; margin-bottom: 2px; }
      .stat-value { font-size: 1.4rem; font-weight: 900; font-family: 'Courier New', monospace; line-height: 1; }

      /* Warna Spesifik */
      .text-cash { color: var(--squid-primary); } /* Hijau */
      .text-bank { color: var(--squid-gold); }    /* Kuning */

      /* --- LIST REVIEW --- */
      .review-item {
          border-bottom: 1px dashed #eee; padding: 12px 0;
          transition: 0.2s;
      }
      .review-item:last-child { border-bottom: none; }
      .review-item:hover { background-color: #fcfcfc; }

      /* Tombol Kecil (Tiny Button) */
      .btn-xs-pill {
          padding: 4px 12px; font-size: 0.7rem; border-radius: 50px; font-weight: 700;
          text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
          transition: transform 0.1s;
      }
      .btn-xs-pill:active { transform: scale(0.95); }

      /* Warna Tombol */
      .btn-view-link { background: #e0f7fa; color: #006064; }
      .btn-view-file { background: #fff8e1; color: #ff6f00; }

      /* Override Active Tab Color (Hijau) */
      .nav-pills .nav-link.active { background-color: var(--squid-primary); box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3); }
      .nav-pills .nav-link { color: #666; }
      .nav-pills .nav-link:hover { color: var(--squid-primary); }

      /* Progress Bar */
      .bg-squid { background-color: var(--squid-primary) !important; }
  </style>
  @endsection

  @section('content')

  {{-- HEADER STYLE BARU (Sesuai Permintaan) --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="fw-bold mb-1" style="color: #008f85;">
              <i class="bx bx-id-card me-2"></i>Detail Kelompok
          </h4>
          <span class="text-muted">Event: {{ $event->name }}</span>
      </div>
      <a href="{{ route('admin.events.groups.index', $event->id) }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back me-1"></i> Kembali
      </a>
  </div>

  {{-- 1. HEADER PROFILE CARD --}}
  <div class="card profile-header-card mb-4">
      <div class="profile-cover"></div>
      <div class="profile-body">

          {{-- Avatar --}}
          <div class="group-avatar">
              <i class='bx bxs-group'></i>
          </div>

          {{-- Info --}}
          <div class="profile-info">
              <h2 class="profile-name">{{ $group->name }}</h2>
              <div class="d-flex align-items-center gap-3 text-muted small">
                  <span><i class="bx bx-user me-1"></i> {{ $group->members->count() }} Anggota</span>
                  <span><i class="bx bx-user-voice me-1 text-info"></i> Mentor: <strong>{{ $group->mentor->name ?? '-' }}</strong></span>
              </div>
          </div>

          {{-- Info Keuangan (Cash Hijau, Bank Kuning) --}}
          <div class="finance-stats">
              {{-- Cash --}}
              <div class="stat-box" style="border-left: 4px solid var(--squid-primary);">
                  <span class="stat-label"><i class="bx bx-wallet"></i> CASH (DOMPET)</span>
                  <div class="stat-value text-cash">${{ number_format($group->squid_dollar, 0, ',', '.') }}</div>
              </div>
              {{-- Bank --}}
              <div class="stat-box" style="border-left: 4px solid var(--squid-gold);">
                  <span class="stat-label"><i class="bx bxs-bank"></i> BANK (TABUNGAN)</span>
                  <div class="stat-value text-bank">${{ number_format($group->bank_balance, 0, ',', '.') }}</div>
              </div>
          </div>
      </div>
  </div>

  <div class="row g-4">

      {{-- KIRI: DATA TIM --}}
      <div class="col-xl-4 col-lg-5">

          {{-- STRUKTUR TIM --}}
          <div class="card mb-4">
              <div class="card-header border-bottom py-3">
                  <h6 class="m-0 fw-bold text-dark"><i class="bx bx-crown text-warning me-1"></i> Struktur Tim</h6>
              </div>
              <div class="card-body pt-4">
                  {{-- Captain --}}
                  <div class="d-flex align-items-center mb-3 p-2 rounded bg-lighter border border-dashed">
                      <div class="avatar me-3">
                          <span class="avatar-initial rounded-circle bg-label-warning text-warning fw-bold">C</span>
                      </div>
                      <div>
                          <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem;">Captain</small>
                          <span class="fw-bold text-dark">{{ $group->captain->name ?? 'Belum ada' }}</span>
                      </div>
                  </div>
                  {{-- Co-Captain --}}
                  <div class="d-flex align-items-center p-2 rounded bg-lighter border border-dashed">
                      <div class="avatar me-3">
                          <span class="avatar-initial rounded-circle bg-label-secondary text-secondary fw-bold">Co</span>
                      </div>
                      <div>
                          <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem;">Co-Captain</small>
                          <span class="fw-bold text-dark">{{ $group->cocaptain->name ?? 'Belum ada' }}</span>
                      </div>
                  </div>

                  <div class="d-grid mt-3">
                      <a href="{{ route('admin.events.groups.edit', ['event' => $event->id, 'group' => $group->id]) }}" class="btn btn-primary btn-sm bg-squid" style="border:none;">
                          <i class="bx bx-edit me-1"></i> Kelola Anggota
                      </a>
                  </div>
              </div>
          </div>

          {{-- PROGRESS CARD --}}
          <div class="card">
              <div class="card-header border-bottom py-3">
                  <h6 class="m-0 fw-bold text-dark"><i class="bx bx-target-lock text-danger me-1"></i> Game Progress</h6>
              </div>
              <div class="card-body pt-4">
                  <div class="mb-4">
                      <div class="d-flex justify-content-between mb-1">
                          <span class="small fw-bold text-muted">Challenges</span>
                          <span class="small fw-bold text-primary">{{ $challengeProgress['completed'] }}/{{ $challengeProgress['total'] }}</span>
                      </div>
                      <div class="progress bg-label-primary" style="height: 8px;">
                          @php $pct = $challengeProgress['total'] > 0 ? ($challengeProgress['completed'] / $challengeProgress['total']) * 100 : 0; @endphp
                          <div class="progress-bar bg-squid" style="width: {{ $pct }}%"></div>
                      </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center p-3 bg-label-secondary rounded">
                      <span class="small fw-bold">Business Case</span>
                      <span class="badge bg-white text-dark shadow-sm">{{ $caseStatus }}</span>
                  </div>
              </div>
          </div>
      </div>

      {{-- KANAN: TABS (MEMBER, FINANCE, REVIEW) --}}
      <div class="col-xl-8 col-lg-7">
          <div class="nav-align-top mb-4">
              <ul class="nav nav-pills mb-3" role="tablist">
                  <li class="nav-item">
                      <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#navs-review">
                          <i class="bx bx-show me-1"></i> Misi & Submission
                      </button>
                  </li>
                  <li class="nav-item">
                      <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#navs-finance">
                          <i class="bx bx-money me-1"></i> Riwayat Keuangan
                      </button>
                  </li>
                  <li class="nav-item">
                      <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#navs-members">
                          <i class="bx bx-user me-1"></i> Anggota
                      </button>
                  </li>
              </ul>

              <div class="tab-content shadow-sm border-0 rounded-3 p-4 bg-white">

                  {{-- TAB 1: REVIEW SUBMISSION --}}
                  <div class="tab-pane fade show active" id="navs-review">
                      <h6 class="fw-bold mb-3 text-primary"><i class='bx bx-joystick me-1'></i> Challenge Submissions</h6>

                      @forelse($challengeSubmissions as $sub)
                          <div class="review-item d-flex justify-content-between align-items-center">
                              <div style="max-width: 65%;">
                                  <div class="d-flex align-items-center gap-2 mb-1">
                                      <span class="fw-bold text-dark text-truncate">{{ $sub->challenge->nama }}</span>
                                      @if($sub->status == 'approved') <i class='bx bxs-check-circle text-success'></i>
                                      @elseif($sub->status == 'rejected') <i class='bx bxs-x-circle text-danger'></i>
                                      @else <i class='bx bxs-time text-warning'></i> @endif
                                  </div>
                                  <div class="small text-muted">
                                      Status:
                                      <span class="fw-bold text-{{ $sub->status == 'approved' ? 'success' : ($sub->status == 'rejected' ? 'danger' : 'warning') }}">
                                          {{ ucfirst($sub->status) }}
                                      </span>
                                      <span class="mx-1">•</span>
                                      {{ $sub->updated_at->diffForHumans() }}
                                  </div>
                              </div>

                              {{-- TOMBOL KECIL REVIEW --}}
                              <div class="d-flex gap-2">
                                  @if($sub->submission_text)
                                      <a href="{{ $sub->submission_text }}" target="_blank" class="btn-xs-pill btn-view-link" title="Buka Link">
                                          <i class='bx bx-link'></i> Link
                                      </a>
                                  @endif
                                  @if($sub->file_path)
                                      <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank" class="btn-xs-pill btn-view-file" title="Lihat File">
                                          <i class='bx bx-show'></i> File
                                      </a>
                                  @endif
                              </div>
                          </div>
                      @empty
                          <div class="text-center py-4 text-muted small bg-lighter rounded">
                              Belum ada challenge yang dikerjakan.
                          </div>
                      @endforelse

                      <h6 class="fw-bold mt-4 mb-3 text-warning"><i class='bx bx-briefcase-alt-2 me-1'></i> Business Case Submissions</h6>
                      @forelse($caseSubmissions as $case)
                          <div class="review-item d-flex justify-content-between align-items-center">
                              <div style="max-width: 65%;">
                                  <span class="fw-bold text-dark d-block text-truncate">{{ $case->case_title }}</span>
                                  <small class="text-muted">Rank: <strong>#{{ $case->rank }}</strong> | Reward: ${{ number_format($case->reward_amount) }}</small>
                              </div>
                              <div class="d-flex gap-2">
                                  @if($case->submission_text)
                                      <a href="{{ $case->submission_text }}" target="_blank" class="btn-xs-pill btn-view-link">
                                          <i class='bx bx-link'></i> Link
                                      </a>
                                  @endif
                                  @if($case->file_path)
                                      <a href="{{ asset('storage/'.$case->file_path) }}" target="_blank" class="btn-xs-pill btn-view-file">
                                          <i class='bx bx-show'></i> File
                                      </a>
                                  @endif
                              </div>
                          </div>
                      @empty
                          <div class="text-center py-4 text-muted small bg-lighter rounded">
                              Belum ada case yang dikerjakan.
                          </div>
                      @endforelse
                  </div>

                  {{-- TAB 2: FINANCE HISTORY --}}
                  <div class="tab-pane fade" id="navs-finance">
                      <div class="list-group list-group-flush">
                          @forelse($transactions as $trx)
                              @php
                                  // Logic Ikon & Warna
                                  $isIncoming = ($trx->to_type == 'group' && $trx->to_id == $group->id);

                                  if($trx->reason == 'CHALLENGE_REWARD' || $trx->reason == 'CASE_REWARD') {
                                      $icon = 'bx-trophy'; $color = 'text-warning'; $desc = 'Reward Game';
                                  } elseif($trx->reason == 'GROUP_TRANSFER') {
                                      $icon = 'bx-transfer'; $color = $isIncoming ? 'text-success' : 'text-danger';
                                      $desc = $isIncoming ? 'Transfer Masuk' : 'Transfer Keluar';
                                  } elseif($trx->reason == 'BUY_GUIDELINE') {
                                      $icon = 'bx-cart'; $color = 'text-danger'; $desc = 'Belanja Toko';
                                  } elseif($trx->reason == 'BANK_WITHDRAWAL') {
                                      $icon = 'bx-money-withdraw'; $color = 'text-info'; $desc = 'Tarik Tunai Bank';
                                  } elseif($trx->reason == 'INVESTMENT') {
                                      $icon = 'bx-line-chart'; $color = 'text-success'; $desc = 'Suntikan Investor';
                                  } else {
                                      $icon = 'bx-dollar-circle'; $color = 'text-secondary'; $desc = 'Transaksi Lain';
                                  }
                              @endphp

                              <div class="list-group-item p-3 mb-2 rounded border" style="border-left: 4px solid {{ $isIncoming ? '#71dd37' : '#ff3e1d' }};">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div class="d-flex align-items-center">
                                          <div class="avatar avatar-sm me-3 bg-lighter rounded-circle d-flex align-items-center justify-content-center">
                                              <i class="bx {{ $icon }} {{ $color }} fs-4"></i>
                                          </div>
                                          <div>
                                              <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $desc }}</h6>
                                              <small class="text-muted">{{ $trx->description }}</small>
                                              <div class="small text-muted fst-italic">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                          </div>
                                      </div>
                                      <div class="text-end">
                                          <span class="fw-bold fs-6 {{ $isIncoming ? 'text-success' : 'text-danger' }}">
                                              {{ $isIncoming ? '+' : '-' }} ${{ number_format($trx->amount) }}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          @empty
                              <div class="text-center py-5">
                                  <i class="bx bx-notepad fs-1 text-muted opacity-50 mb-2"></i>
                                  <p class="text-muted">Belum ada riwayat transaksi.</p>
                              </div>
                          @endforelse
                      </div>
                  </div>

                  {{-- TAB 3: ANGGOTA --}}
                  <div class="tab-pane fade" id="navs-members">
                      <div class="table-responsive">
                          <table class="table table-hover">
                              <thead>
                                  <tr>
                                      <th>Nama</th>
                                      <th>Email</th>
                                      <th>Role</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach($group->members as $member)
                                  <tr>
                                      <td>{{ $member->user->name }}</td>
                                      <td>{{ $member->user->email }}</td>
                                      <td>
                                          @if($group->captain_id == $member->user_id) <span class="badge bg-warning">Captain</span>
                                          @elseif($group->cocaptain_id == $member->user_id) <span class="badge bg-info">Co-Captain</span>
                                          @else <span class="badge bg-label-secondary">Member</span> @endif
                                      </td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>

              </div>
          </div>
      </div>
  </div>
  @endsection
