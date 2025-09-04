<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
 use HasFactory;

 protected $fillable = [
  'user_id',
  'date',
  'clock_in',
  'clock_out',
  'note',
  'status',
 ];

 protected $casts = [
  'date' => 'date',
  'clock_in' => 'datetime',
  'clock_out' => 'datetime',
 ];

 public function user(): BelongsTo
 {
  return $this->belongsTo(User::class);
 }

 public function breaks(): HasMany
 {
  return $this->hasMany(AttendanceBreak::class);
 }

 public function requests(): HasMany
 {
  return $this->hasMany(AttendanceRequest::class);
 }
}
