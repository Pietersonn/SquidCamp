<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        // 1. Cek User Role (Proteksi Halaman Landing)
        // Jika Admin, Mentor, atau Investor mencoba akses '/', lempar ke dashboard mereka.
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role !== 'user') {
                 $dashboardRoute = match ($user->role) {
                    'admin'    => 'admin.dashboard',
                    'mentor'   => 'mentor.dashboard',
                    'investor' => 'investor.dashboard',
                    default    => 'landing',
                };

                // Cegah redirect loop jika route-nya sama
                if ($dashboardRoute !== 'landing') {
                    return redirect()->route($dashboardRoute);
                }
            }
        }

        // 2. Logika Filter Event (Strict Date)
        // Ambil tanggal hari ini (Y-m-d)
        $today = Carbon::today()->toDateString();

        // Query: Hanya ambil event yang tanggalnya HARI INI atau MASA DEPAN.
        // Event kemarin (H-1) otomatis tidak terambil.
        $events = Event::whereDate('event_date', '>=', $today)
                        ->orderBy('event_date', 'asc')
                        ->get();

        return view('index', compact('events'));
    }
}
