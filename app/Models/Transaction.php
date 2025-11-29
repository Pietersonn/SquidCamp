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
        'description', // Pastikan kolom ini ada di migrasi database kamu, jika tidak hapus baris ini
    ];

    // Relasi Opsional (Biar enak kalau mau dipanggil)
    public function fromGroup()
    {
        return $this->belongsTo(Group::class, 'from_id');
    }

    public function toGroup()
    {
        return $this->belongsTo(Group::class, 'to_id');
    }
}
