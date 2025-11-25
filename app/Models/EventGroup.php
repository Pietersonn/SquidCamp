<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke Anggota Group
    public function members()
    {
        return $this->hasMany(GroupMember::class, 'event_group_id');
    }
}
