<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Transaction;

class TransactionController extends Controller
{
  public function transfer(Request $request)
  {
    // 1. Validasi Input
    $request->validate([
      'to_group_id' => 'required|exists:groups,id',
      'amount' => 'required|numeric|min:1000',
    ]);

    $user = Auth::user();
    $amount = (int) $request->amount; // Pastikan jadi integer

    // 2. Ambil Data Pengirim
    $senderMembership = GroupMember::where('user_id', $user->id)
      ->whereHas('event', function ($q) {
        $q->where('is_active', true);
      })
      ->first();

    if (!$senderMembership) {
      return back()->with('error', 'Anda tidak tergabung dalam tim aktif.');
    }

    // 3. Lock Database untuk mencegah saldo minus saat banyak transaksi bersamaan
    DB::beginTransaction();
    try {
      // Ambil data terbaru dengan lockForUpdate
      $senderGroup = Group::where('id', $senderMembership->group_id)->lockForUpdate()->first();
      $receiverGroup = Group::where('id', $request->to_group_id)->lockForUpdate()->first();

      // Validasi Transfer ke Diri Sendiri
      if ($senderGroup->id == $receiverGroup->id) {
        DB::rollBack();
        return back()->with('error', 'Tidak bisa transfer ke tim sendiri.');
      }

      // Validasi Saldo Cukup
      if ($senderGroup->squid_dollar < $amount) {
        DB::rollBack();
        return back()->with('error', 'Saldo tim tidak cukup!');
      }

      // 4. Proses Pindah Saldo (Manual Calculation agar lebih akurat)
      $senderGroup->squid_dollar -= $amount;
      $senderGroup->save();

      $receiverGroup->squid_dollar += $amount;
      $receiverGroup->save();

      // 5. Catat Riwayat
      Transaction::create([
        'event_id' => $senderMembership->event_id,
        'from_type' => 'group',
        'from_id' => $senderGroup->id,
        'to_type' => 'group',
        'to_id' => $receiverGroup->id,
        'amount' => $amount,
        'reason' => 'GROUP_TRANSFER',
        'description' => 'Transfer dari ' . $senderGroup->name
      ]);

      DB::commit();

      return back()->with('success', 'Berhasil transfer SQ$ ' . number_format($amount) . ' ke ' . $receiverGroup->name);
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Gagal transfer: ' . $e->getMessage());
    }
  }
  // app/Http/Controllers/Main/TransactionController.php

  public function withdrawFromBank(Request $request)
  {
    $request->validate([
      'amount' => 'required|numeric|min:1',
    ]);

    $user = Auth::user();
    $membership = GroupMember::where('user_id', $user->id)->first();

    if (!$membership) return back()->with('error', 'Tim tidak ditemukan.');

    $group = Group::find($membership->group_id);

    // LOGIC DIBALIK: Cek Saldo Bank (Tabungan)
    if ($group->bank_balance < $request->amount) {
      return back()->with('error', 'Saldo di Bank tidak mencukupi untuk ditarik!');
    }

    DB::transaction(function () use ($group, $request) {
      // 1. Kurangi Bank Balance
      $group->bank_balance -= $request->amount;

      // 2. Tambah ke Cash (Dompet)
      $group->squid_dollar += $request->amount;
      $group->save();

      // 3. Catat Riwayat Transaksi
      Transaction::create([
        'event_id' => $group->event_id,
        'from_type' => 'bank', // DARI BANK
        'from_id' => 0,
        'to_type' => 'group',  // KE GROUP (Cash)
        'to_id' => $group->id,
        'amount' => $request->amount,
        'reason' => 'BANK_WITHDRAWAL',
        'description' => 'Penarikan dari Bank ke Cash',
      ]);
    });

    return back()->with('success', 'Berhasil menarik uang ke Dompet Cash!');
  }
}
