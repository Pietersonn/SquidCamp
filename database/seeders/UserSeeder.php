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

        // 1. Admin
        User::create([
            'name' => 'Admin SquidCamp',
            'email' => 'admin@squidcamp.com',
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // 2. Mentor
        User::create([
            'name' => 'Mentor Akmal',
            'email' => 'mentor@squidcamp.com',
            'password' => $password,
            'role' => 'mentor',
            'email_verified_at' => now()
        ]);

        // 3. Investor
        User::create([
            'name' => 'Investor Dwipa',
            'email' => 'investor@squidcamp.com',
            'password' => $password,
            'role' => 'investor',
            'email_verified_at' => now()
        ]);

        // 4. User (Main)
        User::create([
            'name' => 'User Peserta',
            'email' => 'user@squidcamp.com',
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now()
        ]);
    }
}
