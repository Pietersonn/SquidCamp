<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Challenge;
use Illuminate\Http\Request;

class EventChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge yang SUDAH ada di event ini.
     */
    public function index(Event $event)
    {
        // Ambil challenge yang sudah terhubung dengan event ini
        $challenges = $event->challenges()->orderBy('price', 'asc')->get();

        return view('admin.events.challenges.index', compact('event', 'challenges'));
    }

    /**
     * Form untuk memilih challenge master untuk dimasukkan ke event.
     */
    public function create(Event $event)
    {
        // Ambil ID challenge yang sudah ada di event ini agar tidak muncul lagi
        $existingIds = $event->challenges()->pluck('challenges.id');

        // Ambil challenge Master yang BELUM ada di event ini
        // Urutkan berdasarkan harga agar mudah dicari
        $challenges = Challenge::whereNotIn('id', $existingIds)
                        ->orderBy('price', 'asc')
                        ->latest()
                        ->get();

        return view('admin.events.challenges.create', compact('event', 'challenges'));
    }

    /**
     * Menyimpan challenge terpilih ke event.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'challenge_ids' => 'required|array',
            'challenge_ids.*' => 'exists:challenges,id',
        ]);

        // Attach challenge ke event (syncWithoutDetaching agar data lama tidak hilang)
        $event->challenges()->syncWithoutDetaching($request->challenge_ids);

        $count = count($request->challenge_ids);
        return redirect()->route('admin.events.challenges.index', $event->id)
                         ->with('success', "$count Challenge berhasil ditambahkan ke event!");
    }

    /**
     * Menghapus challenge dari event.
     */
    public function destroy(Event $event, Challenge $challenge)
    {
        $event->challenges()->detach($challenge->id);
        return back()->with('success', 'Challenge berhasil dihapus dari event ini.');
    }
}
