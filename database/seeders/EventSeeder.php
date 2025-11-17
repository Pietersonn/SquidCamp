<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tentukan tanggal event, misal besok.
        $eventDate = now()->addDay(1);

        // --- EVENT 1: BATCH 1 (AKTIF) ---
        // Ini akan mendapatkan ID: 1
        Event::create([
            'name' => 'SquidCamp Batch 1',
            'instansi' => 'Telkom Indonesia',
            'banner_image_path' => null,

            // ==========================================
            // == DISESUAIKAN DENGAN DB BARU ==
            'event_date' => $eventDate->toDateString(), // Hanya simpan tanggalnya
            // ==========================================

            // Timer Fase (menggunakan tanggal dari $eventDate, tapi dengan jam spesifik)
            'challenge_start_time' => $eventDate->copy()->setTime(10, 0, 0), // Besok jam 10:00
            'challenge_end_time'   => $eventDate->copy()->setTime(12, 0, 0), // Besok jam 12:00

            'case_start_time' => $eventDate->copy()->setTime(13, 0, 0), // Besok jam 13:00
            'case_end_time'   => $eventDate->copy()->setTime(15, 0, 0), // Besok jam 15:00

            'show_start_time' => $eventDate->copy()->setTime(16, 0, 0), // Besok jam 16:00
            'show_end_time'   => $eventDate->copy()->setTime(17, 0, 0), // Besok jam 17:00

            'is_active' => true,
        ]);

        // --- EVENT 2: BATCH 2 (NON-AKTIF / ARSIP) ---
        // Ini akan mendapatkan ID: 2
        Event::create([
            'name' => 'SquidCamp Batch 2 (Arsip)',
            'instansi' => 'Umum',
            'banner_image_path' => null,

            'event_date' => now()->subMonth(1)->toDateString(), // Event bulan lalu

            'challenge_start_time' => null,
            'challenge_end_time' => null,
            'case_start_time' => null,
            'case_end_time' => null,
            'show_start_time' => null,
            'show_end_time' => null,

            'is_active' => false,
        ]);
    }
}
