<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // --- EVENT 1 (LIVE) ---
        Event::create([
            'name' => 'SquidCamp Batch 1 (Live)',
            'instansi' => 'Telkom Indonesia',
            'banner_image_path' => null,
            'event_date' => $now->toDateString(),

            // Timer
            'challenge_start_time' => $now->copy()->subMinutes(30),
            'challenge_end_time'   => $now->copy()->addHours(2),
            'case_start_time'      => $now->copy()->addHours(3),
            'case_end_time'        => $now->copy()->addHours(5),
            'show_start_time'      => $now->copy()->addHours(6),
            'show_end_time'        => $now->copy()->addHours(8),

            'is_active' => true,
            'is_finished' => false,

            // MODAL AWAL ADMIN (BANK SENTRAL)
            'central_bank_balance' => 0,
            'central_cash_balance' => 5000000,
        ]);

        // --- EVENT 2 (ARSIP) ---
        Event::create([
            'name' => 'SquidCamp Batch 0 (Arsip)',
            'instansi' => 'Umum',
            'event_date' => $now->copy()->subMonth(1)->toDateString(),
            'is_active' => false,
            'is_finished' => true,
            'central_bank_balance' => 0,
            'central_cash_balance' => 0,
        ]);
    }
}
