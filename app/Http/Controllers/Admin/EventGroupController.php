<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use App\Models\Transaction;
use App\Models\ChallengeSubmission; // <--- PERBAIKAN UTAMA (Supaya tidak error undefined type)

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventGroupController extends Controller
{
    // ... (sisa kode controller tetap sama seperti sebelumnya) ...
    public function index(Event $event)
    {
        $groups = $event->groups()
            ->with(['mentor', 'captain', 'cocaptain'])
            ->withCount('members')
            ->latest()
            ->get();

        return view('admin.events.groups.index', compact('event', 'groups'));
    }

    public function show(Event $event, Group $group)
    {
        $group->load(['members.user', 'mentor', 'captain', 'cocaptain']);

        $transactions = Transaction::where(function ($q) use ($group) {
            $q->where('from_type', 'group')->where('from_id', $group->id);
        })
        ->orWhere(function ($q) use ($group) {
            $q->where('to_type', 'group')->where('to_id', $group->id);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Ini yang tadi error, sekarang sudah aman karena sudah di-use di atas
        $challengeSubmissions = ChallengeSubmission::where('group_id', $group->id)
            ->with('challenge')
            ->orderBy('created_at', 'desc')
            ->get();

        $caseSubmissions = DB::table('case_submissions')
            ->join('cases', 'case_submissions.case_id', '=', 'cases.id')
            ->where('case_submissions.group_id', $group->id)
            ->select('case_submissions.*', 'cases.title as case_title')
            ->orderBy('case_submissions.created_at', 'desc')
            ->get();

        $totalChallenges = $event->challenges()->count();
        $completedChallenges = $challengeSubmissions->where('status', 'approved')->count();
        $challengeProgress = [
            'total' => $totalChallenges,
            'completed' => $completedChallenges
        ];

        $caseStatus = $caseSubmissions->count() > 0 ? 'Submitted' : 'Pending';

        return view('admin.events.groups.show', compact(
            'event', 'group', 'transactions', 'challengeSubmissions', 'caseSubmissions', 'challengeProgress', 'caseStatus'
        ));
    }

    // ... (method create, store, edit, update, destroy lainnya copy dari kode Anda sebelumnya) ...
    public function create(Event $event)
    {
        $mentors = User::where('role', 'mentor')->get();
        $usersInGroups = GroupMember::whereHas('group', function ($q) use ($event) {
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

        DB::transaction(function () use ($request, $event) {
            $group = $event->groups()->create([
                'name' => $request->name,
                'mentor_id' => $request->mentor_id,
                'captain_id' => $request->captain_id,
                'cocaptain_id' => $request->cocaptain_id,
                'squid_dollar' => $request->squid_dollar,
                'bank_balance' => 0, // Default bank 0
            ]);

            if ($request->has('member_ids')) {
                foreach ($request->member_ids as $userId) {
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
        $usersInOtherGroups = GroupMember::whereHas('group', function ($q) use ($event, $group) {
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
        $request->validate([
            'name' => 'required',
            'squid_dollar' => 'required|integer',
            'bank_balance' => 'required|integer' // Tambahkan validasi bank
        ]);

        DB::transaction(function () use ($request, $group, $event) {
            $group->update([
                'name' => $request->name,
                'mentor_id' => $request->mentor_id,
                'captain_id' => $request->captain_id,
                'cocaptain_id' => $request->cocaptain_id,
                'squid_dollar' => $request->squid_dollar,
                'bank_balance' => $request->bank_balance, // Update saldo bank juga
            ]);

            $submittedIds = $request->input('member_ids', []);
            $group->members()->whereNotIn('user_id', $submittedIds)->delete();
            $currentIds = $group->members()->pluck('user_id')->toArray();
            $newIds = array_diff($submittedIds, $currentIds);

            foreach ($newIds as $userId) {
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
