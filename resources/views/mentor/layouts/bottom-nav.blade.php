<style>
    /* --- SQUID NAVBAR STYLES --- */
    .squid-navbar {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 400px;
        height: 65px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
        z-index: 1040;
    }

    .nav-section {
        display: flex;
        gap: 15px;
        flex: 1;
        justify-content: space-around;
    }

    .nav-link-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #a1a1a1;
        font-size: 0.7rem;
        transition: color 0.3s;
        border: none;
        background: transparent;
    }

    .nav-link-item i {
        font-size: 1.4rem;
        margin-bottom: 2px;
    }

    .nav-link-item.active {
        color: #00a79d;
        font-weight: 700;
    }

    /* Center Button Floating */
    .center-menu-btn {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #00a79d, #008f87);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.4);
        margin-top: -35px;
        /* Float effect */
        cursor: pointer;
        transition: transform 0.2s;
        z-index: 1050;
    }

    .center-menu-btn:active {
        transform: scale(0.95);
    }

    /* Menu Overlay */
    .menu-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1045;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    .menu-backdrop.show {
        opacity: 1;
        pointer-events: auto;
    }

    .menu-overlay {
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        background: white;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 20px;
        z-index: 1050;
        transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding-bottom: 100px;
        /* Space for navbar */
    }

    .menu-overlay.show {
        bottom: 0;
    }

    .menu-overlay-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .menu-handle {
        width: 40px;
        height: 5px;
        background: #e0e0e0;
        border-radius: 10px;
        margin: 0 auto 15px auto;
    }

    .phase-menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .phase-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 15px 5px;
        text-align: center;
        text-decoration: none;
        color: #555;
        transition: background 0.2s;
        display: block;
    }

    .phase-item:active {
        background: #eee;
    }

    .phase-icon {
        font-size: 1.8rem;
        color: #00a79d;
        margin-bottom: 5px;
        display: block;
    }

    .phase-title {
        font-weight: 700;
        font-size: 0.8rem;
        display: block;
        color: #333;
    }
</style>

{{-- 1. BACKDROP GELAP --}}
<div class="menu-backdrop" id="menuBackdrop" onclick="toggleMenu()"></div>

{{-- 2. MENU OVERLAY (Isi Menu Tengah) --}}
<div class="menu-overlay" id="menuOverlay">
    <div class="menu-overlay-header">
        <div class="menu-handle"></div>
        <h6 class="fw-bold m-0 text-dark">Mentor Command Center</h6>
    </div>

    <div class="phase-menu-grid">
        {{-- MENU 1: DASHBOARD --}}
        {{-- FIX: Tambahkan parameter event ID --}}
        <a href="{{ route('mentor.dashboard', $event->id) }}" class="phase-item">
            <i class='bx bx-check-square phase-icon'></i>
            <span class="phase-title">Approvals</span>
            <small class="d-block text-muted" style="font-size: 10px;">Cek Tugas</small>
        </a>

        {{-- MENU 2: TEAMS --}}
        {{-- FIX: Tambahkan parameter event ID --}}
        <a href="{{ route('mentor.groups.index', $event->id) }}" class="phase-item">
            <i class='bx bx-group phase-icon'></i>
            <span class="phase-title">My Teams</span>
            <small class="d-block text-muted" style="font-size: 10px;">Monitor Kelompok</small>
        </a>

        {{-- MENU 3: VOTE (Sebelumnya History) --}}
        <a href="#" class="phase-item">
            <i class='bx bx-poll phase-icon'></i>
            <span class="phase-title">Vote</span>
            <small class="d-block text-muted" style="font-size: 10px;">Voting (Soon)</small>
        </a>
    </div>

    <div class="mt-4 px-2">
        <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block">Login sebagai</small>
                <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
            </div>
            {{-- Tombol Logout di Menu Overlay --}}
            <button onclick="confirmLogoutNav()" class="btn btn-sm btn-danger rounded-pill px-3">
                <i class='bx bx-log-out'></i> Logout
            </button>
        </div>
    </div>
</div>

{{-- 3. NAVBAR UTAMA --}}
<nav class="squid-navbar">

    {{-- BAGIAN KIRI --}}
    <div class="nav-section">
        {{-- Home / Review --}}
        <a href="{{ route('mentor.dashboard', $event->id) }}"
            class="nav-link-item {{ Route::is('mentor.dashboard') ? 'active' : '' }}">
            <i class='bx {{ Route::is('mentor.dashboard') ? 'bxs-home-smile' : 'bx-home-smile' }}'></i>
            <span>Review</span>
        </a>

        {{-- Teams --}}
        <a href="{{ route('mentor.groups.index', $event->id) }}"
            class="nav-link-item {{ Route::is('mentor.groups.*') ? 'active' : '' }}">
            <i class='bx {{ Route::is('mentor.groups.*') ? 'bxs-group' : 'bx-group' }}'></i>
            <span>Teams</span>
        </a>
    </div>

    {{-- TOMBOL TENGAH (CENTER BUTTON) --}}
    <div class="center-menu-btn" onclick="toggleMenu()">
        <i class='bx bx-grid-alt'></i>
    </div>

    {{-- BAGIAN KANAN --}}
    <div class="nav-section">
        {{-- Vote (Ganti History) --}}
        <a href="#" class="nav-link-item">
            <i class='bx bx-poll'></i>
            <span>Vote</span>
        </a>

        {{-- Akun (Jadi Tombol Keluar) --}}
        <a href="javascript:void(0)" onclick="confirmLogoutNav()" class="nav-link-item">
            <i class='bx bx-log-out-circle text-danger'></i>
            <span class="text-danger">Keluar</span>
        </a>
    </div>

</nav>

{{-- SCRIPTS KHUSUS NAV --}}
<script>
    // Logic Buka/Tutup Menu Tengah
    function toggleMenu() {
        const overlay = document.getElementById('menuOverlay');
        const backdrop = document.getElementById('menuBackdrop');
        const btnIcon = document.querySelector('.center-menu-btn i');

        if (overlay.classList.contains('show')) {
            // Tutup
            overlay.classList.remove('show');
            backdrop.classList.remove('show');
            btnIcon.classList.replace('bx-x', 'bx-grid-alt');
        } else {
            // Buka
            overlay.classList.add('show');
            backdrop.classList.add('show');
            btnIcon.classList.replace('bx-grid-alt', 'bx-x');
        }
    }

    // Logic Logout
    function confirmLogoutNav() {
        Swal.fire({
            title: 'Keluar?',
            text: "Anda akan keluar dari sesi mentor.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00a79d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form-nav-bot').submit();
            }
        })
    }
</script>

{{-- Form Logout Tersembunyi --}}
<form id="logout-form-nav-bot" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
