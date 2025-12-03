<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Group;
use App\Models\Event;

class SquidBankController extends Controller
{
    public function index(Event $event)
    {

        $adminBankReserve = $event->central_bank_balance; // Cadangan Bank (Digital)
        $adminPhysicalCash = $event->central_cash_balance; // Uang Fisik Admin

        $totalCashCirculation = Group::where('event_id', $event->id)->sum('squid_dollar');
        $transactions = Transaction::where('event_id', $event->id)
            ->latest()
            ->get();

        return view('admin.events.squidbank.index', compact(
            'event',
            'transactions',
            'adminBankReserve',
            'adminPhysicalCash',
            'totalCashCirculation'
        ));
    }
}
