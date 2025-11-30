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
    /**
     * HALAMAN 1: DASHBOARD (HOME)
     * Menampilkan overview dan tugas yang HARUS segera dikerjakan (Pending)
     */
    public function index()
    {
        $user = Auth::user();
        $mentoredGroupIds = Group::where('mentor_id', $user->id)->pluck('id');

        // 1. Ambil Submission yang PENDING (Prioritas Utama)
        $pendingSubmissions = ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
            ->where('status', 'pending')
            ->with(['group', 'challenge'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Statistik Ringkas untuk Header Dashboard
        $stats = [
            'total_groups' => $mentoredGroupIds->count(),
            'pending_review' => $pendingSubmissions->count(),
            'total_approved' => ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)->where('status', 'approved')->count(),
        ];

        return view('mentor.index', compact('pendingSubmissions', 'stats', 'user'));
    }

    /**
     * HALAMAN 2: MONITORING GROUP (TEAM)
     * Daftar kelompok yang dimenti dan status mereka
     */
    public function myGroups()
    {
        $user = Auth::user();

        // Ambil data group beserta hitungan challenge yang selesai
        $groups = Group::where('mentor_id', $user->id)
            ->withCount(['completedChallenges' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return view('mentor.groups.index', compact('groups'));
    }

    /**
     * DETAIL MONITORING PER GROUP
     * Melihat detail satu kelompok: challenge aktif, riwayat, saldo
     */
    public function showGroup($id)
    {
        $group = Group::with(['members', 'activeChallenges.challenge', 'completedChallenges.challenge'])
                ->findOrFail($id);

        // Pastikan mentor ini berhak melihat group ini
        if($group->mentor_id != Auth::id()) {
            abort(403, 'Unauthorized access to this group');
        }

        return view('mentor.groups.show', compact('group'));
    }

    /**
     * HALAMAN 3: RIWAYAT (HISTORY)
     * Daftar semua approval/rejection yang sudah dilakukan mentor
     */
    public function history()
    {
        $user = Auth::user();

        // Ambil submission yang SUDAH diproses (Approved/Rejected)
        // Kita bisa pakai mentor_id di table submission (jika ada) atau filter by group
        $mentoredGroupIds = Group::where('mentor_id', $user->id)->pluck('id');

        $histories = ChallengeSubmission::whereIn('group_id', $mentoredGroupIds)
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['group', 'challenge'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10); // Pakai pagination biar rapi

        return view('mentor.history', compact('histories'));
    }

    // --- LOGIKA APPROVE/REJECT (SAMA SEPERTI SEBELUMNYA) ---
    public function approve($id)
    {
        $submission = ChallengeSubmission::with(['group', 'challenge'])->findOrFail($id);

        if ($submission->status != 'pending') return back()->with('error', 'Sudah diproses.');

        DB::transaction(function () use ($submission) {
            $submission->update([
                'status' => 'approved',
                'mentor_feedback' => 'Great Job!',
                'approved_at' => now()
            ]);

            // Logic Saldo (Tanpa Pengali)
            $reward = $submission->challenge->price;
            $submission->group->increment('squid_dollar', $reward);

            // Opsional: Catat Transaksi (Jika tabel transactions ada)
            // Transaction::create([...]);
        });

        return back()->with('success', 'Approved!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['feedback' => 'required']);
        $submission = ChallengeSubmission::findOrFail($id);
        $submission->update(['status' => 'rejected', 'mentor_feedback' => $request->feedback]);
        return back()->with('success', 'Rejected.');
    }
}
