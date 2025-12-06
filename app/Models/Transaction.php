<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'from_type',
        'from_id',
        'to_type',
        'to_id',
        'amount',
        'reason',
        'description',
    ];

    // Relasi Investor (User) - PENTING untuk halaman Investment
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // Relasi Group (jika transaksi dari kelompok)
    public function fromGroup()
    {
        return $this->belongsTo(Group::class, 'from_id');
    }

    // Relasi Group Penerima
    public function toGroup()
    {
        return $this->belongsTo(Group::class, 'to_id');
    }

    
}
