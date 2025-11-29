{{-- 1. BACKDROP GELAP (Muncul saat menu dibuka) --}}
<div class="menu-backdrop" id="menuBackdrop" onclick="toggleMenu()"></div>

{{-- 2. MENU OVERLAY (Isi Menu 3 Fase) --}}
<div class="menu-overlay" id="menuOverlay">
    <div class="menu-overlay-header">
        <div class="menu-handle"></div>
        <h6 class="fw-bold m-0 text-dark">Game Menu</h6>
    </div>

    <div class="phase-menu-grid">
        {{-- PHASE 1: CHALLENGE --}}
        <a href="#" class="phase-item">
            <i class='bx bx-joystick phase-icon'></i>
            <span class="phase-title">Challenges</span>
            <small class="d-block text-muted" style="font-size: 10px;">Misi Harian</small>
        </a>

        {{-- PHASE 2: CASE (Placeholder) --}}
        <a href="#" class="phase-item">
            <i class='bx bx-briefcase-alt-2 phase-icon'></i>
            <span class="phase-title">Cases</span>
            <small class="d-block text-muted" style="font-size: 10px;">Studi Kasus</small>
        </a>

        {{-- PHASE 3: SHOW (Placeholder) --}}
        <a href="#" class="phase-item">
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
        {{-- Home --}}
        <a href="{{ route('main.dashboard') }}"
           class="nav-link-item {{ request()->routeIs('main.dashboard') ? 'active' : '' }}">
            <i class='bx {{ request()->routeIs('main.dashboard') ? 'bxs-home-smile' : 'bx-home-smile' }}'></i>
            <span>Home</span>
        </a>

        {{-- Leaderboard (Ganti dari Lomba) --}}
        <a href="#"
           class="nav-link-item">
            <i class='bx bx-bar-chart-alt-2'></i>
            <span>Rank</span>
        </a>
    </div>

    {{-- TOMBOL TENGAH (CENTER BUTTON) --}}
    <div class="center-menu-btn" onclick="toggleMenu()">
        <i class='bx bx-grid-alt'></i> {{-- Icon Menu --}}
    </div>

    {{-- BAGIAN KANAN --}}
    <div class="nav-section">
        {{-- Riwayat (History) --}}
        <a href="#" class="nav-link-item">
            <i class='bx bx-history'></i>
            <span>Riwayat</span>
        </a>

        {{-- Akun --}}
        <a href="javascript:void(0)" onclick="confirmLogout()" class="nav-link-item">
            <i class='bx bx-user-circle'></i>
            <span>Akun</span>
        </a>
    </div>

</nav>

@push('scripts')
<script>
    // Logic untuk Buka/Tutup Menu Tengah
    function toggleMenu() {
        const overlay = document.getElementById('menuOverlay');
        const backdrop = document.getElementById('menuBackdrop');
        const btnIcon = document.querySelector('.center-menu-btn i');

        if (overlay.classList.contains('show')) {
            // Tutup Menu
            overlay.classList.remove('show');
            backdrop.classList.remove('show');
            btnIcon.classList.replace('bx-x', 'bx-grid-alt'); // Ganti icon balik ke grid
        } else {
            // Buka Menu
            overlay.classList.add('show');
            backdrop.classList.add('show');
            btnIcon.classList.replace('bx-grid-alt', 'bx-x'); // Ganti icon jadi silang (X)
        }
    }

    // Logic Logout (Sama seperti sebelumnya)
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
