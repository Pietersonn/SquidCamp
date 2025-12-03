<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Event;
use App\Models\GroupMember;
use App\Models\Cases;
use App\Models\Guideline;
use App\Models\Transaction;
use App\Models\Group;

class CaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cek Event & Membership
        $event = Event::where('is_active', true)->firstOrFail();
        $membership = GroupMember::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->with('group')
            ->firstOrFail();
        $group = $membership->group;

        // 2. Cek Timer Cases
        $now = Carbon::now();
        $isOpened = false;

        if ($event->case_start_time && $event->case_end_time) {
            $start = Carbon::parse($event->case_start_time);
            $end = Carbon::parse($event->case_end_time);
            $isOpened = $now->between($start, $end);
        }

        // 3. Ambil Daftar Kasus
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

        // 4. LOGIC TOKO GUIDELINE
        $allGuidelines = Guideline::whereHas('events', function ($q) use ($event) {
            $q->where('events.id', $event->id);
        })->get();

        $ownedGuidelineIds = DB::table('group_guidelines')
            ->where('group_id', $group->id)
            ->pluck('guideline_id')
            ->toArray();

        $allGuidelines->each(function($gl) use ($ownedGuidelineIds, $event) {
            $gl->is_owned = in_array($gl->id, $ownedGuidelineIds);
            $soldCount = DB::table('group_guidelines')
                ->where('event_id', $event->id)
                ->where('guideline_id', $gl->id)
                ->count();
            $gl->stock = max(0, 5 - $soldCount);
        });

        $myGuidelines = $allGuidelines->where('is_owned', true);

        return view('main.cases.index', compact(
            'event', 'group', 'isOpened', 'cases', 'allGuidelines', 'myGuidelines'
        ));
    }

    // LOGIC BELI MENGGUNAKAN CASH (SQUID_DOLLAR)
    // Pembelian item kecil biasanya pakai Cash/Dompet
    public function buyGuideline(Request $request)
    {
        $request->validate([
            'guideline_id' => 'required|exists:guidelines,id'
        ]);

        $user = Auth::user();
        $event = Event::where('is_active', true)->firstOrFail();
        $membership = GroupMember::where('user_id', $user->id)->where('event_id', $event->id)->firstOrFail();
        $group = $membership->group;

        $guideline = Guideline::find($request->guideline_id);
        $price = $guideline->price;

        // 1. CEK STOK
        $soldCount = DB::table('group_guidelines')
            ->where('event_id', $event->id)
            ->where('guideline_id', $guideline->id)
            ->count();

        if ($soldCount >= 5) {
            return back()->with('error', 'Gagal! Dokumen ini sudah habis terjual (Sold Out).');
        }

        // 2. CEK SALDO CASH (SQUID DOLLAR)
        if ($group->squid_dollar < $price) {
            return back()->with('error', 'Saldo Cash tidak mencukupi! ($'.number_format($price).')');
        }

        // 3. Cek Kepemilikan
        $alreadyOwn = DB::table('group_guidelines')
            ->where('group_id', $group->id)
            ->where('guideline_id', $guideline->id)
            ->exists();

        if ($alreadyOwn) {
            return back()->with('error', 'Tim kamu sudah memiliki dokumen ini!');
        }

        // 4. Proses Transaksi
        DB::transaction(function () use ($group, $event, $guideline, $price) {
            $currentSold = DB::table('group_guidelines')
                ->where('event_id', $event->id)
                ->where('guideline_id', $guideline->id)
                ->lockForUpdate()
                ->count();

            if ($currentSold >= 5) {
                throw new \Exception('Stok habis saat proses transaksi.');
            }

            // KURANGI SALDO CASH (SQUID DOLLAR)
            $group->decrement('squid_dollar', $price);

            DB::table('group_guidelines')->insert([
                'event_id' => $event->id,
                'group_id' => $group->id,
                'guideline_id' => $guideline->id,
                'price_paid' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Transaction::create([
                'event_id' => $event->id,
                'from_type' => 'group',
                'from_id' => $group->id,
                'to_type' => 'system',
                'to_id' => 0,
                'amount' => $price,
                'reason' => 'BUY_GUIDELINE',
                'description' => 'Membeli Dokumen Rahasia ('.$guideline->title.')'
            ]);
        });

        return back()->with('success', 'Berhasil membeli dokumen!');
    }

    public function submit(Request $request, $caseId)
    {
        $request->validate([
            'submission_text' => 'nullable|url',
            'submission_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg|max:20480',
        ]);

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

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $file = $request->file('submission_file');
            $extension = $file->getClientOriginalExtension();
            $safeGroupName = \Illuminate\Support\Str::slug($group->name);
            $fileName = "{$safeGroupName}_Case-{$caseId}.{$extension}";
            $filePath = $file->storeAs('submissions', $fileName, 'public');
        }

        DB::transaction(function () use ($request, $event, $group, $caseId, $user, $filePath) {
            $count = DB::table('case_submissions')
                ->where('event_id', $event->id)
                ->where('case_id', $caseId)
                ->lockForUpdate()
                ->count();

            $rank = $count + 1;

            if ($rank == 1) $reward = 500000;
            elseif ($rank == 2) $reward = 450000;
            elseif ($rank == 3) $reward = 400000;
            elseif ($rank >= 4 && $rank <= 10) $reward = 300000;
            else $reward = 100000;

            DB::table('case_submissions')->insert([
                'event_id' => $event->id,
                'group_id' => $group->id,
                'case_id' => $caseId,
                'user_id' => $user->id,
                'submission_text' => $request->submission_text,
                'file_path' => $filePath,
                'rank' => $rank,
                'reward_amount' => $reward,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // [PERBAIKAN] REWARD MASUK KE BANK (TABUNGAN)
            $group->increment('bank_balance', $reward);

            // CATAT TRANSAKSI (MASUK HISTORY)
            Transaction::create([
                'event_id' => $event->id,
                'from_type' => 'system',
                'from_id' => 0,
                'to_type' => 'group',
                'to_id' => $group->id,
                'amount' => $reward,
                'reason' => 'CASE_REWARD',
                'description' => 'Reward Case #' . $caseId . ' (Rank ' . $rank . ')'
            ]);
        });

        return back()->with('success', 'Misi selesai! Jawaban berhasil dikirim dan reward masuk ke Bank.');
    }
}
