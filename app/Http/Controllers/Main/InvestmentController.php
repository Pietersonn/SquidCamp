<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Transaction;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cari Group di mana user ini menjadi anggota, dan Event-nya sedang aktif
        // PERBAIKAN: Mengganti 'status' => 'active' menjadi 'is_active' => true
        $group = Group::whereHas('members', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereHas('event', function($q) {
            $q->where('is_active', true);
        })->with('event')->first();

        // Jika tidak punya kelompok di event aktif, tendang balik
        if (!$group) {
            return redirect()->route('main.dashboard')->with('error', 'Anda tidak terdaftar di event aktif manapun.');
        }

        $event = $group->event;

        // 2. Ambil Transaksi Masuk (Investment)
        // Syarat: Event ID cocok, Penerima adalah Group ini, Pengirim adalah Investor
        $investments = Transaction::where('event_id', $event->id)
                        ->where('to_type', 'group')      // Penerima tipe Group
                        ->where('to_id', $group->id)     // ID Group penerima
                        ->where('from_type', 'investor') // Pengirim tipe Investor
                        ->with('fromUser')               // Pastikan relasi fromUser ada di model Transaction
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Total Investment Received
        $totalInvestment = $investments->sum('amount');

        return view('main.investments.index', compact('event', 'group', 'investments', 'totalInvestment'));
    }
}
