<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import model Event untuk relasi
use App\Models\Event;

class SquidChallenge extends Model
{
    use HasFactory;

    // Lindungi field agar tidak diisi sembarangan
    protected $guarded = ['id'];

    /**
     * Relasi: Challenge ini milik SATU Event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
