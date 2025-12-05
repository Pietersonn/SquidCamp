<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User; // Import Model User
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

        // 1. Coba ambil Event dari URL (Route Model Binding)
        // Contoh: /mentor/events/{event}/dashboard
        $routeEvent = $request->route('event');

        if ($routeEvent instanceof Event) {
            $event = $routeEvent;
        } elseif (is_string($routeEvent) || is_numeric($routeEvent)) {
            $event = Event::find($routeEvent);
        }

        // 2. Jika tidak ada di URL, ambil dari history membership user terakhir
        if (!$event && $user) {
            // Cek apakah user adalah participant (group member)
            $lastMember = GroupMember::where('user_id', $user->id)->latest()->first();
            if ($lastMember) {
                $event = $lastMember->event;
            }
            // Jika user adalah Mentor, cek event terakhir yang dia handle (Opsional)
            elseif ($user->role === 'mentor') {
                $event = $user->mentorEvents()->latest()->first();
            }
        }

        // 3. Jika masih null, cari Event Aktif (Live) atau Upcoming terdekat
        if (!$event) {
            $event = Event::where('status', 'active')->first(); // Prioritas Live

            if (!$event) {
                $event = Event::where('status', 'upcoming')
                              ->orderBy('start_date', 'asc')
                              ->first();
            }
        }

        // Jika tetap tidak ada event di sistem, biarkan request lewat (handle di Controller atau 404 nanti)
        if (!$event) {
            return $next($request);
        }

        // Simpan Event ke Request agar bisa diakses controller tanpa query ulang (Opsional)
        // $request->merge(['current_event' => $event]);

        // --- KHUSUS MENTOR: VALIDASI AKSES ---
        if ($user && $user->role === 'mentor') {
            // Cek apakah mentor terdaftar di event yang sedang diakses
            if (!$user->mentorEvents()->where('event_id', $event->id)->exists()) {
                // Jika mencoba akses event yang bukan miliknya
                abort(403, 'Anda tidak memiliki akses sebagai Mentor di Event ini.');
            }
            return $next($request);
        }

        // --- KHUSUS PARTICIPANT: CEK ONBOARDING ---
        if ($user && $user->role === 'user') { // Asumsi role participant adalah 'user'
            $hasGroup = GroupMember::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->exists();

            // SKENARIO A: Belum punya kelompok -> Wajib Onboarding
            if (!$hasGroup) {
                // Kecuali sedang di halaman onboarding, join, atau logout, lempar ke onboarding
                if (!$request->routeIs('main.onboarding.*') &&
                    !$request->routeIs('main.event.join') &&
                    !$request->routeIs('logout')) {

                    return redirect()->route('main.onboarding.index');
                }
            }

            // SKENARIO B: Sudah punya kelompok -> Dilarang masuk Onboarding lagi
            if ($hasGroup) {
                if ($request->routeIs('main.onboarding.*') || $request->routeIs('main.event.join')) {
                    return redirect()->route('main.dashboard');
                }
            }
        }

        return $next($request);
    }
}
