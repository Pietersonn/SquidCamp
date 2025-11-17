<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- PENTING: Tambahkan ini for file upload

class EventController extends Controller
{
    /**
     * Menampilkan list semua event (Halaman Card)
     */
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.events.index', compact('events')); // Pastikan path view benar
    }

    /**
     * Menampilkan form untuk BUAT event baru
     */
    public function create()
    {
        return view('admin.events.create'); // Pastikan path view benar
    }

    /**
     * 2. GANTI FUNGSI store()
     * Menyimpan event baru ke database (VERSI BARU DENGAN UPLOAD)
     */
    public function store(Request $request)
    {
        // Validasi (termasuk banner & instansi)
        $request->validate([
            'name' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_start_time' => 'nullable|date',
            'event_end_time' => 'nullable|date|after_or_equal:event_start_time',
            'challenge_start_time' => 'nullable|date',
            'challenge_end_time' => 'nullable|date|after_or_equal:challenge_start_time',
            'case_start_time' => 'nullable|date',
            'case_end_time' => 'nullable|date|after_or_equal:case_start_time',
            'show_start_time' => 'nullable|date',
            'show_end_time' => 'nullable|date|after_or_equal:show_start_time',
        ]);

        $path = null;
        if ($request->hasFile('banner_image')) {
            // Simpan gambar ke storage/app/public/event_banners
            $path = $request->file('banner_image')->store('event_banners', 'public');
        }

        // Buat event baru dengan SEMUA field
        Event::create([
            'name' => $request->name,
            'instansi' => $request->instansi, // <-- DISIMPAN
            'banner_image_path' => $path,     // <-- DISIMPAN
            'event_start_time' => $request->event_start_time,
            'event_end_time' => $request->event_end_time,
            'challenge_start_time' => $request->challenge_start_time, // <-- DISIMPAN
            'challenge_end_time' => $request->challenge_end_time,     // <-- DISIMPAN
            'case_start_time' => $request->case_start_time,         // <-- DISIMPAN
            'case_end_time' => $request->case_end_time,             // <-- DISIMPAN
            'show_start_time' => $request->show_start_time,         // <-- DISIMPAN
            'show_end_time' => $request->show_end_time,             // <-- DISIMPAN
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * 3. Menampilkan halaman "Detail Hub"
     * (Kode Anda sudah benar, tapi pastikan relasi di Model Event ada)
     */
    public function show(Event $event)
    {
        $groupCount = $event->groups()->count();
        $memberCount = $event->members()->count();
        $investorCount = $event->eventInvestors()->count();

        $leaderboardGroups = $event->groups()
        ->withCount('members')
        ->orderByDesc('squid_dollar')
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
     * 4. Menampilkan form untuk EDIT event
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event')); // Pastikan path view benar
    }

    /**
     * 5. GANTI FUNGSI update()
     * Menyimpan perubahan dari EDIT (VERSI BARU DENGAN UPLOAD)
     */
    public function update(Request $request, Event $event)
    {
        // Validasi (sama seperti store)
        $request->validate([
            'name' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_start_time' => 'nullable|date',
            // ... (validasi 6 timer lainnya) ...
        ]);

        // Ambil path lama
        $path = $event->banner_image_path;

        if ($request->hasFile('banner_image')) {
            // Hapus gambar lama jika ada
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            // Simpan gambar baru
            $path = $request->file('banner_image')->store('event_banners', 'public');
        }

        // Update event dengan SEMUA field
        $event->update([
            'name' => $request->name,
            'instansi' => $request->instansi, // <-- DIUPDATE
            'banner_image_path' => $path,     // <-- DIUPDATE
            'event_start_time' => $request->event_start_time,
            'event_end_time' => $request->event_end_time,
            'challenge_start_time' => $request->challenge_start_time, // <-- DIUPDATE
            'challenge_end_time' => $request->challenge_end_time,     // <-- DIUPDATE
            'case_start_time' => $request->case_start_time,         // <-- DIUPDATE
            'case_end_time' => $request->case_end_time,             // <-- DIUPDATE
            'show_start_time' => $request->show_start_time,         // <-- DIUPDATE
            'show_end_time' => $request->show_end_time,             // <-- DIUPDATE
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * 6. GANTI FUNGSI destroy()
     * Menghapus event (VERSI BARU DENGAN HAPUS GAMBAR)
     */
    public function destroy(Event $event)
    {
        // Hapus gambar dari storage sebelum hapus data
        if ($event->banner_image_path) {
            Storage::disk('public')->delete($event->banner_image_path);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
