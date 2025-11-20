<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventChallenge;
use App\Models\Challenge;
use Illuminate\Http\Request;

class EventChallengeController extends Controller
{
    /**
     * TAMPILKAN semua challenge untuk event
     */
    public function index(Event $event)
    {
        $selected = $event->challenges; // dari belongsToMany
        $all = Challenge::all();       // master challenge

        return view('admin.event_challenges.index', compact('event', 'selected', 'all'));
    }

    /**
     * TAMBAH challenge ke event
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'challenge_id' => 'required|exists:challenges,id'
        ]);

        // Cek duplikasi
        if ($event->challenges()->where('challenge_id', $request->challenge_id)->exists()) {
            return back()->with('error', 'Challenge sudah dipilih untuk event ini.');
        }

        EventChallenge::create([
            'event_id' => $event->id,
            'challenge_id' => $request->challenge_id
        ]);

        return back()->with('success', 'Challenge berhasil ditambahkan.');
    }

    /**
     * HAPUS challenge dari event
     */
    public function destroy(Event $event, EventChallenge $eventChallenge)
    {
        $eventChallenge->delete();

        return back()->with('success', 'Challenge berhasil dihapus dari event.');
    }
}
