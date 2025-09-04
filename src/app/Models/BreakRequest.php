<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BreakRequest extends Model
{
 use HasFactory;

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

 public function attendanceRequest(): BelongsTo
 {
  return $this->belongsTo(AttendanceRequest::class);
 }

 public function break(): BelongsTo
 {
  return $this->belongsTo(AttendanceBreak::class, 'break_id');
 }
}
