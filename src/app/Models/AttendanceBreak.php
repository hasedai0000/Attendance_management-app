<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttendanceBreak extends Model
{
 use HasFactory;

 protected $table = 'breaks';
 protected $keyType = 'string';
 public $incrementing = false;

 protected $fillable = [
  'attendance_id',
  'break_start',
  'break_end',
 ];

 protected $casts = [
  'break_start' => 'datetime',
  'break_end' => 'datetime',
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

 public function attendance(): BelongsTo
 {
  return $this->belongsTo(Attendance::class);
 }
}
