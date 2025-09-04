<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceRequest extends Model
{
 use HasFactory;

 protected $fillable = [
  'attendance_id',
  'user_id',
  'requested_clock_in',
  'requested_clock_out',
  'requested_note',
  'status',
  'approved_at',
  'approved_by',
 ];

 protected $casts = [
  'requested_clock_in' => 'datetime',
  'requested_clock_out' => 'datetime',
  'approved_at' => 'datetime',
 ];

 public function attendance(): BelongsTo
 {
  return $this->belongsTo(Attendance::class);
 }

 public function user(): BelongsTo
 {
  return $this->belongsTo(User::class);
 }

 public function approver(): BelongsTo
 {
  return $this->belongsTo(User::class, 'approved_by');
 }

 public function breakRequests(): HasMany
 {
  return $this->hasMany(BreakRequest::class);
 }
}
