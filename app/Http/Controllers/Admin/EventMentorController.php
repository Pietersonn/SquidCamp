<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event; // Import Event
use App\Models\EventMentor; // Import model
use Illuminate\Http\Request;

class EventMentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        // Ambil data mentor yang ter-assign ke event ini
        $eventMentors = $event->eventMentors()->with('user')->get();

        // Cek: Model Event.php Anda BELUM punya relasi 'eventMentors()'.
        // Silakan tambahkan ini di app/Models/Event.php:
        // public function eventMentors() {
        //     return $this->hasMany(EventMentor::class);
        // }

        return view('admin.events.mentors.index', compact('event', 'eventMentors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
