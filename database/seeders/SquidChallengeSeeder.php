<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SquidChallenge;

class SquidChallengeSeeder extends Seeder
{
    public function run(): void
    {
        SquidChallenge::create([
            'event_id' => 1,
            'title' => 'Challenge Tier 1 (700k)',
            'description' => 'Deskripsi soal challenge tier 1.',
            'reward_dollar' => 700000,
        ]);
        SquidChallenge::create([
            'event_id' => 1,
            'title' => 'Challenge Tier 2 (500k)',
            'description' => 'Deskripsi soal challenge tier 2.',
            'reward_dollar' => 500000,
        ]);
        SquidChallenge::create([
            'event_id' => 1,
            'title' => 'Challenge Tier 3 (300k)',
            'description' => 'Deskripsi soal challenge tier 3.',
            'reward_dollar' => 300000,
        ]);
    }
}
