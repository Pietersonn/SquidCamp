<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventInvestor;

class EventInvestorSeeder extends Seeder
{
    public function run(): void
    {
        EventInvestor::create([
            'event_id' => 1, // Asumsi Event ID 1
            'user_id' => 3, // Asumsi Bu Investor B adalah User ID 3
            'investment_balance' => 500000000,
        ]);
    }
}
