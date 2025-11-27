<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan ini SANGAT PENTING untuk foreign key constraints
        $this->call([
            UserSeeder::class,          // 1. Buat user dulu
            EventSeeder::class,         // 2. Buat event-nya
            GroupSeeder::class,         // 3. Buat grup & assign mentor
            EventInvestorSeeder::class, // 4. Assign investor & saldonya
            GroupMemberSeeder::class,   // 5. Masukkan peserta ke grup
        ]);
    }
}
