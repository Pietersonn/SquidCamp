<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use App\Models\Transaction; // Pastikan model Transaction ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventGroupController extends Controller
{
    public function index(Event $event)
    {
        $groups = $event->groups()
            ->with(['mentor', 'captain', 'cocaptain'])
            ->withCount('members')
            ->latest()
            ->get();

        return view('admin.events.groups.index', compact('event', 'groups'));
    }

    /**
     * TAMPILKAN DETAIL GROUP LENGKAP
     */
    public function show(Event $event, Group $group)
    {
        // 1. Load struktur tim
        $group->load(['mentor', 'captain', 'cocaptain', 'members.user']);

        // 2. Ambil Transaksi Keuangan (Masuk/Keluar)
        // Logic: Cari transaksi dimana to_id = group_id (Uang Masuk) ATAU from_id = group_id (Uang Keluar)
        $transactions = Transaction::where('event_id', $event->id)
            ->where(function($q) use ($group) {
                $q->where(function($sub) use ($group) {
                    $sub->where('to_type', 'group')->where('to_id', $group->id);
                })->orWhere(function($sub) use ($group) {
                    $sub->where('from_type', 'group')->where('from_id', $group->id);
                });
            })
            ->latest()
            ->get();

        // 3. Data Dummy untuk Submisi (Karena tabel submissions belum dibuat di migrasi sebelumnya)
        // Nanti Anda bisa ganti ini dengan: $group->challengeSubmissions
        $challengeProgress = [
            'total' => 3, // Contoh: Ada 3 challenge di event ini
            'completed' => 1,
            'status' => 'On Progress'
        ];

        $caseStatus = 'Not Started'; // Bisa: Submitted, Graded, Not Started

        return view('admin.events.groups.show', compact(
            'event',
            'group',
            'transactions',
            'challengeProgress',
            'caseStatus'
        ));
    }

    public function create(Event $event)
    {
        $mentors = User::where('role', 'mentor')->get();
        $usersInGroups = GroupMember::whereHas('group', function($q) use ($event) {
            $q->where('event_id', $event->id);
        })->pluck('user_id');

        $candidates = User::where('role', 'user')
            ->whereNotIn('id', $usersInGroups)
            ->orderBy('name')
            ->get();

        return view('admin.events.groups.create', compact('event', 'mentors', 'candidates'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mentor_id' => 'nullable|exists:users,id',
            'squid_dollar' => 'required|integer|min:0',
        ]);

        DB::transaction(function() use ($request, $event) {
            $group = $event->groups()->create([
                'name' => $request->name,
                'mentor_id' => $request->mentor_id,
                'captain_id' => $request->captain_id,
                'cocaptain_id' => $request->cocaptain_id,
                'squid_dollar' => $request->squid_dollar,
            ]);

            if ($request->has('member_ids')) {
                foreach($request->member_ids as $userId) {
                    $exists = GroupMember::where('event_id', $event->id)->where('user_id', $userId)->exists();
                    if (!$exists) {
                        $group->members()->create(['user_id' => $userId, 'event_id' => $event->id]);
                    }
                }
            }
        });

        return redirect()->route('admin.events.groups.index', $event->id)->with('success', 'Kelompok berhasil dibuat.');
    }

    public function edit(Event $event, Group $group)
    {
        $mentors = User::where('role', 'mentor')->get();
        $usersInOtherGroups = GroupMember::whereHas('group', function($q) use ($event, $group) {
            $q->where('event_id', $event->id)->where('id', '!=', $group->id);
        })->pluck('user_id');

        $candidates = User::where('role', 'user')
            ->whereNotIn('id', $usersInOtherGroups)
            ->orderBy('name')
            ->get();

        $currentMemberIds = $group->members()->pluck('user_id')->toArray();

        return view('admin.events.groups.edit', compact('event', 'group', 'mentors', 'candidates', 'currentMemberIds'));
    }

    public function update(Request $request, Event $event, Group $group)
    {
        $request->validate(['name' => 'required', 'squid_dollar' => 'required|integer']);

        DB::transaction(function() use ($request, $group, $event) {
            $group->update([
                'name' => $request->name,
                'mentor_id' => $request->mentor_id,
                'captain_id' => $request->captain_id,
                'cocaptain_id' => $request->cocaptain_id,
                'squid_dollar' => $request->squid_dollar,
            ]);

            $submittedIds = $request->input('member_ids', []);
            $group->members()->whereNotIn('user_id', $submittedIds)->delete();
            $currentIds = $group->members()->pluck('user_id')->toArray();
            $newIds = array_diff($submittedIds, $currentIds);

            foreach($newIds as $userId) {
                $group->members()->create(['user_id' => $userId, 'event_id' => $event->id]);
            }
        });

        return redirect()->route('admin.events.groups.show', ['event' => $event->id, 'group' => $group->id])
            ->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Event $event, Group $group)
    {
        $group->delete();
        return redirect()->route('admin.events.groups.index', $event->id)->with('success', 'Kelompok dihapus.');
    }
}
