<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;

class EventMentor extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke User (Mentor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
