<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\GroupMember;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Carbon\Carbon;

class ChallengeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cek Event Aktif & Membership
        $event = Event::where('is_active', true)->firstOrFail();
        $membership = GroupMember::where('user_id', $user->id)
                        ->where('event_id', $event->id)
                        ->with('group')
                        ->firstOrFail();

        $group = $membership->group;

        // 2. Cek Logika Waktu (Timer)
        $now = Carbon::now();
        $isOpened = false;

        // Asumsi kolom waktu di tabel event: challenge_start_time & challenge_end_time
        if ($event->challenge_start_time && $event->challenge_end_time) {
            $start = Carbon::parse($event->challenge_start_time);
            $end = Carbon::parse($event->challenge_end_time);

            if ($now->between($start, $end)) {
                $isOpened = true;
            }
        }

        // 3. Ambil Challenge Group saat ini
        $myActiveChallenges = ChallengeSubmission::where('group_id', $group->id)
                                ->whereIn('status', ['active', 'pending', 'rejected']) // Rejected bisa di resubmit/ulang
                                ->with('challenge')
                                ->get();

        $slotUsed = $myActiveChallenges->whereIn('status', ['active', 'pending'])->count();
        $canTakeMore = $slotUsed < 2;

        // Cek Role User di Grup (untuk tombol "Ambil Challenge")
        $isCaptain = ($group->captain_id == $user->id || $group->cocaptain_id == $user->id);

        return view('main.challenges.index', compact(
            'event', 'isOpened', 'myActiveChallenges', 'canTakeMore', 'isCaptain', 'group'
        ));
    }

    // Logic mengambil challenge (Hanya Captain/Co)
    public function take(Request $request)
    {
        $user = Auth::user();
        $price = $request->price; // 300000, 500000, atau 700000

        $event = Event::where('is_active', true)->firstOrFail();
        $membership = GroupMember::where('user_id', $user->id)->where('event_id', $event->id)->firstOrFail();
        $group = $membership->group;

        // Validasi Role
        if ($group->captain_id != $user->id && $group->cocaptain_id != $user->id) {
            return back()->with('error', 'Hanya Captain atau Co-Captain yang boleh mengambil Challenge!');
        }

        // Validasi Slot
        $slotUsed = ChallengeSubmission::where('group_id', $group->id)
                        ->whereIn('status', ['active', 'pending'])->count();

        if ($slotUsed >= 2) {
            return back()->with('error', 'Slot penuh! Selesaikan challenge yang ada dulu.');
        }

        // Randomizer Challenge
        // Ambil challenge berdasarkan harga, relasi event, dan yang BELUM pernah diselesaikan/sedang dikerjakan grup ini
        $takenChallengeIds = ChallengeSubmission::where('group_id', $group->id)
                                ->pluck('challenge_id');

        $randomChallenge = Challenge::whereHas('events', function($q) use ($event) {
                                $q->where('events.id', $event->id);
                            })
                            ->where('price', $price)
                            ->whereNotIn('id', $takenChallengeIds)
                            ->inRandomOrder()
                            ->first();

        if (!$randomChallenge) {
            return back()->with('error', 'Maaf, stok challenge untuk harga ini sudah habis atau sudah kalian ambil semua!');
        }

        // Simpan
        ChallengeSubmission::create([
            'event_id' => $event->id,
            'group_id' => $group->id,
            'challenge_id' => $randomChallenge->id,
            'status' => 'active',
            'user_id' => null // Belum ada yang submit
        ]);

        return back()->with('success', 'Challenge berhasil diambil! Semangat mengerjakannya.');
    }

    // Logic Submit Jawaban (Semua Member)
    public function store(Request $request, $submissionId)
    {
        $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx,zip,jpg,png|max:10240', // Max 10MB
        ]);

        if(!$request->submission_text && !$request->hasFile('file')){
             return back()->with('error', 'Harap lampirkan file atau link/text jawaban.');
        }

        $submission = ChallengeSubmission::where('id', $submissionId)->where('status', 'active')->firstOrFail();

        // Upload File
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('submissions/challenges', $filename, 'public');
        }

        $submission->update([
            'user_id' => Auth::id(), // Pencatat siapa yang submit
            'submission_text' => $request->submission_text,
            'file_path' => $filePath,
            'status' => 'pending' // Masuk antrian review mentor
        ]);

        return back()->with('success', 'Jawaban dikirim! Menunggu review Mentor.');
    }
}
