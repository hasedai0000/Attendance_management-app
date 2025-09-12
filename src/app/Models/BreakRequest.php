<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BreakRequest extends Model
{
 use HasFactory;

 protected $keyType = 'string';
 public $incrementing = false;

 protected $fillable = [
  'attendance_request_id',
  'break_id',
  'requested_break_start',
  'requested_break_end',
 ];

 protected $casts = [
  'requested_break_start' => 'datetime',
  'requested_break_end' => 'datetime',
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

 public function attendanceRequest(): BelongsTo
 {
  return $this->belongsTo(AttendanceRequest::class);
 }

 public function break(): BelongsTo
 {
  return $this->belongsTo(AttendanceBreak::class, 'break_id');
 }
}
