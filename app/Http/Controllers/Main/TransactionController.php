<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Transaction;
use App\Models\Event;

class TransactionController extends Controller
{
    // ... (Fungsi transfer biarkan tetap sama) ...
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
            ->latest()
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

            if ($senderGroup->bank_balance < $amount) {
                DB::rollBack();
                return back()->with('error', 'Saldo Bank tidak cukup untuk transfer!');
            }

            $senderGroup->decrement('bank_balance', $amount);
            $receiverGroup->increment('bank_balance', $amount);

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

    // [LOGIC DIPERBAIKI DISINI]
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
        $event = Event::find($group->event_id);

        // 1. Cek Saldo Bank User (Cukup gak buat bayar ke Admin?)
        if ($group->bank_balance < $request->amount) {
            return back()->with('error', 'Saldo di Squid Bank tidak mencukupi!');
        }

        // 2. Cek Stok Uang Fisik Admin (Ada gak uang cash buat dikasih ke User?)
        if ($event->central_cash_balance < $request->amount) {
            return back()->with('error', 'Bank Pusat (Admin) kehabisan stok uang tunai!');
        }

        $transaction = DB::transaction(function () use ($group, $event, $request) {
            $amount = $request->amount;

            // --- SISI PESERTA ---
            // Saldo Bank Berkurang (Dikirim ke Admin)
            $group->decrement('bank_balance', $amount);
            // Uang Cash Bertambah (Diterima dari Admin)
            $group->increment('squid_dollar', $amount);

            // --- SISI ADMIN (LOGIC BARU) ---
            // Uang Fisik Admin Berkurang (Dikasih ke Peserta) -> Total Cash Berkurang
            $event->decrement('central_cash_balance', $amount);

            // Saldo Bank Admin Bertambah (Menerima transfer digital dari Peserta) -> Total Cadangan Nambah
            $event->increment('central_bank_balance', $amount);

            // Catat Transaksi
            return Transaction::create([
                'event_id' => $group->event_id,
                'from_type' => 'bank', // Dianggap dari sistem Bank
                'from_id' => 0,
                'to_type' => 'group',
                'to_id' => $group->id,
                'amount' => $amount,
                'reason' => 'BANK_WITHDRAWAL',
                'description' => 'Tarik Tunai (Admin Cash Out)'
            ]);
        });

        return back()->with('withdrawal_receipt', [
            'trx_id' => 'TRX-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
            'date' => $transaction->created_at->format('d M Y, H:i'),
            'amount' => $transaction->amount,
            'balance_bank' => $group->bank_balance,
            'balance_cash' => $group->squid_dollar,
        ]);
    }

    // ... (History tetap sama) ...
    public function history()
    {
        $user = Auth::user();
        $membership = GroupMember::where('user_id', $user->id)->latest()->first();
        if (!$membership) return redirect()->route('main.dashboard');

        $group = Group::find($membership->group_id);
        $transactions = Transaction::where(function ($q) use ($group) {
                $q->where('from_type', 'group')->where('from_id', $group->id);
            })
            ->orWhere(function ($q) use ($group) {
                $q->where('to_type', 'group')->where('to_id', $group->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('main.history.index', compact('transactions', 'group'));
    }

}
