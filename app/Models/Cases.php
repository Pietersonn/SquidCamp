<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
  use HasFactory;

  protected $table = 'cases'; // Pastikan tabelnya 'cases'
  protected $guarded = ['id'];

  // Relasi ke Event
  public function events()
  {
    return $this->belongsToMany(Event::class, 'event_cases', 'case_id', 'event_id')
      ->withTimestamps();
  }
}
