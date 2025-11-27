<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        // TANGGAL
        'event_date' => 'date',

        // DATETIME
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',

        'event_start_time' => 'datetime',
        'event_end_time' => 'datetime',

        'challenge_start_time' => 'datetime',
        'challenge_end_time' => 'datetime',

        'case_start_time' => 'datetime',
        'case_end_time' => 'datetime',

        'show_start_time' => 'datetime',
        'show_end_time' => 'datetime',
    ];

    // ================= RELASI UTAMA =================

    // 1. Groups (Kelompok Peserta)
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // 2. Members (Semua Peserta yang join event ini)
    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    // 3. Investors (Investor yang masuk ke event ini)
    // PENTING: Method ini yang dipanggil oleh EventInvestorController
    public function eventInvestors()
    {
        return $this->hasMany(EventInvestor::class);
    }

    // Alias: Jika ada kode lama yang memanggil 'investors', arahkan ke 'eventInvestors'
    public function investors()
    {
        return $this->eventInvestors();
    }

    // 4. Mentors (User yang menjadi mentor di event ini)
    public function mentors()
    {
        return $this->belongsToMany(User::class, 'event_mentors', 'event_id', 'user_id')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    // ================= RELASI MODULE GAME =================

    // 5. Challenges (Fase 1)
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'event_challenges')
                    ->withTimestamps();
    }

    // 6. Cases (Fase 2)
    // Perhatikan: Menggunakan Model 'Cases' sesuai nama file Anda
    public function cases()
    {
        return $this->belongsToMany(Cases::class, 'event_cases', 'event_id', 'case_id')
                    ->withTimestamps();
    }

    // 7. Guidelines (Materi)
    public function guidelines()
    {
        return $this->belongsToMany(Guideline::class, 'event_guidelines')
                    ->withTimestamps();
    }


}
