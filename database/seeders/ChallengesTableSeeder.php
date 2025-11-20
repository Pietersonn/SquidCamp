<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChallengesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('challenges')->insert([
            [
                'nama' => 'Challenge Speed 300 - Basic',
                'kategori' => '300',
                'file_pdf' => null,
                'deskripsi' => 'Challenge dasar tingkat 300.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Challenge Speed 500 - Intermediate',
                'kategori' => '500',
                'file_pdf' => null,
                'deskripsi' => 'Challenge menengah tingkat 500.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Challenge Speed 700 - Advanced',
                'kategori' => '700',
                'file_pdf' => null,
                'deskripsi' => 'Challenge tingkat lanjut 700.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
