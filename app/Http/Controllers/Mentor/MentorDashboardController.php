<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ChallengeSubmission;
use App\Models\Group;
use App\Models\Transaction;

class MentorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mentoredGroupIds = Group::where('mentor_id', $user->id)->pluck('id');

        $pendingSubmissions = ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
            ->where('status', 'pending')
            ->with(['group', 'challenge'])
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_groups' => $mentoredGroupIds->count(),
            'pending_review' => $pendingSubmissions->count(),
            'total_approved' => ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)->where('status', 'approved')->count(),
        ];

        return view('mentor.index', compact('pendingSubmissions', 'stats', 'user'));
    }

    public function myGroups()
    {
        $user = Auth::user();
        $groups = Group::where('mentor_id', $user->id)
            ->withCount(['completedChallenges' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return view('mentor.groups.index', compact('groups'));
    }

    public function showGroup($id)
    {
        $group = Group::with(['members', 'activeChallenges.challenge', 'completedChallenges.challenge'])
                ->findOrFail($id);

        if($group->mentor_id != Auth::id()) {
            abort(403, 'Unauthorized access to this group');
        }

        return view('mentor.groups.show', compact('group'));
    }

    public function history()
    {
        $user = Auth::user();
        $mentoredGroupIds = Group::where('mentor_id', $user->id)->pluck('id');

        $histories = ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['group', 'challenge'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('mentor.history', compact('histories'));
    }

    // --- LOGIC APPROVE ---
    public function approve($id)
    {
        $submission = ChallengeSubmission::with(['group', 'challenge'])->findOrFail($id);

        if ($submission->status != 'pending') return back()->with('error', 'Sudah diproses.');

        DB::transaction(function () use ($submission) {
            $submission->update([
                'status' => 'approved',
                'mentor_feedback' => 'Great Job! Misi Diterima.',
                'approved_at' => now()
            ]);

            $reward = $submission->challenge->price;

            // [PERBAIKAN] REWARD MASUK KE BANK (TABUNGAN)
            $submission->group->increment('bank_balance', $reward);

            // CATAT TRANSAKSI (MASUK HISTORY)
            Transaction::create([
                'event_id' => $submission->event_id,
                'from_type' => 'system',
                'from_id' => 0,
                'to_type' => 'group',
                'to_id' => $submission->group_id,
                'amount' => $reward,
                'reason' => 'CHALLENGE_REWARD',
                'description' => 'Reward Misi: ' . $submission->challenge->nama
            ]);
        });

        return back()->with('success', 'Approved! Saldo Bank bertambah & tercatat di history.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['feedback' => 'required']);
        $submission = ChallengeSubmission::findOrFail($id);
        $submission->update(['status' => 'rejected', 'mentor_feedback' => $request->feedback]);
        return back()->with('success', 'Rejected.');
    }
}
