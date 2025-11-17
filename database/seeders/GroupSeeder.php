<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Grup 1 (Event 1, Mentor 2)
        Group::create([
            'event_id' => 1,
            'name' => 'Grup Naga',
            'mentor_id' => 2,
            'squid_dollar' => 100000,
        ]);

        // Grup 2 (Event 1, Tanpa Mentor)
        Group::create([
            'event_id' => 1, // Asumsi Event ID 1
            'name' => 'Grup Elang',
            'mentor_id' => null,
            'squid_dollar' => 100000,
        ]);
    }
}
