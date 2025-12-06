<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupMember;
use App\Models\Event;
use App\Models\Group;

class MainDashboardController extends Controller
{
  /**
   * Halaman Utama Dashboard Peserta
   */
  public function index()
  {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // 1. Cari membership user di event yang sedang aktif
    $membership = GroupMember::where('user_id', $user->id)
      ->whereHas('event', function ($q) {
        // Opsional: Filter event aktif jika perlu, tapi middleware sudah handle
        // $q->where('is_active', true);
      })
      ->with(['group', 'event'])
      ->latest() // Ambil yang paling baru join
      ->first();

    // Jika user belum punya grup, middleware 'CheckEventMembership' seharusnya sudah handle ini.
    $group = $membership ? $membership->group : null;
    $event = $membership ? $membership->event : null;

    // Ambil semua grup di event yang sama untuk list transfer (kecuali grup sendiri)
    $allGroups = [];
    if ($event && $group) {
      $allGroups = Group::where('event_id', $event->id)
        ->where('id', '!=', $group->id) // Exclude grup sendiri
        ->orderBy('name')
        ->get();
    }

    return view('main.dashboard', compact('user', 'group', 'event', 'allGroups'));
  }

  /**
   * Halaman Terima Kasih (Event Selesai)
   */
public function thanks()
    {
        $user = Auth::user();

        // 1. Ambil membership terakhir
        $groupMember = GroupMember::where('user_id', $user->id)->latest()->first();

        if (!$groupMember) {
            return redirect()->route('landing');
        }

        $myGroup = $groupMember->group;
        $event = $myGroup->event;

        // 2. LOGIKA RANKING (Kekayaan TERTINGGI -> TERENDAH)
        $allGroups = Group::where('event_id', $event->id)->get();

        // Sort Descending: Index 0 = Tim Paling Kaya
        $sortedGroups = $allGroups->sortByDesc(function ($group) {
            return $group->squid_dollar + $group->bank_balance;
        })->values();

        // 3. DAFTAR GELAR (DIBALIK: Index 0 = Juara 1 / Paling Kaya)
        $awardsList = [
            'The Best Squid',          // Rank 1
            'The Richest Squid',       // Rank 2
            'The Most Inspiring',      // Rank 3
            'The Most Favorite',       // Rank 4
            'The Most Entertaining',   // Rank 5
            'The Most Passionate',     // Rank 6
            'The Most Popular',        // Rank 7
            'The Most Solid',          // Rank 8
            'The Most Iconic',         // Rank 9
            'The Most Active',         // Rank 10
            'The Most Innovative',     // Rank 11
            'The Best Collaboration',  // Rank 12
            'The Most Supportive',     // Rank 13
            'The Most Resourceful',    // Rank 14
            'The Most Creative'        // Rank 15 (Paling Sedikit Uangnya)
        ];

        // 4. Cari Posisi Tim Saya
        $myIndex = $sortedGroups->search(function ($group) use ($myGroup) {
            return $group->id === $myGroup->id;
        });

        // Tentukan Gelar (Jika tim > 15, tim terbawah dapat gelar terakhir)
        $myAward = isset($awardsList[$myIndex]) ? $awardsList[$myIndex] : end($awardsList);

        // Hitung Ranking (1, 2, 3...)
        $realRank = $myIndex + 1;

        return view('thanks', compact('event', 'myGroup', 'myAward', 'realRank'));
    }
}
