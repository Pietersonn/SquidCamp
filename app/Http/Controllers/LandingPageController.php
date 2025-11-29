<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class LandingPageController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Menggunakan 'event_date' sesuai struktur database kamu
        $events = Event::where('is_active', true)
                        ->orWhere('event_date', '>=', now()->toDateString()) // Cek tanggal hari ini atau masa depan
                        ->orderBy('event_date', 'asc')
                        ->get();

        return view('index', compact('events'));
    }
}
