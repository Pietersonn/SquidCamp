@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Detail Event: ' . $event->name)

@section('styles')
  <style>
    .banner-bg {
      height: 260px;
      background-size: cover;
      background-position: center;
      border-radius: 0.5rem 0.5rem 0 0;
    }

    .info-card {
      border-left: 5px solid #696cff !important;
    }

    .leader-card {
      transition: .2s;
    }

    .leader-card:hover {
      transform: scale(1.03);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }

    /* Status Timer */
    .phase-status {
      font-size: 0.95rem;
      font-weight: 600;
      color: #fff;
      padding: 0.4rem 0.6rem;
      border-radius: 0.5rem;
      display: inline-block;
      min-width: 130px;
      text-align: center;
    }

    .status-upcoming {
      background-color: #f6c23e;
    }

    .status-running {
      background-color: #4e73df;
    }

    .status-finished {
      background-color: #1cc88a;
    }

    .status-not-set {
      background-color: #858796;
    }

    /* Countdown */
    #event-countdown {
      font-size: 1.4rem;
      font-weight: bold;
      color: #4e73df;
    }
  </style>
@endsection

@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Event Management /</span> Detail Event
  </h4>

  <div class="row gx-3 gy-3">

    {{-- LEFT SIDE --}}
    <div class="col-12 col-xl-8">

      {{-- Banner --}}
      <div class="card mb-3">
        <div class="card-body p-0">
          <div class="banner-bg" style="background-image: url('{{ asset('storage/' . $event->banner_image_path) }}')"></div>
          <div class="p-3">
            <h4 class="mb-1">{{ $event->name }}</h4>
            <div class="d-flex flex-wrap gap-3">
              <span class="badge bg-primary">
                <i class="bx bx-building-house me-1"></i>
                {{ $event->instansi ?? 'Umum' }}
              </span>

              <span class="badge bg-{{ $event->is_active ? 'success' : 'secondary' }}">
                <i class="bx bx-check-shield me-1"></i>
                {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>

              <span class="badge bg-info">
                <i class="bx bx-calendar me-1"></i>
                {{ $event->event_date?->translatedFormat('l, d F Y') ?? 'Belum diatur' }}
              </span>
            </div>

            <hr>

            {{-- Phase Status --}}
            <div class="row text-center gx-2">
              @php
                $phases = [
                    'Challenge' => ['start' => $event->challenge_start_time, 'end' => $event->challenge_end_time],
                    'Case' => ['start' => $event->case_start_time, 'end' => $event->case_end_time],
                    'Show' => ['start' => $event->show_start_time, 'end' => $event->show_end_time],
                ];
                $now = now();
              @endphp

              @foreach ($phases as $label => $times)
                @php
                  if (!$times['start'] || !$times['end']) {
                      $statusText = 'Belum diatur';
                      $statusClass = 'status-not-set';
                  } elseif ($now < $times['start']) {
                      $statusText = 'Segera Dimulai';
                      $statusClass = 'status-upcoming';
                  } elseif ($now >= $times['start'] && $now <= $times['end']) {
                      $statusText = 'Sedang Berlangsung';
                      $statusClass = 'status-running';
                  } else {
                      $statusText = 'Telah Selesai';
                      $statusClass = 'status-finished';
                  }
                @endphp
                <div class="col">
                  <small class="text-muted">{{ $label }}</small>
                  <div class="phase-status {{ $statusClass }}">{{ $statusText }}</div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      {{-- Quick Menu --}}
      <div class="row gx-2">
        @php
          $quickMenus = [
              [
                  'route' => 'admin.events.edit',
                  'icon' => 'bx-edit-alt',
                  'label' => 'Edit Event',
                  'desc' => 'Ubah detail & timer',
              ],
              ['route' => 'admin.groups.index', 'icon' => 'bx-group', 'label' => 'Kelola Grup', 'desc' => 'Group'],
              [
                  'route' => 'admin.mentors.index',
                  'icon' => 'bx-user-voice',
                  'label' => 'Mentor',
                  'desc' => 'Kelola Mentor',
              ],
              [
                  'route' => 'admin.investors.index',
                  'icon' => 'bx-dollar',
                  'label' => 'Investor',
                  'desc' => 'Kelola Investor',
              ],
              [
                  'route' => 'admin.challenges.index',
                  'icon' => 'bx-target-lock',
                  'label' => 'Challenge',
                  'desc' => 'Kelola Challenge',
              ],
              ['route' => 'admin.cases.index', 'icon' => 'bx-book', 'label' => 'Case', 'desc' => 'Kelola Case'],
          ];
        @endphp

        @foreach ($quickMenus as $menu)
          <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route($menu['route'], $event->id) }}" class="text-decoration-none">
              <div class="card h-100 info-card">
                <div class="card-body d-flex align-items-center">
                  <i class="bx {{ $menu['icon'] }} fs-2 me-3"></i>
                  <div>
                    <div class="small text-muted">{{ $menu['label'] }}</div>
                    <div class="fw-bold">{{ $menu['desc'] }}</div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-12 col-xl-4">

      {{-- TIMER COUNTDOWN --}}
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Timer Event</h5>

          @php
            $countdownTime = null;
            $phaseLabel = null;

            foreach ($phases as $label => $times) {
                if ($times['end'] && now() < $times['end']) {
                    $countdownTime = $times['end'];
                    $phaseLabel = $label;
                    break;
                }
            }
          @endphp

          @if ($countdownTime)
            <p class="mb-1"><i class="bx bx-time me-2"></i>{{ $phaseLabel }} berakhir dalam:</p>
            <h4 id="event-countdown"
              data-countdown="{{ $countdownTime->setTimezone('Asia/Makassar')->toIso8601String() }}">--</h4>
          @else
            <p class="text-muted">Event sudah selesai</p>
          @endif

        </div>
      </div>

      {{-- LEADERBOARD --}}
      @if (isset($leaderboardGroups) && count($leaderboardGroups) > 0)
        <div class="card leader-card">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="bx bx-trophy me-1 text-warning"></i>Leaderboard Kelompok</h5>
            @foreach ($leaderboardGroups as $group)
              <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div>
                  <strong>{{ $group->name }}</strong>
                  <div class="text-muted small">{{ $group->members_count }} anggota</div>
                </div>
                <div class="fw-bold text-success">
                  Rp {{ number_format($group->squid_dollar, 0, ',', '.') }}
                </div>
              </div>
            @endforeach
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
            countdownEl.innerHTML = "Waktu Habis!";
            clearInterval(interval);
            return;
        }

        // Hitung total jam, termasuk hari
        const totalHours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.innerHTML = `${totalHours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    const interval = setInterval(updateCountdown, 1000);
});
</script>
@endpush

