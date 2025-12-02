<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupMember;
use App\Models\Event;
use App\Models\Group;

class MainDashboardController extends Controller
{
    /**
     * Halaman Utama Dashboard Peserta
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Cari membership user di event yang sedang aktif
        $membership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function ($q) {
                // Opsional: Filter event aktif jika perlu, tapi middleware sudah handle
                // $q->where('is_active', true);
            })
            ->with(['group', 'event'])
            ->latest() // Ambil yang paling baru join
            ->first();

        // Jika user belum punya grup, middleware 'CheckEventMembership' seharusnya sudah handle ini.
        $group = $membership ? $membership->group : null;
        $event = $membership ? $membership->event : null;

        // Ambil semua grup di event yang sama untuk list transfer (kecuali grup sendiri)
        $allGroups = [];
        if ($event && $group) {
            $allGroups = Group::where('event_id', $event->id)
                              ->where('id', '!=', $group->id) // Exclude grup sendiri
                              ->orderBy('name')
                              ->get();
        }

        return view('main.dashboard', compact('user', 'group', 'event', 'allGroups'));
    }

    /**
     * Halaman Terima Kasih (Event Selesai)
     */
    public function thanks()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Ambil data member
        $member = $user->groupMemberships()->latest()->first();

        // Jika user akses thanks page tapi belum join grup, lempar ke landing
        if (!$member) {
            return redirect()->route('landing');
        }

        $group = $member->group;
        $event = $group->event;

        // 2. Cek apakah event BENAR-BENAR sudah selesai?
        // (Agar user tidak bisa mengintip thanks page saat event masih jalan)
        if (!$event->is_finished) {
            return redirect()->route('main.dashboard');
        }

        // 3. Hitung Ranking Kelompok (Berdasarkan Total Wealth)
        $allGroups = Group::where('event_id', $event->id)
                    ->selectRaw('*, (squid_dollar + bank_balance) as total_wealth')
                    ->orderByDesc('total_wealth')
                    ->get();

        // Cari posisi ranking grup user ini
        $rank = $allGroups->search(function($g) use ($group) {
            return $g->id === $group->id;
        }) + 1;

        // 4. Pesan Spesifik berdasarkan Ranking
        $message = match ($rank) {
            1 => 'CHAMPION! Selamat, strategi kalian tak terkalahkan! 🏆',
            2 => 'Hebat! Posisi Runner-up adalah bukti ketangguhan kalian. 🥈',
            3 => 'Keren! Kalian berhasil mengamankan posisi 3 Besar. 🥉',
            default => 'Perjuangan yang luar biasa! Terima kasih telah berpartisipasi.'
        };

        return view('main.thanks', compact('group', 'rank', 'message', 'event'));
    }
}
