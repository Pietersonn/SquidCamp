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
      'name' => 'Mentor 1',
      'email' => 'mentor1@squidcamp.com',
      'password' => $password,
      'role' => 'mentor',
      'email_verified_at' => now()
    ]);

    User::create([
      'name' => 'Mentor 2',
      'email' => 'mentor2@squidcamp.com',
      'password' => $password,
      'role' => 'mentor',
      'email_verified_at' => now()
    ]);

    User::create([
      'name' => 'Mentor 3',
      'email' => 'mentor3@squidcamp.com',
      'password' => $password,
      'role' => 'mentor',
      'email_verified_at' => now()
    ]);

    User::create([
      'name' => 'Mentor 4',
      'email' => 'mentor4@squidcamp.com',
      'password' => $password,
      'role' => 'mentor',
      'email_verified_at' => now()
    ]);

    User::create([
      'name' => 'Mentor 5',
      'email' => 'mentor5@squidcamp.com',
      'password' => $password,
      'role' => 'mentor',
      'email_verified_at' => now()
    ]);

    // 3. Investor (Akan dapat ID: 3)
    User::create([
      'name' => 'Investor 1',
      'email' => 'investor1@squidcamp.com',
      'password' => $password,
      'role' => 'investor',
      'email_verified_at' => now()
    ]);

    User::create([
      'name' => 'Investor 2',
      'email' => 'investor2@squidcamp.com',
      'password' => $password,
      'role' => 'investor',
      'email_verified_at' => now()
    ]);
  }
}
