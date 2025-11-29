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

        // 2. Ambil Grup Pengirim (Grup milik User yang sedang login)
        $senderMembership = GroupMember::where('user_id', $user->id)
            ->whereHas('event', function ($q) {
                $q->where('is_active', true);
            })
            ->first();

        if (!$senderMembership) {
            return back()->with('error', 'Anda tidak memiliki tim aktif.');
        }

        $senderGroup = Group::find($senderMembership->group_id);
        $receiverGroup = Group::find($request->to_group_id);

        // 3. Validasi Logika Bisnis
        // Cek apakah transfer ke diri sendiri?
        if ($senderGroup->id == $receiverGroup->id) {
            return back()->with('error', 'Tidak bisa transfer ke kelompok sendiri.');
        }

        // Cek apakah saldo cukup?
        if ($senderGroup->squid_dollar < $request->amount) {
            return back()->with('error', 'Saldo tim tidak mencukupi!');
        }

        // 4. Eksekusi Transaksi (Atomik)
        DB::beginTransaction();
        try {
            // Kurangi Saldo Pengirim
            $senderGroup->decrement('squid_dollar', $request->amount);

            // Tambah Saldo Penerima
            $receiverGroup->increment('squid_dollar', $request->amount);

            // Catat di Tabel Transaksi (History)
            Transaction::create([
                'event_id' => $senderMembership->event_id,
                'from_entity_type' => 'group',
                'from_entity_id' => $senderGroup->id,
                'to_entity_type' => 'group',
                'to_entity_id' => $receiverGroup->id,
                'amount' => $request->amount,
                'reason' => 'GROUP_TRANSFER',
            ]);

            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('main.dashboard')
                             ->with('success', 'Berhasil mengirim SQ$ ' . number_format($request->amount));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
