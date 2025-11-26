<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventMentorController extends Controller
{
    /**
     * TAMPILKAN DAFTAR MENTOR
     */
    public function index(Event $event)
    {
        $mentors = $event->mentors()
            ->with(['groups' => function($q) use ($event) {
                $q->where('event_id', $event->id);
            }])
            ->get();

        return view('admin.events.mentors.index', compact('event', 'mentors'));
    }

    /**
     * FORM TAMBAH MENTOR (Sekaligus Pilih Kelompok)
     */
    public function create(Event $event)
    {
        // 1. Ambil Mentor yang BELUM ada di event ini
        $existingMentorIds = $event->mentors()->pluck('users.id');
        $availableMentors = User::where('role', 'mentor')
            ->whereNotIn('id', $existingMentorIds)
            ->get();

        // 2. Ambil Semua Kelompok di Event ini untuk dipilih
        $groups = $event->groups;

        return view('admin.events.mentors.create', compact('event', 'availableMentors', 'groups'));
    }

    /**
     * SIMPAN MENTOR & UPDATE KELOMPOK
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        DB::transaction(function () use ($request, $event) {
            // 1. Masukkan user ke dalam list mentor event (Tabel Pivot)
            $event->mentors()->attach($request->user_id);

            // 2. Jika ada kelompok yang dipilih, update mentor_id mereka
            if ($request->has('group_ids')) {
                // Pastikan kelompok yang dipilih benar-benar milik event ini (security check)
                Group::where('event_id', $event->id)
                    ->whereIn('id', $request->group_ids)
                    ->update(['mentor_id' => $request->user_id]);
            }
        });

        return redirect()->route('admin.events.mentors.index', $event->id)
            ->with('success', 'Mentor berhasil ditambahkan dan kelompok telah ditetapkan.');
    }

    /**
     * FORM EDIT (Ubah Penugasan Kelompok)
     */
    public function edit(Event $event, User $mentor)
    {
        $groups = $event->groups;
        $assignedGroupIds = $mentor->groups()
            ->where('event_id', $event->id)
            ->pluck('id')
            ->toArray();

        return view('admin.events.mentors.edit', compact('event', 'mentor', 'groups', 'assignedGroupIds'));
    }

    /**
     * UPDATE PENUGASAN
     */
    public function update(Request $request, Event $event, User $mentor)
    {
        $request->validate([
            'group_ids' => 'array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        DB::transaction(function () use ($event, $mentor, $request) {
            // Reset semua kelompok milik mentor di event ini
            Group::where('event_id', $event->id)
                ->where('mentor_id', $mentor->id)
                ->update(['mentor_id' => null]);

            // Assign yang baru
            if ($request->has('group_ids')) {
                Group::whereIn('id', $request->group_ids)
                    ->update(['mentor_id' => $mentor->id]);
            }
        });

        return redirect()->route('admin.events.mentors.index', $event->id)
            ->with('success', 'Penugasan kelompok berhasil diperbarui.');
    }

    public function destroy(Event $event, User $mentor)
    {
        Group::where('event_id', $event->id)
             ->where('mentor_id', $mentor->id)
             ->update(['mentor_id' => null]);

        $event->mentors()->detach($mentor->id);

        return back()->with('success', 'Mentor dihapus dari event ini.');
    }
}
