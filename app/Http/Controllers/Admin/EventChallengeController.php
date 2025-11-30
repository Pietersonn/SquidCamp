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
        // Variabel yang dikirim ke view adalah '$challenges'
        $challenges = $event->challenges;

        return view('admin.events.challenges.index', compact('event', 'challenges'));
    }

    /**
     * Form untuk memilih challenge master untuk dimasukkan ke event.
     */
    public function create(Event $event)
    {
        // Ambil ID challenge yang sudah ada di event ini
        $existingIds = $event->challenges()->pluck('challenges.id');

        // Ambil challenge Master yang BELUM ada di event ini (agar tidak duplikat)
        // Kita kirim sebagai '$challenges' ke view create
        $challenges = Challenge::whereNotIn('id', $existingIds)->latest()->get();

        return view('admin.events.challenges.create', compact('event', 'challenges'));
    }

    /**
     * Menyimpan challenge terpilih ke event.
     */
    public function store(Request $request, Event $event)
    {
        // Validasi array ID
        $request->validate([
            'challenge_ids' => 'required|array',
            'challenge_ids.*' => 'exists:challenges,id',
        ]);

        // Sync tanpa menghapus yang lama (attach banyak sekaligus)
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
        return back()->with('success', 'Challenge dihapus dari event ini.');
    }
}
