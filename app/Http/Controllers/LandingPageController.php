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
        // 1. Cek User Role (Redirect Admin/Mentor ke Dashboard)
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role !== 'user') {
                 $dashboardRoute = match ($user->role) {
                    'admin'    => 'admin.dashboard',
                    'mentor'   => 'mentor.dashboard',
                    'investor' => 'investor.dashboard',
                    default    => 'landing',
                };

                if ($dashboardRoute !== 'landing') {
                    return redirect()->route($dashboardRoute);
                }
            }
        }

        // 2. LOGIKA FILTER EVENT
        // Ambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        $events = Event::whereDate('event_date', '>=', $today) // Hanya Hari Ini atau Masa Depan
                        ->where('is_finished', false)          // [BARU] Sembunyikan yang sudah Finish
                        ->orderBy('event_date', 'asc')
                        ->get();

        return view('index', compact('events'));
    }
}
