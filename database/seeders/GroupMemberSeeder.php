<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupMember;

class GroupMemberSeeder extends Seeder
{
    public function run(): void
    {
        // User 4 -> Grup 1 (Event 1)
        // Ini akan berhasil karena UserSeeder baru membuat user ID 4
        GroupMember::create(['user_id' => 4, 'group_id' => 1, 'event_id' => 1]);

        // User 5 -> Grup 1 (Event 1)
        // Ini juga akan berhasil
        GroupMember::create(['user_id' => 5, 'group_id' => 1, 'event_id' => 1]);

        // User 6 -> Grup 1 (Event 1)
        // Ini juga akan berhasil
        GroupMember::create(['user_id' => 6, 'group_id' => 1, 'event_id' => 1]);

        // (Opsional) Masukkan 3 peserta lagi ke Grup 2
        GroupMember::create(['user_id' => 7, 'group_id' => 2, 'event_id' => 1]);
        GroupMember::create(['user_id' => 8, 'group_id' => 2, 'event_id' => 1]);
        GroupMember::create(['user_id' => 9, 'group_id' => 2, 'event_id' => 1]);
    }
}
