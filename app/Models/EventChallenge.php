<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChallenge extends Model
{
    use HasFactory;

    protected $table = 'event_challenges';
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
