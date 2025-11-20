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

    // RELASI

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function investors()
    {
        return $this->hasMany(EventInvestor::class);
    }

    // MANY-TO-MANY CHALLENGES
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'event_challenges')
            ->withTimestamps();
    }

    // MANY-TO-MANY GUIDELINES
    public function guidelines()
    {
        return $this->belongsToMany(Guideline::class, 'event_guidelines')
            ->withTimestamps();
    }

    // ONE CASE PER EVENT
    public function squidCase()
    {
        return $this->hasOne(SquidCase::class);
    }
}
