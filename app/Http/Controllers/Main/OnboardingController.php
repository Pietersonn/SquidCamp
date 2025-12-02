<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    // Redirect ke onboarding event yang aktif ATAU yang akan datang
    public function index()
    {
        // 1. Prioritas: Cari Event yang sedang LIVE (is_active = 1)
        $event = Event::where('is_active', true)->first();

        // 2. Fallback: Jika tidak ada yang Live, cari Event Upcoming (Coming Soon)
        // Syarat: Belum selesai (is_finished = 0) dan tanggalnya hari ini atau masa depan
        if (!$event) {
            $event = Event::where('is_finished', false)
                          ->whereDate('event_date', '>=', now())
                          ->orderBy('event_date', 'asc')
                          ->first();
        }

        // Jika ketemu eventnya (entah aktif atau coming soon), buka form
        if ($event) {
            return redirect()->route('main.onboarding.form', $event->id);
        }

        return redirect()->route('landing')->with('error', 'Tidak ada event aktif atau akan datang.');
    }

    // Bridge dari Landing Page "Join Now"
    public function joinEvent(Event $event)
    {
        $user = Auth::user();

        // Cek apakah user sudah join event INI
        $existingMember = GroupMember::where('user_id', $user->id)
                                     ->where('event_id', $event->id)
                                     ->first();

        // Jika sudah join, langsung lempar ke dashboard
        if ($existingMember) {
            return redirect()->route('main.dashboard');
        }

        // Jika belum, arahkan ke form pendaftaran event TERSEBUT
        return redirect()->route('main.onboarding.form', $event->id);
    }

    // Menampilkan Form Piliih Tim
    public function showForm(Event $event)
    {
        // Double check: Kalau user iseng refresh padahal udah join
        $isJoined = GroupMember::where('user_id', Auth::id())
                               ->where('event_id', $event->id)
                               ->exists();

        if ($isJoined) return redirect()->route('main.dashboard');

        // Ambil daftar kelompok di event ini
        $groups = Group::where('event_id', $event->id)->withCount('members')->get();

        return view('main.onboarding.index', compact('groups', 'event'));
    }

    // Simpan Pilihan Tim & Role
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'role'     => 'required|in:captain,cocaptain,member',
        ]);

        $user = Auth::user();
        $group = Group::findOrFail($request->group_id);

        // Validasi Slot Role
        if ($request->role === 'captain' && $group->captain_id) {
            return back()->with('error', 'Posisi Captain sudah diambil orang lain!');
        }
        if ($request->role === 'cocaptain' && $group->cocaptain_id) {
            return back()->with('error', 'Posisi Co-Captain sudah diambil orang lain!');
        }

        // 1. Masukkan User ke Tabel Member
        GroupMember::create([
            'user_id'  => $user->id,
            'group_id' => $group->id,
            'event_id' => $event->id,
        ]);

        // 2. Update Jabatan di Group
        if ($request->role === 'captain') {
            $group->update(['captain_id' => $user->id]);
        } elseif ($request->role === 'cocaptain') {
            $group->update(['cocaptain_id' => $user->id]);
        }

        // Redirect ke dashboard.
        // NANTI: Middleware CheckEventStatus akan mencegat jika event belum mulai (redirect balik ke landing).
        return redirect()->route('main.dashboard')
            ->with('success', 'Berhasil bergabung! Menunggu event dimulai.');
    }
}
