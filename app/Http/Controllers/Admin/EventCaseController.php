<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Cases;
use Illuminate\Http\Request;

class EventCaseController extends Controller
{
    public function index(Event $event)
    {
        $selected_cases = $event->cases;
        return view('admin.events.cases.index', compact('event', 'selected_cases'));
    }

    public function create(Event $event)
    {
        $selected_ids = $event->cases->pluck('id')->toArray();
        $cases = Cases::whereNotIn('id', $selected_ids)->get();

        return view('admin.events.cases.create', compact('event', 'cases'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'case_ids' => 'required|array',
            'case_ids.*' => 'exists:cases,id'
        ]);

        $event->cases()->syncWithoutDetaching($request->case_ids);
        $count = count($request->case_ids);

        return redirect()->route('admin.events.cases.index', $event->id)
            ->with('success', "$count Case berhasil ditambahkan ke Event.");
    }

    public function edit(Event $event, Cases $case)
    {
        $selected_ids = $event->cases->pluck('id')->toArray();
        $available_cases = Cases::whereNotIn('id', array_diff($selected_ids, [$case->id]))->get();

        return view('admin.events.cases.edit', compact('event', 'case', 'available_cases'));
    }

    public function update(Request $request, Event $event, Cases $case)
    {
        $request->validate([
            'new_case_id' => 'required|exists:cases,id'
        ]);

        $event->cases()->detach($case->id);
        $event->cases()->syncWithoutDetaching([$request->new_case_id]);

        return redirect()->route('admin.events.cases.index', $event->id)
            ->with('success', 'Case berhasil diperbarui.');
    }

    public function destroy(Event $event, Cases $case)
    {
        $event->cases()->detach($case->id);
        return back()->with('success', 'Case berhasil dihapus dari event.');
    }
}
