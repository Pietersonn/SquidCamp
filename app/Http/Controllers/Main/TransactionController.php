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
  // 1. TRANSFER ANTAR KELOMPOK (Masih Pakai SweetAlert)
  public function transfer(Request $request)
  {
    $request->validate([
      'to_group_id' => 'required|exists:groups,id',
      'amount' => 'required|numeric|min:1000',
    ]);

    $user = Auth::user();
    $amount = (int) $request->amount;

    $senderMembership = GroupMember::where('user_id', $user->id)
      ->whereHas('event', function ($q) {
        $q->where('is_active', true);
      })
      ->first();

    if (!$senderMembership) {
      return back()->with('error', 'Anda tidak tergabung dalam tim aktif.');
    }

    DB::beginTransaction();
    try {
      $senderGroup = Group::where('id', $senderMembership->group_id)->lockForUpdate()->first();
      $receiverGroup = Group::where('id', $request->to_group_id)->lockForUpdate()->first();

      if ($senderGroup->id == $receiverGroup->id) {
        DB::rollBack();
        return back()->with('error', 'Tidak bisa transfer ke tim sendiri.');
      }

      // Cek Saldo CASH (bank_balance)
      if ($senderGroup->bank_balance < $amount) {
        DB::rollBack();
        return back()->with('error', 'Saldo Cash (Dompet) tidak cukup! Silakan tarik uang dari Bank dulu.');
      }

      $senderGroup->bank_balance -= $amount;
      $senderGroup->save();

      $receiverGroup->bank_balance += $amount;
      $receiverGroup->save();

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

      // Transfer tetap pakai notifikasi Success biasa
      return back()->with('success', 'Berhasil transfer SQ$ ' . number_format($amount) . ' ke ' . $receiverGroup->name);
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Gagal transfer: ' . $e->getMessage());
    }
  }

  // 2. TARIK TUNAI DARI BANK (Tanpa SweetAlert, Hanya Struk)
  public function withdrawFromBank(Request $request)
  {
    $request->validate([
      'amount' => 'required|numeric|min:1',
    ]);

    $user = Auth::user();
    $membership = GroupMember::where('user_id', $user->id)->first();

    if (!$membership) return back()->with('error', 'Tim tidak ditemukan.');

    $group = Group::find($membership->group_id);

    // Cek Saldo BANK (squid_dollar)
    if ($group->squid_dollar < $request->amount) {
      return back()->with('error', 'Saldo di Squid Bank tidak mencukupi!');
    }

    // Gunakan variabel untuk menangkap hasil return transaction
    $transaction = DB::transaction(function () use ($group, $request) {
      // 1. Kurangi Saldo BANK
      $group->squid_dollar -= $request->amount;

      // 2. Tambah ke DOMPET CASH
      $group->bank_balance += $request->amount;

      $group->save();

      // 3. Buat Transaksi dan RETURN modelnya agar bisa dipakai di bawah
      return Transaction::create([
        'event_id' => $group->event_id,
        'from_type' => 'bank',
        'from_id' => 0,
        'to_type' => 'group',
        'to_id' => $group->id,
        'amount' => $request->amount,
        'reason' => 'BANK_WITHDRAWAL',
        'description' => 'Penarikan dari Bank ke Cash',
      ]);
    });

    // HANYA kirim data 'withdrawal_receipt'.
    // Tidak mengirim 'success', jadi SweetAlert tidak akan muncul.
    return back()->with('withdrawal_receipt', [
      'trx_id' => 'TRX-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
      'date' => $transaction->created_at->format('d M Y, H:i'),
      'amount' => $transaction->amount,
      'balance_bank' => $group->squid_dollar,
      'balance_cash' => $group->bank_balance,
    ]);
  }

  public function history()
  {
    $user = Auth::user();
    $membership = GroupMember::where('user_id', $user->id)->first();

    if (!$membership) {
      return redirect()->route('main.dashboard');
    }

    $group = Group::find($membership->group_id);

    // Ambil transaksi dimana Group ini sebagai PENGIRIM atau PENERIMA
    $transactions = Transaction::where(function ($q) use ($group) {
      $q->where('from_type', 'group')->where('from_id', $group->id);})->orWhere(function ($q) use ($group) {
      $q->where('to_type', 'group')->where('to_id', $group->id);})->orderBy('created_at', 'desc')->get();

    return view('main.history.index', compact('transactions', 'group'));
  }
}
