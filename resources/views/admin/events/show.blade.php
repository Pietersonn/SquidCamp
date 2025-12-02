@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Detail Event: ' . $event->name)

@section('styles')
  <style>
    :root {
      --squid-primary: #00a79d;
      --squid-light: #e0f2f1;
      --squid-dark: #00796b;
    }

    /* --- HERO BANNER --- */
    .event-hero {
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 10px 30px rgba(0, 167, 157, 0.15);
      background: #fff;
    }

    .banner-bg {
      height: 280px;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .banner-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.8));
    }

    .hero-content {
      position: absolute;
      bottom: 20px;
      left: 20px;
      color: white;
      z-index: 2;
    }

    .status-badge-floating {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 2;
      padding: 8px 16px;
      border-radius: 30px;
      font-weight: bold;
      background: rgba(255, 255, 255, 0.9);
      color: #333;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(5px);
    }

    .status-active { color: var(--squid-primary); }
    .status-inactive { color: #858796; }

    /* --- PHASE TIMELINE --- */
    .phase-container {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 15px;
      margin-top: -1px;
    }

    .phase-step {
      position: relative;
      padding: 10px;
      border-radius: 8px;
      background: #fff;
      border: 1px solid #eee;
      transition: 0.3s;
      text-align: center;
      flex: 1;
    }

    .phase-step.active {
      background: var(--squid-primary);
      color: white;
      border-color: var(--squid-primary);
      box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
      transform: translateY(-2px);
    }

    .phase-step.passed {
      background: var(--squid-light);
      color: var(--squid-dark);
      border-color: var(--squid-light);
    }

    .phase-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
    }

    .phase-status-text {
      font-size: 0.85rem;
      margin-top: 4px;
      display: block;
    }

    /* --- QUICK MENU GRID --- */
    .menu-card {
      border: none;
      border-radius: 16px;
      background: #fff;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      height: 100%;
      display: flex;
      align-items: center;
      padding: 1.5rem;
      text-decoration: none !important;
      position: relative;
      overflow: hidden;
    }

    .menu-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 167, 157, 0.15);
    }

    .menu-card::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background: var(--squid-primary);
      opacity: 0;
      transition: 0.3s;
    }

    .menu-card:hover::before { opacity: 1; }

    .menu-icon-box {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      background: var(--squid-light);
      color: var(--squid-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-right: 1rem;
      transition: 0.3s;
    }

    .menu-card:hover .menu-icon-box {
      background: var(--squid-primary);
      color: white;
      transform: rotate(10deg);
    }

    .menu-title {
      color: #333;
      font-weight: 700;
      font-size: 0.95rem;
      margin-bottom: 2px;
    }

    .menu-desc {
      color: #888;
      font-size: 0.75rem;
    }

    /* --- SIDEBAR WIDGETS --- */
    .widget-card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      background: #fff;
      margin-bottom: 1.5rem;
      overflow: hidden;
    }

    .widget-header {
      padding: 1.25rem;
      border-bottom: 1px solid #f0f0f0;
      font-weight: 700;
      color: #333;
      display: flex;
      align-items: center;
    }

    /* Countdown */
    .timer-box {
      background: linear-gradient(135deg, #333 0%, #000 100%);
      color: var(--squid-primary);
      font-family: 'Courier New', monospace;
      font-weight: bold;
      font-size: 1.8rem;
      padding: 1rem;
      border-radius: 8px;
      text-align: center;
      border: 2px solid #444;
      box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
      text-shadow: 0 0 10px var(--squid-primary);
      margin-top: 10px;
    }

    /* Leaderboard */
    .rank-item {
      padding: 12px 15px;
      border-bottom: 1px dashed #eee;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: 0.2s;
    }
    .rank-item:last-child { border-bottom: none; }
    .rank-item:hover { background-color: #f9f9f9; }

    .rank-badge {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: bold;
      margin-right: 10px;
      color: white;
    }
    .rank-1 { background: #FFD700; box-shadow: 0 2px 5px rgba(255, 215, 0, 0.4); }
    .rank-2 { background: #C0C0C0; }
    .rank-3 { background: #CD7F32; }
    .rank-other { background: #eee; color: #777; }
  </style>
@endsection

@section('content')

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--squid-primary);">
      <span class="text-muted fw-light">Event /</span> Dashboard
    </h4>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
  </div>

  <div class="row gx-4 gy-4">

    {{-- LEFT SIDE: MAIN CONTENT --}}
    <div class="col-12 col-xl-8">

      {{-- 1. HERO BANNER --}}
      <div class="event-hero mb-4">
        {{-- Status Badge --}}
        <div class="status-badge-floating {{ $event->is_active ? 'status-active' : 'status-inactive' }}">
          <i class="bx {{ $event->is_active ? 'bx-check-circle' : 'bx-power-off' }} me-1"></i>
          {{ $event->is_active ? 'ACTIVE EVENT' : 'NON-ACTIVE' }}
        </div>

        <div class="banner-bg"
          style="background-image: url('{{ $event->banner_image_path ? asset('storage/' . $event->banner_image_path) : asset('assets/img/backgrounds/1.jpg') }}')">
          <div class="banner-overlay"></div>
          <div class="hero-content">
            <div class="d-flex align-items-center mb-2">
              <span class="badge bg-white text-primary me-2 rounded-pill">
                <i class="bx bx-building me-1"></i> {{ $event->instansi ?? 'Umum' }}
              </span>
              <span class="badge bg-black bg-opacity-50 text-white rounded-pill backdrop-blur">
                <i class="bx bx-calendar me-1"></i>
                {{ $event->event_date?->translatedFormat('l, d F Y') ?? 'Tanggal Belum Diatur' }}
              </span>
            </div>
            <h2 class="text-white fw-bold mb-0 text-shadow">{{ $event->name }}</h2>
          </div>
        </div>

        {{-- 2. PHASE TIMELINE --}}
        <div class="phase-container">
          <div class="d-flex gap-2 flex-wrap">
            @php
              $phases = [
                  'Challenge' => [
                      'start' => $event->challenge_start_time ? \Carbon\Carbon::parse($event->challenge_start_time) : null,
                      'end' => $event->challenge_end_time ? \Carbon\Carbon::parse($event->challenge_end_time) : null,
                      'icon' => 'bx-target-lock',
                  ],
                  'Case' => [
                      'start' => $event->case_start_time ? \Carbon\Carbon::parse($event->case_start_time) : null,
                      'end' => $event->case_end_time ? \Carbon\Carbon::parse($event->case_end_time) : null,
                      'icon' => 'bx-briefcase',
                  ],
                  'Show' => [
                      'start' => $event->show_start_time ? \Carbon\Carbon::parse($event->show_start_time) : null,
                      'end' => $event->show_end_time ? \Carbon\Carbon::parse($event->show_end_time) : null,
                      'icon' => 'bx-tv'
                  ],
              ];
              $now = now();
            @endphp

            @foreach ($phases as $label => $times)
              @php
                $statusClass = '';
                $statusText = 'Pending';
                if (!$times['start'] || !$times['end']) {
                    $statusText = 'Not Set';
                } elseif ($now < $times['start']) {
                    $statusText = 'Upcoming';
                } elseif ($now >= $times['start'] && $now <= $times['end']) {
                    $statusClass = 'active';
                    $statusText = 'RUNNING';
                } else {
                    $statusClass = 'passed';
                    $statusText = 'Finished';
                }
              @endphp

              <div class="phase-step {{ $statusClass }}">
                <div class="d-flex align-items-center justify-content-center mb-1">
                  <i class="bx {{ $times['icon'] }} me-1"></i>
                  <span class="phase-label">{{ $label }} Phase</span>
                </div>
                <strong class="phase-status-text">{{ $statusText }}</strong>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- 3. QUICK MENU GRID --}}
      <h5 class="fw-bold mb-3 text-muted text-uppercase small">Manage Event Modules</h5>
      <div class="row gx-3 gy-3">
        @php
          $quickMenus = [
              [
                  'route' => 'admin.events.edit',
                  'icon' => 'bx-cog', 'label' => 'Settings', 'desc' => 'Edit detail & timer', 'color' => 'secondary',
              ],
              [
                  'route' => 'admin.events.groups.index',
                  'icon' => 'bx-group', 'label' => 'Groups', 'desc' => 'Kelola tim & anggota', 'color' => 'primary',
              ],
              [
                  'route' => 'admin.events.mentors.index',
                  'icon' => 'bx-user-voice', 'label' => 'Mentors', 'desc' => 'Assign mentor ke tim', 'color' => 'info',
              ],
              [
                  'route' => 'admin.events.challenges.index',
                  'icon' => 'bx-joystick', 'label' => 'Challenge', 'desc' => 'Squid Challenge', 'color' => 'warning',
              ],
              [
                  'route' => 'admin.events.cases.index',
                  'icon' => 'bx-briefcase-alt-2', 'label' => 'Case', 'desc' => 'Squid Case', 'color' => 'success',
              ],
              [
                  'route' => 'admin.events.guidelines.index',
                  'icon' => 'bx-book-open', 'label' => 'Guidelines', 'desc' => 'Materi & Aturan', 'color' => 'danger',
              ],
              [
                  'route' => 'admin.events.investors.index',
                  'icon' => 'bx-line-chart', 'label' => 'Investors', 'desc' => 'Modal & Investasi', 'color' => 'dark',
              ],
              // --- TAMBAHAN QUICK ACCESS ---
              [
                  'route' => 'admin.events.squidbank.index',
                  'icon'  => 'bx-dollar-circle',
                  'label' => 'Squid Bank',
                  'desc'  => 'Cek Mutasi & Cadangan',
                  'color' => 'success',
              ],
              [
                  'route' => 'admin.events.case-submission.index', // Menuju CaseSubmissionController
                  'icon'  => 'bx-list-check',
                  'label' => 'Case Submissions',
                  'desc'  => 'Cek Jawaban Peserta',
                  'color' => 'primary',
              ],
          ];
        @endphp

        @foreach ($quickMenus as $menu)
          <div class="col-md-6 col-lg-4">
            <a href="{{ route($menu['route'], $event->id) }}" class="menu-card">
              <div class="menu-icon-box bg-label-{{ $menu['color'] }} text-{{ $menu['color'] }}">
                <i class="bx {{ $menu['icon'] }}"></i>
              </div>
              <div>
                <div class="menu-title">{{ $menu['label'] }}</div>
                <div class="menu-desc">{{ $menu['desc'] }}</div>
              </div>
            </a>
          </div>
        @endforeach
      </div>

    </div>

    {{-- RIGHT SIDE: WIDGETS --}}
    <div class="col-12 col-xl-4">

      {{-- WIDGET: TIMER --}}
      <div class="widget-card">
        <div class="widget-header bg-light">
          <i class="bx bx-time-five me-2 text-primary"></i>
          <span>Live Countdown</span>
        </div>
        <div class="card-body p-4 text-center">
          @php
            $countdownTime = null; $phaseLabel = null;
            foreach ($phases as $label => $times) {
                if ($times['end'] && now() < $times['end']) {
                    $countdownTime = $times['end']; $phaseLabel = $label; break;
                }
            }
          @endphp

          @if ($countdownTime)
            <small class="text-muted d-block mb-1 text-uppercase fw-bold">Menuju Akhir {{ $phaseLabel }}</small>
            <div class="timer-box" id="event-countdown"
              data-countdown="{{ $countdownTime->setTimezone('Asia/Makassar')->toIso8601String() }}">
              00h 00m 00s
            </div>
          @else
            <div class="py-3">
              <i class="bx bx-check-double fs-1 text-success mb-2"></i>
              <h5 class="mb-0">Tidak ada fase aktif</h5>
              <small class="text-muted">Event selesai atau belum dimulai.</small>
            </div>
          @endif
        </div>
      </div>

      {{-- WIDGET: LEADERBOARD --}}
      @if (isset($leaderboardGroups) && count($leaderboardGroups) > 0)
        <div class="widget-card">
          <div class="widget-header">
            <i class="bx bx-trophy me-2 text-warning"></i>
            <span>Top Groups (Total Wealth)</span>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @foreach ($leaderboardGroups as $index => $group)
                <div class="rank-item">
                  <div class="d-flex align-items-center">
                    @php
                      $rankClass = 'rank-other';
                      if ($index == 0) $rankClass = 'rank-1';
                      elseif ($index == 1) $rankClass = 'rank-2';
                      elseif ($index == 2) $rankClass = 'rank-3';
                    @endphp
                    <div class="rank-badge {{ $rankClass }}">
                      {{ $loop->iteration }}
                    </div>
                    <div>
                      <span class="fw-bold text-dark d-block">{{ $group->name }}</span>
                      <small class="text-muted" style="font-size: 0.7rem;">{{ $group->members_count }} Members</small>
                    </div>
                  </div>

                  <div class="text-end">
                      <span class="badge bg-label-success fw-bold">
                        ${{ number_format($group->total_wealth, 0, ',', '.') }}
                      </span>
                      <div class="text-muted small" style="font-size: 0.6rem; line-height: 1.1; margin-top: 2px;">
                          B: ${{ \Illuminate\Support\Str::limit(number_format($group->squid_dollar), 8) }}<br>
                          C: ${{ \Illuminate\Support\Str::limit(number_format($group->bank_balance), 8) }}
                      </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="card-footer bg-light text-center p-2">
            <a href="{{ route('admin.events.groups.index', $event->id) }}" class="small text-primary fw-bold">Lihat Semua Leaderboard &rarr;</a>
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const countdownEl = document.getElementById('event-countdown');
      if (!countdownEl) return;
      const endTime = new Date(countdownEl.dataset.countdown).getTime();

      function updateCountdown() {
        const now = new Date().getTime();
        let distance = endTime - now;
        if (distance <= 0) {
          countdownEl.innerHTML = "<span class='text-danger'>WAKTU HABIS!</span>";
          return;
        }
        const totalHours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const h = totalHours < 10 ? "0" + totalHours : totalHours;
        const m = minutes < 10 ? "0" + minutes : minutes;
        const s = seconds < 10 ? "0" + seconds : seconds;
        countdownEl.innerHTML = `${h}h ${m}m ${s}s`;
      }
      updateCountdown();
      setInterval(updateCountdown, 1000);
    });
  </script>
@endpush
