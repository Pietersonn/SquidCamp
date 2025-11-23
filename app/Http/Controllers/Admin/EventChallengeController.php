<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Challenge;
use Illuminate\Http\Request;

class EventChallengeController extends Controller
{
    /**
     * TAMPILKAN semua challenge untuk event
     */
    public function index(Event $event)
    {
        $selected_challenges = $event->challenges;
        return view('admin.events.challenges.index', compact('event', 'selected_challenges'));
    }

    /**
     * HALAMAN CREATE (Pilih Challenge)
     */
    public function create(Event $event)
    {
        $selected_ids = $event->challenges->pluck('id')->toArray();
        // Ambil challenge yang belum dipilih
        $challenges = Challenge::whereNotIn('id', $selected_ids)->get();

        return view('admin.events.challenges.create', compact('event', 'challenges'));
    }

    /**
     * SIMPAN Challenge ke Event (BISA BANYAK SEKALIGUS)
     */
    public function store(Request $request, Event $event)
    {
        // Validasi Array
        $request->validate([
            'challenge_ids' => 'required|array',
            'challenge_ids.*' => 'exists:challenges,id'
        ], [
            'challenge_ids.required' => 'Pilih setidaknya satu challenge.'
        ]);

        // Gunakan syncWithoutDetaching:
        // Ini akan menambahkan ID baru ke pivot table tanpa menghapus ID yang sudah ada sebelumnya.
        // Juga otomatis mencegah duplikasi jika ID yang sama dikirim (meski sudah kita filter di view).
        $event->challenges()->syncWithoutDetaching($request->challenge_ids);

        $count = count($request->challenge_ids);

        return redirect()->route('admin.events.challenges.index', $event->id)
            ->with('success', "$count Challenge berhasil ditambahkan ke Event.");
    }

    /**
     * HALAMAN EDIT (Ganti Challenge)
     */
    public function edit(Event $event, Challenge $challenge)
    {
        $selected_ids = $event->challenges->pluck('id')->toArray();

        // Exclude selected lain, tapi include challenge ini sendiri agar muncul di dropdown
        $available_challenges = Challenge::whereNotIn('id', array_diff($selected_ids, [$challenge->id]))->get();

        return view('admin.events.challenges.edit', compact('event', 'challenge', 'available_challenges'));
    }

    /**
     * UPDATE Challenge di Event
     */
    public function update(Request $request, Event $event, Challenge $challenge)
    {
        $request->validate([
            'challenge_id' => 'required|exists:challenges,id'
        ]);

        // Hapus yang lama
        $event->challenges()->detach($challenge->id);

        // Tambah yang baru (jika belum ada)
        $event->challenges()->syncWithoutDetaching([$request->challenge_id]);

        return redirect()->route('admin.events.challenges.index', $event->id)
            ->with('success', 'Challenge berhasil diperbarui.');
    }

    /**
     * HAPUS Challenge dari Event
     */
    public function destroy(Event $event, Challenge $challenge)
    {
        $event->challenges()->detach($challenge->id);

        return back()->with('success', 'Challenge berhasil dihapus dari event.');
    }
}
