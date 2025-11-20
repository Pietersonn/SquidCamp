<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventGuideline;
use App\Models\Guideline;
use Illuminate\Http\Request;

class EventGuidelineController extends Controller
{
    /**
     * TAMPILKAN semua guideline untuk event tertentu
     */
    public function index(Event $event)
    {
        $selected = $event->guidelines; // dari belongsToMany
        $all = Guideline::all();       // master guideline

        return view('admin.event_guidelines.index', compact('event', 'selected', 'all'));
    }

    /**
     * TAMBAH guideline ke event
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'guideline_id' => 'required|exists:guidelines,id'
        ]);

        // Cegah duplikasi
        if ($event->guidelines()->where('guideline_id', $request->guideline_id)->exists()) {
            return back()->with('error', 'Guideline sudah terdaftar di event ini.');
        }

        EventGuideline::create([
            'event_id' => $event->id,
            'guideline_id' => $request->guideline_id
        ]);

        return back()->with('success', 'Guideline berhasil ditambahkan.');
    }

    /**
     * HAPUS guideline dari event
     */
    public function destroy(Event $event, EventGuideline $eventGuideline)
    {
        $eventGuideline->delete();

        return back()->with('success', 'Guideline berhasil dihapus dari event.');
    }
}
