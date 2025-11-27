<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Group (Penerima Dana)
    public function group()
    {
        // Asumsi: to_type = 'group', maka to_id adalah id group
        return $this->belongsTo(Group::class, 'to_id');
    }

    // Relasi ke Pengirim (Investor/User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
}
