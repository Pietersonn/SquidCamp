<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupMember;
use App\Models\Event;
use App\Models\Group; // Tambahkan ini

class MainDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cari membership user di event yang sedang aktif
        $membership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function ($q) {
                $q->where('is_active', true);
            })
            ->with(['group', 'event']) // Load relasi group dan event
            ->first();

        // Jika user belum punya grup, middleware seharusnya sudah handle ini.
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
}
