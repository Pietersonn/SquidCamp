<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import model lain
use App\Models\Event;
use App\Models\User;
use App\Models\GroupMember;

class Group extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi: Grup ini milik SATU Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi: Grup ini dibimbing oleh SATU Mentor (User)
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // Relasi: Grup ini punya BANYAK anggota (dari pivot)
    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }
}
