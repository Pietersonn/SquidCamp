<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guideline;
use Illuminate\Http\Request;

class EventGuidelineController extends Controller
{
    /**
     * TAMPILKAN semua guideline untuk event
     */
    public function index(Event $event)
    {
        $selected_guidelines = $event->guidelines;
        return view('admin.events.guidelines.index', compact('event', 'selected_guidelines'));
    }

    /**
     * HALAMAN CREATE (Pilih Guideline)
     */
    public function create(Event $event)
    {
        // Ambil ID yang sudah dipilih agar tidak muncul di opsi
        $selected_ids = $event->guidelines->pluck('id')->toArray();

        // Ambil guideline yang belum dipilih
        $guidelines = Guideline::whereNotIn('id', $selected_ids)->get();

        return view('admin.events.guidelines.create', compact('event', 'guidelines'));
    }

    /**
     * SIMPAN Guideline ke Event (BISA BANYAK SEKALIGUS)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'guideline_ids' => 'required|array',
            'guideline_ids.*' => 'exists:guidelines,id'
        ], [
            'guideline_ids.required' => 'Pilih setidaknya satu guideline.'
        ]);

        // Simpan banyak sekaligus tanpa menghapus yang lama
        $event->guidelines()->syncWithoutDetaching($request->guideline_ids);

        $count = count($request->guideline_ids);

        return redirect()->route('admin.events.guidelines.index', $event->id)
            ->with('success', "$count Guideline berhasil ditambahkan ke Event.");
    }

    /**
     * HALAMAN EDIT (Ganti Guideline)
     */
    public function edit(Event $event, Guideline $guideline)
    {
        $selected_ids = $event->guidelines->pluck('id')->toArray();

        // Exclude selected lain, tapi include current guideline
        $available_guidelines = Guideline::whereNotIn('id', array_diff($selected_ids, [$guideline->id]))->get();

        return view('admin.events.guidelines.edit', compact('event', 'guideline', 'available_guidelines'));
    }

    /**
     * UPDATE Guideline di Event
     */
    public function update(Request $request, Event $event, Guideline $guideline)
    {
        $request->validate([
            'guideline_id' => 'required|exists:guidelines,id'
        ]);

        // Hapus yang lama
        $event->guidelines()->detach($guideline->id);

        // Tambah yang baru (jika belum ada)
        $event->guidelines()->syncWithoutDetaching([$request->guideline_id]);

        return redirect()->route('admin.events.guidelines.index', $event->id)
            ->with('success', 'Guideline berhasil diperbarui.');
    }

    /**
     * HAPUS Guideline dari Event
     */
    public function destroy(Event $event, Guideline $guideline)
    {
        $event->guidelines()->detach($guideline->id);

        return back()->with('success', 'Guideline berhasil dihapus dari event.');
    }
}
