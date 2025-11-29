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
    // Redirect ke onboarding event yang aktif (Mengatasi RouteNotFound)
    public function index()
    {
        $activeEvent = Event::where('is_active', true)->first();

        if ($activeEvent) {
            return redirect()->route('main.onboarding.form', $activeEvent->id);
        }

        return redirect()->route('landing')->with('error', 'Tidak ada event aktif.');
    }

    // Bridge dari Landing Page "Join Now"
    public function joinEvent(Event $event)
    {
        $user = Auth::user();
        $existingMember = GroupMember::where('user_id', $user->id)->where('event_id', $event->id)->first();

        if ($existingMember) return redirect()->route('main.dashboard');

        return redirect()->route('main.onboarding.form', $event->id);
    }

    // Menampilkan Form Piliih Tim
    public function showForm(Event $event)
    {
        // Cek lagi takutnya user refresh halaman padahal udah join
        $isJoined = GroupMember::where('user_id', Auth::id())->where('event_id', $event->id)->exists();
        if ($isJoined) return redirect()->route('main.dashboard');

        $groups = Group::where('event_id', $event->id)->withCount('members')->get();
        return view('main.onboarding.index', compact('groups', 'event'));
    }

    // Simpan Pilihan Tim & Role
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'role' => 'required|in:captain,cocaptain,member',
        ]);

        $user = Auth::user();
        $group = Group::findOrFail($request->group_id);

        // Validasi Role Slot
        if ($request->role === 'captain' && $group->captain_id) {
            return back()->with('error', 'Posisi Captain sudah diambil orang lain!');
        }
        if ($request->role === 'cocaptain' && $group->cocaptain_id) {
            return back()->with('error', 'Posisi Co-Captain sudah diambil orang lain!');
        }

        // 1. Masukkan ke Member
        GroupMember::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'event_id' => $event->id,
        ]);

        // 2. Update Jabatan di Group
        if ($request->role === 'captain') {
            $group->update(['captain_id' => $user->id]);
        } elseif ($request->role === 'cocaptain') {
            $group->update(['cocaptain_id' => $user->id]);
        }

        return redirect()->route('main.dashboard')->with('success', 'Welcome to the game!');
    }
}
