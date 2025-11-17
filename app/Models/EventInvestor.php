<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import model lain
use App\Models\Event;
use App\Models\User;

class EventInvestor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi: Catatan saldo ini milik SATU Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi: Catatan saldo ini milik SATU Investor (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
