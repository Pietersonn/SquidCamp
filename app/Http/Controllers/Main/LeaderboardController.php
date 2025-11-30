<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Event;
use App\Models\GroupMember;

class LeaderboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cari Event Aktif user
        $membership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function($q) {
                $q->where('is_active', true);
            })
            ->with('event')
            ->first();

        if (!$membership) {
            return redirect()->route('main.dashboard')->with('error', 'Event tidak aktif.');
        }

        $event = $membership->event;
        $myGroupId = $membership->group_id;

        // 2. Ambil Leaderboard (Urutkan berdasarkan Squid Dollar)
        $leaderboard = Group::where('event_id', $event->id)
            ->orderBy('squid_dollar', 'desc')
            ->get();

        // 3. Pisahkan Top 3 untuk Podium
        $topThree = $leaderboard->take(3);
        $others = $leaderboard->skip(3);

        return view('main.menu.leaderboard', compact('event', 'topThree', 'others', 'myGroupId'));
    }
}
