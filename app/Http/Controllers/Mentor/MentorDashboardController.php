<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ChallengeSubmission;
use App\Models\Group;
use App\Models\Transaction;
use App\Models\Event;

class MentorDashboardController extends Controller
{
    /**
     * Halaman pemilihan event.
     */
    public function selectEvent()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Error P1013 fixed: Method mentorEvents() sekarang sudah ada di User Model
        $events = $user->mentorEvents()->orderBy('created_at', 'desc')->get();

        return view('mentor.select-event', compact('events'));
    }

    /**
     * Dashboard Utama (Scoped per Event)
     */
    public function index(Event $event)
    {
        $user = Auth::user();

        // Filter groups milik mentor INI dan di event INI
        $mentoredGroupIds = Group::where('mentor_id', $user->id)
                                 ->where('event_id', $event->id)
                                 ->pluck('id');

        $pendingSubmissions = ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
            ->where('status', 'pending')
            ->with(['group', 'challenge'])
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_groups' => $mentoredGroupIds->count(),
            'pending_review' => $pendingSubmissions->count(),
            'total_approved' => ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
                                                   ->where('status', 'approved')
                                                   ->count(),
        ];

        return view('mentor.index', compact('pendingSubmissions', 'stats', 'user', 'event'));
    }

    /**
     * List Group Saya (Scoped per Event)
     */
    public function myGroups(Event $event)
    {
        $user = Auth::user();
        $groups = Group::where('mentor_id', $user->id)
            ->where('event_id', $event->id)
            ->withCount(['completedChallenges' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return view('mentor.groups.index', compact('groups', 'event'));
    }

    /**
     * Detail Group
     */
    public function showGroup(Event $event, $id)
    {
        $group = Group::with(['members', 'activeChallenges.challenge', 'completedChallenges.challenge'])
                ->where('event_id', $event->id)
                ->findOrFail($id);

        if($group->mentor_id != Auth::id()) {
            abort(403, 'Unauthorized access to this group');
        }

        return view('mentor.groups.show', compact('group', 'event'));
    }

    // --- LOGIC APPROVE ---
    public function approve(Event $event, $id)
    {
        $submission = ChallengeSubmission::with(['group', 'challenge'])->findOrFail($id);

        // Validasi konteks event
        if ($submission->group->event_id != $event->id) {
            abort(404, 'Submission not found in this event context.');
        }

        if ($submission->status != 'pending') return back()->with('error', 'Sudah diproses.');

        DB::transaction(function () use ($submission, $event) {
            $submission->update([
                'status' => 'approved',
                'mentor_feedback' => 'Great Job! Misi Diterima.',
                'approved_at' => now()
            ]);

            $reward = $submission->challenge->price;

            // Tambah Saldo Group
            $submission->group->increment('bank_balance', $reward);

            // Catat Transaksi
            Transaction::create([
                'event_id' => $event->id,
                'from_type' => 'system',
                'from_id' => 0,
                'to_type' => 'group',
                'to_id' => $submission->group_id,
                'amount' => $reward,
                'reason' => 'CHALLENGE_REWARD',
                'description' => 'Reward Misi: ' . $submission->challenge->nama
            ]);
        });

        return back()->with('success', 'Approved!');
    }

    public function reject(Request $request, Event $event, $id)
    {
        $request->validate(['feedback' => 'required']);

        $submission = ChallengeSubmission::findOrFail($id);

        if ($submission->group->event_id != $event->id) {
            abort(404);
        }

        $submission->update(['status' => 'rejected', 'mentor_feedback' => $request->feedback]);
        return back()->with('success', 'Rejected.');
    }
}
