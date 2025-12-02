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
    // 1. TRANSFER ANTAR KELOMPOK (VIA BANK)
    public function transfer(Request $request)
    {
        $request->validate([
            'to_group_id' => 'required|exists:groups,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();
        $amount = (int) $request->amount;

        // Ambil membership user di event aktif
        $senderMembership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function ($q) {
                $q->where('is_active', true);
            })
            ->latest()
            ->first();

        if (!$senderMembership) {
            return back()->with('error', 'Anda tidak tergabung dalam tim aktif.');
        }

        DB::beginTransaction();
        try {
            // Lock row untuk mencegah race condition
            $senderGroup = Group::where('id', $senderMembership->group_id)->lockForUpdate()->first();
            $receiverGroup = Group::where('id', $request->to_group_id)->lockForUpdate()->first();

            // Validasi Self-Transfer
            if ($senderGroup->id == $receiverGroup->id) {
                DB::rollBack();
                return back()->with('error', 'Tidak bisa transfer ke tim sendiri.');
            }

            // [FIX] CEK SALDO BANK (bank_balance)
            // Transfer menggunakan uang di Bank, bukan Cash.
            if ($senderGroup->bank_balance < $amount) {
                DB::rollBack();
                return back()->with('error', 'Saldo Bank tidak cukup untuk transfer! Pastikan uang ada di tabungan.');
            }

            // Proses Transfer (Bank ke Bank)
            $senderGroup->decrement('bank_balance', $amount);
            $receiverGroup->increment('bank_balance', $amount);

            // Catat Transaksi
            Transaction::create([
                'event_id' => $senderMembership->event_id,
                'from_type' => 'group',
                'from_id' => $senderGroup->id,
                'to_type' => 'group',
                'to_id' => $receiverGroup->id,
                'amount' => $amount,
                'reason' => 'GROUP_TRANSFER',
                'description' => 'Transfer Bank ke ' . $receiverGroup->name
            ]);

            DB::commit();
            return back()->with('success', 'Berhasil transfer SQ$ ' . number_format($amount) . ' ke ' . $receiverGroup->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal transfer: ' . $e->getMessage());
        }
    }

    // 2. TARIK TUNAI (BANK -> CASH)
    public function withdrawFromBank(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $membership = GroupMember::where('user_id', $user->id)->latest()->first();

        if (!$membership) return back()->with('error', 'Tim tidak ditemukan.');

        $group = Group::find($membership->group_id);

        // [FIX] Cek Saldo BANK (bank_balance) sebagai sumber dana
        if ($group->bank_balance < $request->amount) {
            return back()->with('error', 'Saldo di Squid Bank tidak mencukupi!');
        }

        $transaction = DB::transaction(function () use ($group, $request) {
            // 1. Kurangi Saldo BANK
            $group->decrement('bank_balance', $request->amount);

            // 2. Tambah ke DOMPET CASH (squid_dollar)
            $group->increment('squid_dollar', $request->amount);

            // 3. Catat Transaksi
            return Transaction::create([
                'event_id' => $group->event_id,
                'from_type' => 'group', // Dari akun bank grup
                'from_id' => $group->id,
                'to_type' => 'group',   // Ke dompet grup (self)
                'to_id' => $group->id,
                'amount' => $request->amount,
                'reason' => 'BANK_WITHDRAWAL',
                'description' => 'Tarik Tunai dari Bank'
            ]);
        });

        // Kirim data struk ke session (tanpa notifikasi success agar popup struk muncul)
        return back()->with('withdrawal_receipt', [
            'trx_id' => 'TRX-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
            'date' => $transaction->created_at->format('d M Y, H:i'),
            'amount' => $transaction->amount,
            'balance_bank' => $group->bank_balance,   // Sisa di Bank
            'balance_cash' => $group->squid_dollar,   // Total di Cash
        ]);
    }

    // 3. HALAMAN HISTORY
    public function history()
    {
        $user = Auth::user();
        $membership = GroupMember::where('user_id', $user->id)->latest()->first();

        if (!$membership) {
            return redirect()->route('main.dashboard');
        }

        $group = Group::find($membership->group_id);

        // Ambil semua transaksi yang melibatkan grup ini
        $transactions = Transaction::where(function ($q) use ($group) {
                $q->where('from_type', 'group')->where('from_id', $group->id); // Uang Keluar
            })
            ->orWhere(function ($q) use ($group) {
                $q->where('to_type', 'group')->where('to_id', $group->id); // Uang Masuk
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('main.history.index', compact('transactions', 'group'));
    }
}
