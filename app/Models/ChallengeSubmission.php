<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function challenge() { return $this->belongsTo(Challenge::class); }
    public function group() { return $this->belongsTo(Group::class); }
    public function submitter() { return $this->belongsTo(User::class, 'user_id'); }
    public function event() { return $this->belongsTo(Event::class); }
}
