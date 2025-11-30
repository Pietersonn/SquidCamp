<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupMember;
use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil membership user di event aktif
        $membership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function($q) { $q->where('is_active', true); })
            ->first();

        if (!$membership) {
            return redirect()->route('main.dashboard');
        }

        // Ambil Data Group beserta Member & Mentor
        $group = Group::where('id', $membership->group_id)
            ->with(['members.user', 'mentor'])
            ->first();

        return view('main.menu.group', compact('group'));
    }
}
