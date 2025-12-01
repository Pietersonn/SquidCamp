<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Models
use App\Models\Event;
use App\Models\GroupMember;
use App\Models\Cases;
use App\Models\Guideline;
use App\Models\Transaction;

class CaseController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    $event = Event::where('is_active', true)->firstOrFail();
    $membership = GroupMember::where('user_id', $user->id)
      ->where('event_id', $event->id)
      ->with('group')
      ->firstOrFail();
    $group = $membership->group;

    // Cek Timer
    $now = Carbon::now();
    $isOpened = false;

    if ($event->case_start_time && $event->case_end_time) {
      $start = Carbon::parse($event->case_start_time);
      $end = Carbon::parse($event->case_end_time);
      $isOpened = $now->between($start, $end);
    }

    // Ambil Cases Event
    $cases = Cases::whereHas('events', function ($q) use ($event) {
      $q->where('events.id', $event->id);
    })->get();

    foreach ($cases as $case) {
      $submission = DB::table('case_submissions')
        ->where('group_id', $group->id)
        ->where('case_id', $case->id)
        ->first();
      $case->my_submission = $submission;
    }

    // Logic Gacha Guideline
    $totalGuidelines = Guideline::whereHas('events', function ($q) use ($event) {
      $q->where('events.id', $event->id);
    })->count();

    $myGuidelines = DB::table('group_guidelines')
      ->join('guidelines', 'group_guidelines.guideline_id', '=', 'guidelines.id')
      ->where('group_guidelines.group_id', $group->id)
      ->select('guidelines.*')
      ->get();

    $ownedCount = $myGuidelines->count();
    $gachaPrice = 500000;

    return view('main.cases.index', compact(
      'event',
      'group',
      'isOpened',
      'cases',
      'myGuidelines',
      'totalGuidelines',
      'ownedCount',
      'gachaPrice'
    ));
  }

  // Logic Beli Guideline (Gacha)
  public function buyGuideline(Request $request)
  {
    $user = Auth::user();
    $event = Event::where('is_active', true)->firstOrFail();
    $membership = GroupMember::where('user_id', $user->id)->where('event_id', $event->id)->firstOrFail();
    $group = $membership->group;

    $gachaPrice = 150000;

    if ($group->squid_dollar < $gachaPrice) {
      return back()->with('error', 'Saldo tidak cukup!');
    }

    // Cari yang belum dimiliki
    $ownedIds = DB::table('group_guidelines')
      ->where('group_id', $group->id)
      ->pluck('guideline_id');

    $randomGuideline = Guideline::whereHas('events', function ($q) use ($event) {
      $q->where('events.id', $event->id);
    })
      ->whereNotIn('id', $ownedIds)
      ->inRandomOrder()
      ->first();

    if (!$randomGuideline) {
      return back()->with('error', 'Semua guideline sudah terbeli!');
    }

    DB::transaction(function () use ($group, $event, $randomGuideline, $gachaPrice) {

      // Kurangi saldo kelompok
      $group->decrement('squid_dollar', $gachaPrice);

      // Tambahkan guideline
      DB::table('group_guidelines')->insert([
        'event_id' => $event->id,
        'group_id' => $group->id,
        'guideline_id' => $randomGuideline->id,
        'price_paid' => $gachaPrice,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      // Catat transaksi
      Transaction::create([
        'event_id' => $event->id,
        'from_type' => 'group',
        'from_id' => $group->id,
        'to_type' => 'system',
        'to_id' => 0,
        'amount' => $gachaPrice,
        'reason' => 'BUY_GUIDELINE',
      ]);
    });

    return back()->with('success', 'Guideline berhasil dibeli!');
  }

  // Logic Submit dengan Race Condition Lock
  public function submit(Request $request, $caseId)
  {
    $request->validate([
      'submission_text' => 'nullable|url',
      'submission_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg|max:20480', // Max 20MB
    ]);

    // Cek manual apakah setidaknya satu input terisi
    if (!$request->submission_text && !$request->hasFile('submission_file')) {
      return back()->with('error', 'Mohon lampirkan Link Jawaban atau Upload File.');
    }

    $user = Auth::user();
    $event = Event::where('is_active', true)->firstOrFail();
    $membership = GroupMember::where('user_id', $user->id)->where('event_id', $event->id)->firstOrFail();
    $group = $membership->group;

    $exists = DB::table('case_submissions')
      ->where('group_id', $group->id)
      ->where('case_id', $caseId)
      ->exists();

    if ($exists) {
      return back()->with('error', 'Kasus ini sudah disubmit sebelumnya.');
    }

    // --- PERUBAHAN DI SINI (RENAME FILE) ---
    $filePath = null;
    if ($request->hasFile('submission_file')) {
      $file = $request->file('submission_file');
      $extension = $file->getClientOriginalExtension();

      // Bersihkan nama group (ganti spasi jadi strip, hapus karakter aneh)
      $safeGroupName = \Illuminate\Support\Str::slug($group->name);

      // Format Nama: NamaGroup_Case-ID.ext (Contoh: Tim-Alpha_Case-1.pdf)
      $fileName = "{$safeGroupName}_Case-{$caseId}.{$extension}";

      // Simpan dengan nama baru
      $filePath = $file->storeAs('submissions', $fileName, 'public');
    }
    // ---------------------------------------

    DB::transaction(function () use ($request, $event, $group, $caseId, $user, $filePath) {

      // Lock row count untuk ranking
      $count = DB::table('case_submissions')
        ->where('event_id', $event->id)
        ->where('case_id', $caseId)
        ->lockForUpdate()
        ->count();

      $rank = $count + 1;

      // Reward Logic (Bisa disesuaikan)
      if ($rank == 1) $reward = 500000;
      elseif ($rank == 2) $reward = 450000;
      elseif ($rank == 3) $reward = 400000;
      elseif ($rank >= 4 && $rank <= 10) $reward = 300000;
      else $reward = 100000;

      // Simpan submission
      DB::table('case_submissions')->insert([
        'event_id' => $event->id,
        'group_id' => $group->id,
        'case_id' => $caseId,
        'user_id' => $user->id,
        'submission_text' => $request->submission_text, // Link
        'file_path' => $filePath,                       // Path File
        'rank' => $rank,
        'reward_amount' => $reward,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      // Tambah saldo grup
      $group->increment('squid_dollar', $reward);

      // Log transaksi reward
      Transaction::create([
        'event_id' => $event->id,
        'from_type' => 'system',
        'from_id' => 0,
        'to_type' => 'group',
        'to_id' => $group->id,
        'amount' => $reward,
        'reason' => 'CASE_REWARD',
      ]);
    });

    return back()->with('success', 'Misi selesai! Jawaban berhasil dikirim.');
  }
}
