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

    // Relasi ke Group (Induk)
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Relasi ke Anggota Group (Peserta)
    public function members()
    {
        return $this->hasMany(GroupMember::class, 'event_group_id');
    }
}
