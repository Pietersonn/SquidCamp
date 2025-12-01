<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Group;
use App\Models\Event;

class SquidBankController extends Controller
{
    // Terima parameter $event karena route-nya di dalam prefix events/{event}
    public function index(Event $event)
    {
        // 1. Hitung Total Uang Bank HANYA untuk event ini
        // Ingat: 'squid_dollar' sekarang kita anggap sebagai BANK/TABUNGAN
        $totalBankReserve = Group::where('event_id', $event->id)->sum('squid_dollar');

        // 2. Hitung Total Uang Beredar (CASH) untuk perbandingan (Optional)
        $totalCashCirculation = Group::where('event_id', $event->id)->sum('bank_balance');

        // 3. Ambil Riwayat Transaksi Bank HANYA untuk event ini
        $transactions = Transaction::where('event_id', $event->id)
            ->where(function($q) {
                // Kita ambil transaksi yang melibatkan Bank
                // Penarikan: from_type='bank' (Bank -> Cash)
                // Setoran (jika ada nanti): to_type='bank'
                $q->where('from_type', 'bank')
                  ->orWhere('to_type', 'bank');
            })
            ->latest()
            ->get();

        return view('admin.events.squidbank.index', compact('event', 'transactions', 'totalBankReserve', 'totalCashCirculation'));
    }
}
