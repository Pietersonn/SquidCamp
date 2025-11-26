<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Challenge;
use App\Models\Guideline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar semua event
     */
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Form untuk membuat event baru
     */
    public function create()
    {
        $challenges = Challenge::all();
        $guidelines = Guideline::all();

        return view('admin.events.create', compact('challenges', 'guidelines'));
    }

    /**
     * Menyimpan event baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'challenge_ids' => 'array',
            'guideline_ids' => 'array',
        ]);

        $path = null;
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('event_banners', 'public');
        }

        $event = Event::create([
            'name' => $request->name,
            'instansi' => $request->instansi,
            'banner_image_path' => $path,

            'event_date' => $request->event_date,

            'event_start_time' => $request->event_start_time,
            'event_end_time' => $request->event_end_time,

            'challenge_start_time' => $request->challenge_start_time,
            'challenge_end_time' => $request->challenge_end_time,

            'case_start_time' => $request->case_start_time,
            'case_end_time' => $request->case_end_time,

            'show_start_time' => $request->show_start_time,
            'show_end_time' => $request->show_end_time,

            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // Sync relationships (jika ada input array ID)
        $event->challenges()->sync($request->challenge_ids ?? []);
        $event->guidelines()->sync($request->guideline_ids ?? []);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Menampilkan detail event (Dashboard Event)
     */
    public function show(Event $event)
    {
        // Hitung data statistik untuk dashboard event
        $groupCount = $event->groups()->count();
        $memberCount = $event->members()->count(); // Pastikan relasi members ada di model Event (via groups)
        $investorCount = $event->investors()->count();

        // Ambil leaderboard kelompok berdasarkan uang terbanyak
        $leaderboardGroups = $event->groups()
            ->withCount('members')
            ->orderByDesc('squid_dollar')
            ->limit(5) // Batasi 5 besar
            ->get();

        return view('admin.events.show', compact(
            'event',
            'groupCount',
            'memberCount',
            'investorCount',
            'leaderboardGroups'
        ));
    }

    /**
     * Form untuk mengedit event
     */
    public function edit(Event $event)
    {
        $challenges = Challenge::all();
        $guidelines = Guideline::all();

        return view('admin.events.edit', compact('event', 'challenges', 'guidelines'));
    }

    /**
     * Memperbarui data event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'challenge_ids' => 'array',
            'guideline_ids' => 'array',
        ]);

        $path = $event->banner_image_path;

        // Cek jika ada upload banner baru
        if ($request->hasFile('banner_image')) {
            // Hapus banner lama jika ada
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('banner_image')->store('event_banners', 'public');
        }

        $event->update([
            'name' => $request->name,
            'instansi' => $request->instansi,
            'banner_image_path' => $path,

            'event_date' => $request->event_date,

            'event_start_time' => $request->event_start_time,
            'event_end_time' => $request->event_end_time,

            'challenge_start_time' => $request->challenge_start_time,
            'challenge_end_time' => $request->challenge_end_time,

            'case_start_time' => $request->case_start_time,
            'case_end_time' => $request->case_end_time,

            'show_start_time' => $request->show_start_time,
            'show_end_time' => $request->show_end_time,

            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // Sync ulang jika ada perubahan pada master data tantangan/guideline
        $event->challenges()->sync($request->challenge_ids ?? []);
        $event->guidelines()->sync($request->guideline_ids ?? []);

        // REDIRECT KE HALAMAN SHOW (DETAIL)
        return redirect()->route('admin.events.show', $event->id)
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Menghapus event
     */
    public function destroy(Event $event)
    {
        if ($event->banner_image_path) {
            Storage::disk('public')->delete($event->banner_image_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
