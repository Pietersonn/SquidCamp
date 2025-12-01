<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  protected $fillable = [
    'event_id',
    'name',
    'mentor_id',
    'captain_id',
    'cocaptain_id',
    'squid_dollar', // TABUNGAN BANK
    'bank_balance', // UANG CASH
  ];

  /**
   * Accessor untuk mendapatkan Total Kekayaan (Cash + Bank).
   * Cara akses: $group->total_wealth
   */
  public function getTotalWealthAttribute()
  {
      // Jika atribut 'total_wealth' sudah di-selectRaw dari query, pakai itu
      if (isset($this->attributes['total_wealth'])) {
          return $this->attributes['total_wealth'];
      }

      // Jika tidak, hitung manual
      return $this->squid_dollar + $this->bank_balance;
  }

  // Relasi: Grup ini milik SATU Event
  public function event()
  {
    return $this->belongsTo(Event::class);
  }

  // Relasi: Mentor (User)
  public function mentor()
  {
    return $this->belongsTo(User::class, 'mentor_id');
  }

  // Relasi: Captain (User)
  public function captain()
  {
    return $this->belongsTo(User::class, 'captain_id');
  }

  // Relasi: Co-Captain (User)
  public function cocaptain()
  {
    return $this->belongsTo(User::class, 'cocaptain_id');
  }

  // Relasi: Anggota (GroupMember)
  public function members()
  {
    return $this->hasMany(GroupMember::class);
  }

  public function activeChallenges()
  {
    // Challenge yang sedang dikerjakan atau menunggu review (menghitung slot)
    return $this->hasMany(ChallengeSubmission::class)
      ->whereIn('status', ['active', 'pending']);
  }

  public function completedChallenges()
  {
    return $this->hasMany(ChallengeSubmission::class)
      ->where('status', 'approved');
  }
}
