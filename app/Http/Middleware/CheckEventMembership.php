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
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $event = null;

        // 1. Coba ambil Event dari URL (Route Model Binding)
        // Contoh: /event/{id}/onboarding
        if ($request->route('event') instanceof Event) {
            $event = $request->route('event');
        } elseif ($request->route('event')) {
            $event = Event::find($request->route('event'));
        }

        // 2. Jika tidak ada di URL, ambil dari history membership user terakhir
        if (!$event && $user) {
            $lastMember = GroupMember::where('user_id', $user->id)->latest()->first();
            if ($lastMember) {
                $event = $lastMember->event;
            }
        }

        // 3. Jika masih null, cari Event Aktif atau Upcoming
        if (!$event) {
            $event = Event::where('is_active', true)->first(); // Prioritas Live
            if (!$event) {
                $event = Event::where('is_finished', false)
                              ->whereDate('event_date', '>=', now()) // Prioritas Upcoming
                              ->orderBy('event_date', 'asc')
                              ->first();
            }
        }

        // Jika tetap tidak ada event, biarkan lewat (mungkin halaman error)
        if (!$event) {
            return $next($request);
        }

        // --- CEK APAKAH USER SUDAH JOIN DI EVENT INI? ---
        $hasGroup = false;
        if ($user) {
            $hasGroup = GroupMember::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->exists();
        }

        // SKENARIO A: Belum punya kelompok -> Wajib Onboarding
        if (!$hasGroup) {
            // Kecuali sedang di halaman onboarding/join, lempar ke onboarding
            if (!$request->routeIs('main.onboarding.*') && !$request->routeIs('main.event.join')) {
                return redirect()->route('main.onboarding.index');
            }
        }

        // SKENARIO B: Sudah punya kelompok -> Dilarang masuk Onboarding lagi
        if ($hasGroup) {
            if ($request->routeIs('main.onboarding.*') || $request->routeIs('main.event.join')) {
                return redirect()->route('main.dashboard');
            }
        }

        return $next($request);
    }
}
