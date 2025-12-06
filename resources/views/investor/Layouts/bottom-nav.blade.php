<style>
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #ffffff;
        box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-around;
        padding: 12px 0;
        z-index: 1050;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .nav-item {
        text-align: center;
        color: #a1acb8;
        text-decoration: none;
        position: relative;
        transition: all 0.3s ease;
        flex: 1;
    }

    .nav-item i {
        font-size: 1.6rem;
        display: block;
        margin-bottom: 2px;
        transition: transform 0.2s;
    }

    .nav-item span {
        font-size: 0.7rem;
        font-weight: 600;
        display: block;
    }

    /* Active State (Squid Teal) */
    .nav-item.active {
        color: #00a79d;
    }

    .nav-item.active i {
        transform: translateY(-3px);
    }

    .nav-item.active::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 4px;
        background: #00a79d;
        border-radius: 10px 10px 0 0;
    }

    /* Center Button Effect (Optional for Voting) */
    .nav-item-highlight {
        color: #ffab00 !important; /* Gold for voting */
    }
</style>

<div class="bottom-nav">
    {{-- 1. HOME / DASHBOARD --}}
    <a href="{{ isset($event) ? route('investor.dashboard', $event->id) : '#' }}"
       class="nav-item {{ Route::is('investor.dashboard') ? 'active' : '' }}">
        <i class='bx bxs-home-circle'></i>
        <span>Home</span>
    </a>

    {{-- 2. VOTING (Link Pagar) --}}
    {{-- Kita kasih warna sedikit beda biar stand out, atau active standard --}}
    <a href="#" class="nav-item">
        <i class='bx bxs-bar-chart-alt-2'></i>
        <span>Voting</span>
    </a>

    {{-- 3. PROFILE / LOGOUT (Simpel aja buat mobile) --}}
    <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
        <i class='bx bx-log-out-circle'></i>
        <span>Logout</span>
    </a>

    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
