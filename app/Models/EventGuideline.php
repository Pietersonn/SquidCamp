<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGuideline extends Model
{
    use HasFactory;

    protected $table = 'event_guidelines';
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function guideline()
    {
        return $this->belongsTo(Guideline::class);
    }
}
