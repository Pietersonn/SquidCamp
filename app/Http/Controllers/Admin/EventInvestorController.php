<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventInvestor;
use App\Models\User;
use App\Models\Transaction; // Pastikan Model Transaction diimport
use Illuminate\Http\Request;

class EventInvestorController extends Controller
{
    /**
     * TAMPILKAN DAFTAR INVESTOR
     */
    public function index(Event $event)
    {
        // Eager load user agar tidak N+1 query
        $investors = $event->eventInvestors()->with('user')->get();

        // PERBAIKAN: Kirim variabel $events untuk layout
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.events.investors.index', compact('event', 'investors', 'events'));
    }

    /**
     * FORM TAMBAH INVESTOR
     */
    public function create(Event $event)
    {
        // 1. Ambil ID investor yang SUDAH ada di event ini
        $existingInvestorIds = $event->eventInvestors()->pluck('user_id');

        // 2. Ambil User role 'investor' yang BELUM ada di event ini
        $availableInvestors = User::where('role', 'investor')
            ->whereNotIn('id', $existingInvestorIds)
            ->get();

        // PERBAIKAN: Kirim variabel $events
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.events.investors.create', compact('event', 'availableInvestors', 'events'));
    }

    /**
     * SIMPAN INVESTOR BARU
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'investment_balance' => 'required|integer|min:0',
        ]);

        $event->eventInvestors()->create([
            'user_id' => $request->user_id,
            'investment_balance' => $request->investment_balance,
        ]);

        return redirect()->route('admin.events.investors.index', $event->id)
            ->with('success', 'Investor berhasil ditambahkan ke event.');
    }

    /**
     * FORM EDIT SALDO
     */
    public function edit(Event $event, EventInvestor $investor)
    {
        // PERBAIKAN: Kirim variabel $events
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.events.investors.edit', compact('event', 'investor', 'events'));
    }

    /**
     * UPDATE SALDO
     */
    public function update(Request $request, Event $event, EventInvestor $investor)
    {
        $request->validate([
            'investment_balance' => 'required|integer|min:0',
        ]);

        $investor->update([
            'investment_balance' => $request->investment_balance,
        ]);

        return redirect()->route('admin.events.investors.index', $event->id)
            ->with('success', 'Saldo investasi berhasil diperbarui.');
    }

    /**
     * SHOW: Detail Investor & History
     */
    public function show(Event $event, EventInvestor $investor)
    {
        $investor->load('user');

        // Ambil riwayat transaksi
        $investments = Transaction::where('event_id', $event->id)
            ->where('from_type', 'user')
            ->where('from_id', $investor->user_id)
            ->where('to_type', 'group')
            ->with('group')
            ->latest()
            ->get();

        $totalInvested = $investments->sum('amount');
        $groupsFundedCount = $investments->unique('to_id')->count();

        // PERBAIKAN: Kirim variabel $events
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.events.investors.show', compact(
            'event',
            'investor',
            'investments',
            'totalInvested',
            'groupsFundedCount',
            'events'
        ));
    }

    /**
     * HAPUS INVESTOR
     */
    public function destroy(Event $event, EventInvestor $investor)
    {
        $investor->delete();
        return back()->with('success', 'Investor dihapus dari event ini.');
    }
}
