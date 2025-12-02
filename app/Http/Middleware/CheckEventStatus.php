<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class CheckEventStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */ // Opsional: Tambahkan ini agar editor tidak merah
        $user = Auth::user();

        $groupMember = null;

        if ($user) {
            // Mengambil member terakhir
            $groupMember = $user->groupMemberships()->latest()->first();
        }

        if (!$groupMember) {
            return redirect()->route('landing')->with('error', 'Anda belum terdaftar di event manapun.');
        }

        // Ambil event dari group
        $event = $groupMember->group->event ?? null;

        if (!$event) {
            return redirect()->route('landing')->with('error', 'Data event tidak valid.');
        }

        // --- LOGIKA UTAMA ---

        // A. Cek Tanggal Pelaksanaan
        $eventDate = Carbon::parse($event->event_date);

        if (!$eventDate->isToday()) {
             if ($eventDate->isFuture()) {
                 return redirect()->route('landing')->with('info', 'Event belum dimulai. Tanggal: ' . $eventDate->translatedFormat('d F Y'));
             }
             // Opsional: Jika user mencoba masuk event yang sudah lewat hari (H+1)
             // return redirect()->route('landing')->with('error', 'Event sudah berlalu.');
        }

        // B. Cek Selesai
        if ($event->is_finished) {
            return redirect()->route('main.thanks');
        }

        // C. Cek Aktif
        if (!$event->is_active) {
            return redirect()->route('landing')->with('info', 'Harap tunggu, Admin belum memulai event.');
        }

        return $next($request);
    }
}
