<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Menggunakan guarded kosong atau id agar semua kolom lain (termasuk is_active, is_finished)
     * bisa diisi secara mass assignment (create/update).
     */
    protected $guarded = ['id'];

    /**
     * Konversi tipe data otomatis
     */
    protected $casts = [
        // Status Event (PENTING untuk logika tombol Start/Finish)
        'is_active'   => 'boolean',
        'is_finished' => 'boolean',

        // TANGGAL UTAMA
        'event_date' => 'date',

        // PERIODE REGISTRASI
        'registration_start' => 'datetime',
        'registration_end'   => 'datetime',

        // WAKTU PELAKSANAAN (OPSIONAL JIKA DIPERLUKAN)
        'event_start_time' => 'datetime',
        'event_end_time'   => 'datetime',

        // FASE 1: CHALLENGE
        'challenge_start_time' => 'datetime',
        'challenge_end_time'   => 'datetime',

        // FASE 2: CASE
        'case_start_time' => 'datetime',
        'case_end_time'   => 'datetime',

        // FASE 3: SHOW / FINAL
        'show_start_time' => 'datetime',
        'show_end_time'   => 'datetime',
    ];

    // ================= RELASI UTAMA =================

    // 1. Groups (Kelompok Peserta)
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // 2. Members (Semua Peserta yang join event ini lewat group)
    // Relasi HasManyThrough sebenarnya lebih tepat jika GroupMember terhubung via Group
    // Tapi jika tabel group_members punya event_id, hasMany biasa sudah benar.
    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    // 3. Investors (Investor yang masuk ke event ini)
    public function eventInvestors()
    {
        return $this->hasMany(EventInvestor::class);
    }

    // Alias: Agar kode lama yang memanggil 'investors' tetap jalan
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
    public function cases()
    {
        // Pastikan nama Model 'Cases' sesuai dengan nama class/file aslinya (singular/plural)
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
