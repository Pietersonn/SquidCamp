<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Event (Existing)
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_challenges')
            ->withTimestamps();
    }

    // Relasi ke Submissions (BARU)
    public function submissions()
    {
        return $this->hasMany(ChallengeSubmission::class);
    }
}
