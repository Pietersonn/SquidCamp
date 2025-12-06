{{-- 1. BACKDROP & MENU OVERLAY (Tampilan Menu Tengah) --}}
<div class="menu-backdrop" id="menuBackdrop" onclick="toggleMenu()"></div>

<div class="menu-overlay" id="menuOverlay">
    <div class="menu-overlay-header">
        <div class="menu-handle"></div>
        <h6 class="fw-bold m-0 text-dark">Mentor Command Center</h6>
    </div>

    <div class="phase-menu-grid">
        {{-- Menu 1: Approvals (Dashboard) --}}
        <a href="{{ route('mentor.dashboard', $event->id) }}" class="phase-item">
            <i class='bx bx-check-square phase-icon'></i>
            <span class="phase-title">Approvals</span>
            <small class="d-block text-muted" style="font-size: 10px;">Cek Tugas</small>
        </a>

        {{-- Menu 2: My Teams --}}
        <a href="{{ route('mentor.groups.index', $event->id) }}" class="phase-item">
            <i class='bx bx-group phase-icon'></i>
            <span class="phase-title">My Teams</span>
            <small class="d-block text-muted" style="font-size: 10px;">Monitor Tim</small>
        </a>

        {{-- Menu 3: Vote (Placeholder) --}}
        <a href="#" class="phase-item">
            <i class='bx bx-poll phase-icon'></i>
            <span class="phase-title">Vote</span>
            <small class="d-block text-muted" style="font-size: 10px;">Voting Session</small>
        </a>
    </div>
</div>

{{-- 2. NAVBAR UTAMA (Floating Bottom) --}}
<nav class="squid-navbar">

    {{-- KIRI: Review & Teams --}}
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

    {{-- TENGAH: Tombol Menu Bulat --}}
    <div class="center-menu-btn" onclick="toggleMenu()">
        <i class='bx bx-grid-alt'></i>
    </div>

    {{-- KANAN: Vote & Keluar --}}
    <div class="nav-section">
        {{-- Vote --}}
        <a href="#" class="nav-link-item">
            <i class='bx bx-poll'></i>
            <span>Vote</span>
        </a>

        {{-- Logout --}}
        <a href="javascript:void(0)" onclick="confirmLogoutNav()" class="nav-link-item">
            <i class='bx bx-exit'></i>
            <span class="text">Keluar</span>
        </a>
    </div>

</nav>

{{-- 3. JAVASCRIPT LOGIC --}}
@push('scripts')
<script>
    // Logic Toggle Menu Tengah
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

    // Logic Konfirmasi Logout
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
            reverseButtons: true, // Tombol konfirmasi di kanan (opsional, biar mirip UI mobile)
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form-nav-bot').submit();
            }
        })
    }
</script>

{{-- Form Logout Hidden --}}
<form id="logout-form-nav-bot" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
