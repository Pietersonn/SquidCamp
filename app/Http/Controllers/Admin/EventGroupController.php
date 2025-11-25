<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventGroup;
use Illuminate\Http\Request;

class EventGroupController extends Controller
{
    /**
     * TAMPILKAN daftar group beserta anggotanya
     */
    public function index(Event $event)
    {
        // Ambil groups milik event ini, beserta data members-nya
        $groups = $event->groups()->with('members')->latest()->get();

        return view('admin.events.groups.index', compact('event', 'groups'));
    }

    /**
     * HAPUS Group
     */
    public function destroy(Event $event, EventGroup $group)
    {
        $group->delete();

        return back()->with('success', 'Kelompok berhasil dihapus.');
    }
}
