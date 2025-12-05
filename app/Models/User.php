<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELASI ==========

    /**
     * Relasi Groups (Untuk Mentor): Grup yang dibimbing oleh user ini.
     * Nama method 'groups' disesuaikan dengan panggilan di EventMentorController.
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'mentor_id');
    }

    /**
     * (Opsional) Alias jika Anda ingin menggunakan nama yang lebih spesifik di tempat lain.
     */
    public function mentoredGroups()
    {
        return $this->groups();
    }

    /**
     * [PERBAIKAN] Relasi Event milik Mentor.
     * Menghubungkan User (Mentor) ke Event melalui tabel pivot 'event_mentors'.
     * Update: Menghapus ->withPivot('status') karena kolom status tidak ada di tabel database saat ini.
     */
    public function mentorEvents()
    {
        return $this->belongsToMany(Event::class, 'event_mentors', 'user_id', 'event_id')
                    // ->withPivot('status') // HAPUS ATAU KOMENTARI BARIS INI
                    ->withTimestamps();
    }

    /**
     * Helper untuk cek apakah mentor punya akses ke event tertentu
     */
    public function hasEventAccess($eventId)
    {
        return $this->mentorEvents()->where('event_id', $eventId)->exists();
    }

    /**
     * Relasi untuk INVESTOR: Event mana saja dia ditugaskan
     */
    public function investedEvents()
    {
        return $this->hasMany(EventInvestor::class, 'user_id');
    }

    /**
     * Relasi untuk PESERTA: Grup apa yang dia ikuti
     */
    public function groupMemberships()
    {
        return $this->hasMany(GroupMember::class, 'user_id');
    }
}
