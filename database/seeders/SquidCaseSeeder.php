<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SquidCase;

class SquidCaseSeeder extends Seeder
{
    public function run(): void
    {
        SquidCase::create([
            'event_id' => 1,
            'title' => 'Studi Kasus Utama Batch 1',
            'description' => 'Deskripsi lengkap studi kasus...',
            'reward_dollar' => 10000000,
        ]);
    }
}
