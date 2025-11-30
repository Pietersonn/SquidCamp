<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil waktu SEKARANG
        $now = Carbon::now();

        // --- EVENT 1: BATCH 1 (HARI INI & AKTIF) ---
        Event::create([
            'name' => 'SquidCamp Batch 1 (Live)',
            // 'instansi' => 'Telkom', // <-- INI HARUS DIHAPUS KARENA KOLOMNYA SUDAH TIDAK ADA
            'banner_image_path' => null,
            'event_date' => $now->toDateString(),

            // FASE 1: CHALLENGE (Setting agar SEDANG BERJALAN)
            // Mulai 30 menit yang lalu -> Selesai 2 jam lagi
            'challenge_start_time' => $now->copy()->subMinutes(30),
            'challenge_end_time'   => $now->copy()->addHours(2),

            // FASE 2: CASE (Mulai setelah Challenge selesai)
            'case_start_time' => $now->copy()->addHours(3),
            'case_end_time'   => $now->copy()->addHours(5),

            // FASE 3: SHOW (Malam nanti)
            'show_start_time' => $now->copy()->addHours(6),
            'show_end_time'   => $now->copy()->addHours(8),

            'is_active' => true,
        ]);

        // --- EVENT 2: BATCH LAMA (ARSIP) ---
        Event::create([
            'name' => 'SquidCamp Batch 0 (Arsip)',
            // 'instansi' => 'Umum', // <-- INI JUGA DIHAPUS
            'banner_image_path' => null,
            'event_date' => $now->copy()->subMonth(1)->toDateString(),

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
