<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        $activeEvent = Event::where('is_active', 1)->first();

        if(!$activeEvent) return "Belum ada event aktif.";

        // Ambil groups di event ini
        $groups = Group::where('event_id', $activeEvent->id)
            ->withCount('members')
            ->orderBy('name')
            ->get();

        return view('main.onboarding.index', compact('activeEvent', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'role' => 'required|in:captain,cocaptain,member',
        ]);

        $user = Auth::user();
        $group = Group::find($request->group_id);
        $event = Event::where('is_active', 1)->first();

        // Validasi Slot Role
        if ($request->role == 'captain' && $group->captain_id) {
            return back()->with('error', 'Posisi Captain sudah terisi!');
        }
        if ($request->role == 'cocaptain' && $group->cocaptain_id) {
            return back()->with('error', 'Posisi Co-Captain sudah terisi!');
        }

        DB::transaction(function () use ($request, $user, $group, $event) {
            // Update role di group
            if ($request->role == 'captain') {
                $group->update(['captain_id' => $user->id]);
            } elseif ($request->role == 'cocaptain') {
                $group->update(['cocaptain_id' => $user->id]);
            }

            // Masukkan ke group_members
            GroupMember::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'event_id' => $event->id
            ]);
        });

        return redirect()->route('main.dashboard');
    }
}
