<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Finished - {{ $event->name ?? 'SquidCamp' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            /* Background Gradient Mewah */
            background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Public Sans', sans-serif;
            text-align: center;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Kartu Glassmorphism */
        .thanks-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
            animation: popUp 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        @keyframes popUp {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Peringkat Besar */
        .rank-badge {
            font-size: 3rem;
            font-weight: 800;
            background: white;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin-bottom: 5px;
            display: block;
            line-height: 1;
        }

        .group-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        /* Kotak Statistik */
        .stats-box {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 16px;
            padding: 15px;
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .stat-item h6 { font-size: 0.75rem; opacity: 0.8; margin-bottom: 0; text-transform: uppercase; }
        .stat-item p { font-size: 1.2rem; font-weight: bold; margin-bottom: 0; color: #ccfbf1; }

        /* Avatar Anggota Tim */
        .avatar-group { display: flex; justify-content: center; margin-top: 10px; }
        .avatar-group img {
            width: 45px; height: 45px;
            border-radius: 50%;
            border: 2px solid white;
            margin-left: -12px;
            object-fit: cover;
            transition: 0.3s;
        }
        .avatar-group img:first-child { margin-left: 0; }
        .avatar-group img:hover { transform: translateY(-3px); z-index: 3; }

        /* Efek Confetti (Background SVG simple) */
        .bg-pattern {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.2) 2px, transparent 2px);
            background-size: 30px 30px;
            opacity: 0.3;
            z-index: 1;
        }
    </style>
</head>
<body>

    <div class="bg-pattern"></div>

    <div class="thanks-card">

        {{-- Logika Ikon Berdasarkan Ranking --}}
        @if(isset($rank) && $rank == 1)
            <div class="mb-3">
                {{-- Mahkota Emas --}}
                <i class='bx bxs-crown text-warning' style="font-size: 5rem; filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.6));"></i>
            </div>
        @elseif(isset($rank) && $rank <= 3)
            <div class="mb-3">
                {{-- Medali / Trophy --}}
                <i class='bx bxs-trophy text-warning' style="font-size: 5rem;"></i>
            </div>
        @else
            <div class="mb-3">
                {{-- Party Popper --}}
                <i class='bx bxs-party text-white' style="font-size: 5rem; opacity: 0.9;"></i>
            </div>
        @endif

        {{-- Pesan Utama --}}
        <h2 class="fw-bold mb-1">Event Selesai!</h2>
        <p class="text-white-50 mb-4 px-3" style="font-size: 0.95rem;">
            {{ $message ?? 'Terima kasih telah berpartisipasi.' }}
        </p>

        <hr style="border-color: rgba(255,255,255,0.3);">

        {{-- Info Kelompok & Ranking --}}
        <div class="mb-4">
            <span class="badge bg-white text-primary rounded-pill mb-2 px-3">Official Result</span>

            <div class="rank-badge">RANK #{{ $rank ?? '-' }}</div>
            <div class="group-name">{{ $group->name ?? 'Nama Tim' }}</div>

            {{-- Foto Anggota (Avatar Group) --}}
            <div class="avatar-group">
                @if(isset($group) && $group->members)
                    @foreach($group->members as $member)
                        <img src="{{ $member->user->avatar ? asset('storage/'.$member->user->avatar) : asset('assets/img/avatars/1.png') }}"
                             alt="{{ $member->user->name }}"
                             title="{{ $member->user->name }}"
                             data-bs-toggle="tooltip">
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Statistik Harta (Uang Game + Bank) --}}
        <div class="stats-box">
            <div class="stat-item">
                <h6>Total Harta</h6>
                <p>
                    @if(isset($group))
                        ${{ number_format($group->squid_dollar + $group->bank_balance) }}
                    @else
                        $0
                    @endif
                </p>
            </div>
            <div class="stat-item">
                <h6>Status</h6>
                <p class="text-uppercase" style="color: #fff;">Finished</p>
            </div>
        </div>

        {{-- Tombol Keluar --}}
        <a href="{{ route('landing') }}" class="btn btn-light w-100 py-3 rounded-pill fw-bold text-primary shadow-sm mb-3">
            <i class='bx bx-home-alt me-1'></i> Kembali ke Beranda
        </a>

        <div class="small text-white-50">
            SquidCamp Season 1 &copy; {{ date('Y') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
