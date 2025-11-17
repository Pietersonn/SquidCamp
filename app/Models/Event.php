<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import model lain yang akan direlasikan
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\EventInvestor;
use App\Models\SquidChallenge;
use App\Models\SquidCase;

class Event extends Model
{
    use HasFactory;

    /**
     * Mass assignment protection.
     * $guarded = ['id'] berarti semua kolom boleh diisi
     * kecuali 'id'.
     */
    protected $guarded = ['id'];

    /**
     * ==============================================================
     * == INI ADALAH FIX UNTUK ERROR ANDA ==
     * ==============================================================
     * Memberi tahu Laravel untuk otomatis mengubah kolom-kolom ini
     * dari string (database) menjadi objek Tanggal (Carbon).
     */
    protected $casts = [
        'event_date' => 'date', // 'date' (karena tidak ada jam)
        'challenge_start_time' => 'datetime', // 'datetime' (karena ada jam)
        'challenge_end_time' => 'datetime',
        'case_start_time' => 'datetime',
        'case_end_time' => 'datetime',
        'show_start_time' => 'datetime',
        'show_end_time' => 'datetime',
    ];


    // == RELASI UNTUK HALAMAN DETAIL ==

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function members() {
        return $this->hasMany(GroupMember::class);
    }

    public function eventInvestors() {
        return $this->hasMany(EventInvestor::class);
    }

    public function challenges() {
        return $this->hasMany(SquidChallenge::class);
    }

    public function squidCase() {
        return $this->hasOne(SquidCase::class);
    }
}
