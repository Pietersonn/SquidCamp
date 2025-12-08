{{-- FETCH DATA EVENT (Agar bisa diakses global di nav) --}}
@php
  // Ambil event aktif
  $activeEventNav = \App\Models\Event::where('is_active', true)->first();

  // 1. Waktu Challenge
  $cStart = $activeEventNav ? $activeEventNav->challenge_start_time : null;
  $cEnd   = $activeEventNav ? $activeEventNav->challenge_end_time : null;

  // 2. Waktu Case (Asumsi nama kolom DB)
  $caseStart = $activeEventNav ? $activeEventNav->case_start_time : null;
  $caseEnd   = $activeEventNav ? $activeEventNav->case_end_time : null;

  // 3. Waktu Show (Asumsi nama kolom DB)
  $showStart = $activeEventNav ? $activeEventNav->show_start_time : null;
  $showEnd   = $activeEventNav ? $activeEventNav->show_end_time : null;

  // Konversi ke Timestamp Javascript (Milliseconds)
  $jsCStart = $cStart ? $cStart->valueOf() : 0;
  $jsCEnd   = $cEnd ? $cEnd->valueOf() : 0;

  $jsCaseStart = $caseStart ? $caseStart->valueOf() : 0;
  $jsCaseEnd   = $caseEnd ? $caseEnd->valueOf() : 0;

  $jsShowStart = $showStart ? $showStart->valueOf() : 0;
  $jsShowEnd   = $showEnd ? $showEnd->valueOf() : 0;
@endphp

{{-- 1. BACKDROP GELAP --}}
<div class="menu-backdrop" id="menuBackdrop" onclick="toggleMenu()"></div>

{{-- 2. MENU OVERLAY --}}
<div class="menu-overlay" id="menuOverlay">
  <div class="menu-overlay-header">
    <div class="menu-handle"></div>
    <h6 class="fw-bold m-0 text-dark">Game Menu</h6>
  </div>

  <div class="phase-menu-grid">
    {{-- PHASE 1: CHALLENGE --}}
    <a href="{{ route('main.challenges.index') }}" class="phase-item" onclick="checkAccess(event, 'challenge')">
      <i class='bx bx-joystick phase-icon'></i>
      <span class="phase-title">Challenges</span>
      <small class="d-block text-muted" style="font-size: 10px;">Misi Harian</small>
    </a>

    {{-- PHASE 2: CASE --}}
    <a href="{{ route('main.cases.index') }}" class="phase-item" onclick="checkAccess(event, 'case')">
      <i class='bx bx-briefcase-alt-2 phase-icon'></i>
      <span class="phase-title">Cases</span>
      <small class="d-block text-muted" style="font-size: 10px;">Studi Kasus</small>
    </a>

    {{-- PHASE 3: SHOW --}}
    <a href="{{ route('main.investments.index') }}" class="phase-item" onclick="checkAccess(event, 'show')">
      <i class='bx bx-microphone phase-icon'></i>
      <span class="phase-title">Show</span>
      <small class="d-block text-muted" style="font-size: 10px;">Presentasi</small>
    </a>
  </div>
</div>

{{-- 3. NAVBAR UTAMA --}}
<nav class="squid-navbar">

  {{-- BAGIAN KIRI --}}
  <div class="nav-section">
    <a href="{{ route('main.dashboard') }}"
      class="nav-link-item {{ request()->routeIs('main.dashboard') ? 'active' : '' }}">
      <i class='bx {{ request()->routeIs('main.dashboard') ? 'bxs-home-smile' : 'bx-home-smile' }}'></i>
      <span>Home</span>
    </a>

    <a href="{{ route('main.leaderboard.index') }}"
      class="nav-link-item {{ request()->routeIs('main.leaderboard.*') ? 'active' : '' }}">
      <i class='bx bx-bar-chart-alt-2'></i>
      <span>Leaderboard</span>
    </a>
  </div>

  {{-- TOMBOL TENGAH --}}
  <div class="center-menu-btn" onclick="toggleMenu()">
    <i class='bx bx-grid-alt'></i>
  </div>

  {{-- BAGIAN KANAN --}}
  <div class="nav-section">
    <a href="{{ route('main.group.index') }}"
      class="nav-link-item {{ request()->routeIs('main.group.*') ? 'active' : '' }}">
      <i class='bx bx-group'></i>
      <span>Tim</span>
    </a>

    <a href="javascript:void(0)" onclick="confirmLogout()" class="nav-link-item">
      <i class='bx bx-exit'></i>
      <span>Keluar</span>
    </a>
  </div>

</nav>

@push('scripts')
  <script>
    // --- Logic Validasi Waktu Universal (Challenge, Case, Show) ---
    function checkAccess(e, type) {
      let startTime = 0;
      let endTime = 0;
      let labelName = '';

      // Tentukan waktu berdasarkan tipe menu yang diklik
      if (type === 'challenge') {
          startTime = {{ $jsCStart }};
          endTime = {{ $jsCEnd }};
          labelName = 'Challenge';
      } else if (type === 'case') {
          startTime = {{ $jsCaseStart }};
          endTime = {{ $jsCaseEnd }};
          labelName = 'Case Study';
      } else if (type === 'show') {
          startTime = {{ $jsShowStart }};
          endTime = {{ $jsShowEnd }};
          labelName = 'Show Session';
      }

      // Waktu sekarang di browser user
      const now = new Date().getTime();

      // 1. Cek jika jadwal belum di set sama sekali
      if (startTime === 0 || endTime === 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Belum Tersedia',
          text: `Jadwal ${labelName} belum ditentukan oleh panitia.`,
          confirmButtonColor: '#00a79d',
          customClass: { popup: 'rounded-4' }
        });
        return;
      }

      // 2. Cek jika BELUM mulai (Too Early)
      if (now < startTime) {
        e.preventDefault();

        const startDate = new Date(startTime);
        const hours = String(startDate.getHours()).padStart(2, '0');
        const minutes = String(startDate.getMinutes()).padStart(2, '0');

        Swal.fire({
          icon: 'info',
          title: 'Belum Dibuka',
          text: `Sabar ya! ${labelName} baru akan dibuka pukul ${hours}:${minutes}`,
          confirmButtonColor: '#00a79d',
          customClass: { popup: 'rounded-4' }
        });
        return;
      }

      // 3. Cek jika SUDAH lewat (Too Late)
      if (now > endTime) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Ditutup',
          text: `Yah, sesi ${labelName} sudah berakhir.`,
          confirmButtonColor: '#d33',
          customClass: { popup: 'rounded-4' }
        });
        return;
      }

      // 4. Jika lolos, biarkan masuk
    }

    // Logic untuk Buka/Tutup Menu Tengah
    function toggleMenu() {
      const overlay = document.getElementById('menuOverlay');
      const backdrop = document.getElementById('menuBackdrop');
      const btnIcon = document.querySelector('.center-menu-btn i');

      if (overlay.classList.contains('show')) {
        overlay.classList.remove('show');
        backdrop.classList.remove('show');
        btnIcon.classList.replace('bx-x', 'bx-grid-alt');
      } else {
        overlay.classList.add('show');
        backdrop.classList.add('show');
        btnIcon.classList.replace('bx-grid-alt', 'bx-x');
      }
    }

    // Logic Logout
    function confirmLogout() {
      Swal.fire({
        title: 'Keluar?',
        text: "Sesi anda akan diakhiri.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00a79d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: { popup: 'rounded-4' }
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logout-form-nav').submit();
        }
      })
    }
  </script>

  <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
