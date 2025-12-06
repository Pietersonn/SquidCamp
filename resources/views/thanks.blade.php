<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Awarding Night - {{ $event->name ?? 'SquidCamp' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- RESET & LAYOUT --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Mencegah scroll samping */
        }

        body {
            background-color: #ffffff;
            font-family: 'Public Sans', sans-serif;
            color: #333;

            /* TEKNIK CENTER PALING AMPUH */
            display: grid;
            place-items: center;
            padding: 20px; /* Jarak aman di HP */
            box-sizing: border-box;
        }

        /* --- DEKORASI BACKGROUND --- */
        .shape {
            position: absolute;
            opacity: 0.05;
            z-index: -1; /* Di belakang kartu */
            color: #00a79d;
            pointer-events: none;
        }
        .shape-circle { top: 5%; left: -10%; font-size: 15rem; }
        .shape-triangle { bottom: 5%; right: -10%; font-size: 15rem; }
        .shape-square { top: 40%; right: 5%; font-size: 8rem; opacity: 0.03; transform: rotate(15deg); }

        /* --- KARTU UTAMA --- */
        .thanks-card {
            background: #ffffff;
            border-radius: 30px;
            padding: 40px 30px;
            width: 100%;
            max-width: 480px; /* Batas lebar di layar besar */
            text-align: center;
            position: relative;
            z-index: 10;

            /* Shadow Halus & Elegan */
            box-shadow: 0 25px 50px -12px rgba(0, 167, 157, 0.25);
            border: 1px solid #f0f0f0;

            /* Animasi Muncul */
            animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* --- ELEMEN KARTU --- */
        .app-logo {
            width: 70px;
            margin-bottom: 25px;
        }

        .award-label {
            font-size: 0.7rem;
            font-weight: 800;
            color: #00a79d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
            display: block;
        }

        .award-title {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
            background: -webkit-linear-gradient(135deg, #00a79d, #00796b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .message-text {
            color: #697a8d;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 30px;
            font-style: italic;
        }

        /* --- TEAM BOX --- */
        .team-section {
            background: #f0fdfa; /* Hijau pudar */
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 25px;
            border: 1px dashed #99f6e4;
        }

        .team-name {
            font-weight: 800;
            color: #134e4a;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        /* --- AVATAR --- */
        .avatar-group {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            padding-left: 10px; /* Kompensasi margin overlap */
        }

        .avatar-wrapper {
            margin-left: -12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .avatar-wrapper:hover {
            transform: translateY(-5px) scale(1.1);
            z-index: 20;
        }

        .avatar-circle {
            width: 45px; height: 45px;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1rem;
            background: linear-gradient(135deg, #00a79d, #00796b);
            object-fit: cover;
        }

        .avatar-img {
            background-color: #fff; /* Fallback jika transparan */
        }

        /* --- STATS --- */
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #1f2937;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 10px 20px rgba(31, 41, 55, 0.15);
            margin-bottom: 30px;
        }

        /* --- BUTTON --- */
        .btn-home {
            background: transparent;
            color: #00a79d;
            border: 2px solid #00a79d;
            border-radius: 50px;
            padding: 12px 0;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background: #00a79d;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 167, 157, 0.3);
            transform: translateY(-2px);
        }

        /* RESPONSIVE FONT SIZE UNTUK LAYAR KECIL */
        @media (max-width: 400px) {
            .award-title { font-size: 1.5rem; }
            .thanks-card { padding: 30px 20px; }
        }
    </style>
</head>
<body>

    {{-- DEKORASI --}}
    <i class='bx bx-circle shape shape-circle'></i>
    <i class='bx bx-play shape shape-triangle'></i>
    <i class='bx bx-stop shape shape-square'></i>

    <div class="thanks-card">

        {{-- LOGO --}}
        <img src="{{ asset('assets/img/logo/logo-squidcamp.png') }}" alt="Logo" class="app-logo">

        {{-- JUDUL PENGHARGAAN --}}
        <span class="award-label">CONGRATULATIONS</span>
        <h1 class="award-title">{{ $myAward }}</h1>

        {{-- PESAN --}}
        <p class="message-text">
            "Terima kasih atas dedikasi dan kerja keras kalian. Perjalanan ini luar biasa karena semangat kalian yang tak padam!"
        </p>

        {{-- INFO TIM --}}
        <div class="team-section">
            <div class="team-name">{{ $myGroup->name ?? 'Unknown Team' }}</div>

            <div class="avatar-group">
                @if(isset($myGroup) && $myGroup->members)
                    @foreach($myGroup->members as $member)
                        <div class="avatar-wrapper" data-bs-toggle="tooltip" title="{{ $member->user->name }}">
                            @if($member->user->avatar)
                                {{-- FOTO --}}
                                <img src="{{ asset('storage/'.$member->user->avatar) }}"
                                     alt="{{ $member->user->name }}"
                                     class="avatar-circle avatar-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                {{-- FALLBACK INISIAL (Jika error load) --}}
                                <div class="avatar-circle" style="display: none;">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </div>
                            @else
                                {{-- INISIAL (Jika tidak ada foto) --}}
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- TOTAL ASET --}}
        <div class="stat-badge">
            <i class='bx bx-coin-stack fs-5'></i>
            <span>Total Aset: ${{ number_format($myGroup->squid_dollar + $myGroup->bank_balance) }}</span>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <a href="{{ route('landing') }}" class="btn btn-home">
            <i class='bx bx-arrow-back me-2'></i> Kembali ke Beranda
        </a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Init Tooltips Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html>
