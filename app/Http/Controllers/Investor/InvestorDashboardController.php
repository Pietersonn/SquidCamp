<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\EventInvestor;
use App\Models\Group;
use App\Models\Transaction;

class InvestorDashboardController extends Controller
{
    public function selectEvent()
    {
        $user = Auth::user();

        $events = Event::whereHas('investors', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('is_active', true)->get();

        return view('investor.select-event', compact('events'));
    }

    public function dashboard($eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        // Ambil data investor (termasuk saldo investment_balance)
        $investorData = EventInvestor::where('event_id', $eventId)
                        ->where('user_id', $user->id)
                        ->firstOrFail();

        $groups = Group::with('members')
                    ->where('event_id', $eventId)
                    ->get();

        $transactions = Transaction::where('event_id', $eventId)
                        ->where('from_type', 'investor')
                        ->where('from_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();

        return view('investor.dashboard', compact('event', 'investorData', 'groups', 'transactions'));
    }

    public function invest(Request $request, $eventId)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        DB::beginTransaction();
        try {
            $investor = EventInvestor::where('event_id', $eventId)
                        ->where('user_id', $user->id)
                        ->lockForUpdate()
                        ->firstOrFail();

            // Cek Saldo Investor (Gunakan investment_balance)
            if ($investor->investment_balance < $amount) {
                return back()->with('error', 'Saldo Anda tidak mencukupi untuk investasi ini.');
            }

            // 1. Kurangi Saldo Investor
            $investor->investment_balance -= $amount;
            $investor->save();

            // 2. Tambah Saldo Kelompok ke BANK BALANCE (Sesuai Request)
            $group = Group::lockForUpdate()->find($request->group_id);
            $group->bank_balance += $amount;
            $group->save();

            // 3. Catat Transaksi
            Transaction::create([
                'event_id' => $eventId,
                'from_type' => 'investor',
                'from_id' => $user->id,
                'to_type' => 'group',
                'to_id' => $group->id,
                'amount' => $amount,
                'reason' => 'Investment',
                'description' => 'Suntikan dana dari Investor ' . $user->name,
            ]);

            DB::commit();
            return back()->with('success', 'Berhasil investasi $' . number_format($amount) . ' ke ' . $group->name);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
