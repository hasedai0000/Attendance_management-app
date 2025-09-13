<?php

namespace App\Models;

use App\Models\User;
use App\Models\AttendanceBreak;
use App\Models\AttendanceRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attendance extends Model
{
 use HasFactory;

 protected $keyType = 'string';
 public $incrementing = false;

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

 protected static function boot()
 {
  parent::boot();

  static::creating(function ($model) {
   if (empty($model->id)) {
    $model->id = Str::uuid();
   }
  });
 }

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
