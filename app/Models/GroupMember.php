<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi: Catatan member ini milik SATU Grup
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Relasi: Catatan member ini milik SATU User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Catatan member ini milik SATU Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
