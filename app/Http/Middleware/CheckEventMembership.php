<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;
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
        /** @var User|null $user */
        $user = Auth::user();
        $event = null;

        // --- 1. DETEKSI EVENT CONTEXT ---

        // A. Cek URL (Prioritas Utama)
        // Contoh: /mentor/events/{id}/dashboard
        $routeEvent = $request->route('event');
        if ($routeEvent instanceof Event) {
            $event = $routeEvent;
        } elseif (is_string($routeEvent) || is_numeric($routeEvent)) {
            $event = Event::find($routeEvent);
        }

        // B. Jika URL kosong, Cek History User (Prioritas Kedua)
        if (!$event && $user) {
            if ($user->role === 'mentor') {
                // Ambil event terakhir yang dia mentori
                $event = $user->mentorEvents()->latest()->first();
            } elseif ($user->role === 'user') {
                // Ambil event dari keanggotaan tim terakhir
                // [FIX]: Menggunakan getAuthIdentifier() untuk menghindari error "Undefined property id"
                $lastMember = GroupMember::where('user_id', $user->getAuthIdentifier())->latest()->first();
                if ($lastMember) {
                    $event = $lastMember->event;
                }
            }
        }

        // C. Jika masih null, Cek Event Aktif/Upcoming (Prioritas Ketiga - Default System)
        if (!$event) {
            // [FIX]: Menggunakan 'is_active' karena kolom 'status' tidak ada di database
            $event = Event::where('is_active', true)->first();

            if (!$event) {
                // Jika tidak ada yang aktif, cari yang akan datang (upcoming) berdasarkan tanggal
                // Asumsi ada kolom 'start_date' atau 'event_date'
                $event = Event::where('start_date', '>=', now())
                              ->orderBy('start_date', 'asc')
                              ->first();
            }
        }

        // Jika Event tetap tidak ditemukan di seluruh sistem, biarkan request lewat (handle 404 nanti)
        if (!$event) {
            return $next($request);
        }

        // --- 2. VALIDASI BERDASARKAN ROLE (LOGIC CABANG) ---

        // === SKENARIO MENTOR (STRICT) ===
        // Mentor harus sudah di-assign oleh Admin. Kalau tidak -> ERROR 403.
        if ($user && $user->role === 'mentor') {
            if (!$user->mentorEvents()->where('event_id', $event->id)->exists()) {
                abort(403, 'Akses Ditolak: Anda tidak terdaftar sebagai Mentor di Event ini.');
            }
            return $next($request);
        }

        // === SKENARIO USER / PESERTA (FLEXIBLE / ONBOARDING) ===
        // User bebas masuk untuk mendaftar.
        if ($user && $user->role === 'user') {
            $hasGroup = GroupMember::where('user_id', $user->getAuthIdentifier())
                ->where('event_id', $event->id)
                ->exists();

            // KASUS A: Belum Punya Tim (User Baru / Belum Join)
            if (!$hasGroup) {
                // Izinkan akses ke route onboarding/join agar bisa mendaftar
                if ($request->routeIs('main.onboarding.*') ||
                    $request->routeIs('main.event.join') ||
                    $request->routeIs('logout')) {
                    return $next($request);
                }

                // Jika mencoba akses dashboard tapi belum punya tim -> Arahkan ke pendaftaran
                return redirect()->route('main.onboarding.index');
            }

            // KASUS B: Sudah Punya Tim
            if ($hasGroup) {
                // Redirect user yang sudah punya tim jika mencoba masuk ke halaman pendaftaran lagi
                if ($request->routeIs('main.onboarding.*') || $request->routeIs('main.event.join')) {
                    return redirect()->route('main.dashboard');
                }
            }
        }

        // Untuk role lain (Admin/Investor), loloskan saja
        return $next($request);
    }
}
