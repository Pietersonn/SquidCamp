<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\GroupMember;
use Symfony\Component\HttpFoundation\Response;

class CheckEventMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Cari Event yang sedang Aktif
        $activeEvent = Event::where('is_active', 1)->first();

        // Jika tidak ada event aktif, biarkan request lanjut (atau bisa redirect ke halaman 'No Event')
        if (!$activeEvent) {
            return $next($request);
        }

        // 2. Cek apakah user sudah terdaftar di salah satu grup pada event ini
        $hasGroup = GroupMember::where('user_id', $user->id)
            ->where('event_id', $activeEvent->id)
            ->exists();

        // --- SKENARIO A: User BELUM punya kelompok ---
        // Aksi: Wajib ke Onboarding, tidak boleh akses halaman lain di 'main.*'
        if (!$hasGroup) {
            // Jika rute yang diakses BUKAN onboarding, paksa redirect ke onboarding
            if (!$request->routeIs('main.onboarding.*')) {
                return redirect()->route('main.onboarding.index');
            }
        }

        // --- SKENARIO B: User SUDAH punya kelompok ---
        // Aksi: Tidak boleh masuk ke Onboarding lagi (biar gak ganti-ganti atau double)
        if ($hasGroup) {
            // Jika rute yang diakses ADALAH onboarding, lempar balik ke dashboard
            if ($request->routeIs('main.onboarding.*')) {
                return redirect()->route('main.dashboard');
            }
        }

        return $next($request);
    }
}
