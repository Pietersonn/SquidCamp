<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Password default untuk semua
        $password = Hash::make('password');

        // 1. Admin (Akan dapat ID: 1)
        User::create([
            'name' => 'Admin SquidCamp',
            'email' => 'admin@squidcamp.com',
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // 2. Mentor (Akan dapat ID: 2)
        User::create([
            'name' => 'Mentor Akmal',
            'email' => 'mentor@squidcamp.com',
            'password' => $password,
            'role' => 'mentor',
            'email_verified_at' => now()
        ]);

        // 3. Investor (Akan dapat ID: 3)
        User::create([
            'name' => 'Investor Dwipa',
            'email' => 'investor@squidcamp.com',
            'password' => $password,
            'role' => 'investor',
            'email_verified_at' => now()
        ]);

        // 4. Buat 10 Peserta (Akan dapat ID: 4 s/d 13)
        // Kita gunakan Factory yang sudah ada di folder database/factories
        User::factory(10)->create([
            'role' => 'user',
        ]);
    }
}
