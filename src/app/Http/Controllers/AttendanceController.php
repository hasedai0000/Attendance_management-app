<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\AttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\AttendanceRequest;
use App\Models\BreakRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
 public function index()
 {
  $user = Auth::user();
  $currentMonth = request('month', Carbon::now()->format('Y-m'));

  $attendances = $user->attendances()
   ->whereYear('date', Carbon::parse($currentMonth)->year)
   ->whereMonth('date', Carbon::parse($currentMonth)->month)
   ->orderBy('date', 'desc')
   ->get();

  return view('attendance.index', compact('attendances', 'currentMonth'));
 }

 public function show($date)
 {
  $user = Auth::user();
  $attendance = $user->attendances()
   ->where('date', $date)
   ->with(['breaks'])
   ->first();

  if (!$attendance) {
   $attendance = new Attendance([
    'date' => $date,
    'status' => 'off_duty'
   ]);
  }

  return view('attendance.show', compact('attendance', 'date'));
 }

 public function clockIn()
 {
  $user = Auth::user();
  $today = Carbon::today();

  // 今日の勤怠レコードを取得または作成
  $attendance = $user->attendances()->firstOrCreate(
   ['date' => $today],
   ['status' => 'off_duty']
  );

  if ($attendance->status !== 'off_duty') {
   return back()->with('error', '既に出勤済みです。');
  }

  $attendance->update([
   'clock_in' => Carbon::now(),
   'status' => 'working'
  ]);

  return back()->with('success', '出勤しました。');
 }

 public function clockOut()
 {
  $user = Auth::user();
  $today = Carbon::today();

  $attendance = $user->attendances()
   ->where('date', $today)
   ->first();

  if (!$attendance || $attendance->status !== 'working') {
   return back()->with('error', '出勤していません。');
  }

  $attendance->update([
   'clock_out' => Carbon::now(),
   'status' => 'finished'
  ]);

  return back()->with('success', 'お疲れ様でした。');
 }

 public function breakStart()
 {
  $user = Auth::user();
  $today = Carbon::today();

  $attendance = $user->attendances()
   ->where('date', $today)
   ->first();

  if (!$attendance || $attendance->status !== 'working') {
   return back()->with('error', '出勤していません。');
  }

  $attendance->update(['status' => 'break']);

  // 休憩レコードを作成
  AttendanceBreak::create([
   'attendance_id' => $attendance->id,
   'break_start' => Carbon::now(),
  ]);

  return back()->with('success', '休憩を開始しました。');
 }

 public function breakEnd()
 {
  $user = Auth::user();
  $today = Carbon::today();

  $attendance = $user->attendances()
   ->where('date', $today)
   ->first();

  if (!$attendance || $attendance->status !== 'break') {
   return back()->with('error', '休憩中ではありません。');
  }

  $attendance->update(['status' => 'working']);

  // 最新の休憩レコードを更新
  $break = $attendance->breaks()
   ->whereNull('break_end')
   ->latest()
   ->first();

  if ($break) {
   $break->update(['break_end' => Carbon::now()]);
  }

  return back()->with('success', '休憩を終了しました。');
 }

 public function update(AttendanceUpdateRequest $request, $date)
 {
  $user = Auth::user();
  $attendance = $user->attendances()
   ->where('date', $date)
   ->first();

  if (!$attendance) {
   return back()->with('error', '勤怠レコードが見つかりません。');
  }

  // 修正申請を作成
  $attendanceRequest = AttendanceRequest::create([
   'attendance_id' => $attendance->id,
   'user_id' => $user->id,
   'requested_clock_in' => $request->clock_in ? Carbon::parse($request->clock_in) : null,
   'requested_clock_out' => $request->clock_out ? Carbon::parse($request->clock_out) : null,
   'requested_note' => $request->note,
   'status' => 'pending',
  ]);

  // 休憩時間の修正申請も処理
  if ($request->has('breaks')) {
   foreach ($request->breaks as $breakData) {
    if ($breakData['start'] && $breakData['end']) {
     BreakRequest::create([
      'attendance_request_id' => $attendanceRequest->id,
      'requested_break_start' => Carbon::parse($breakData['start']),
      'requested_break_end' => Carbon::parse($breakData['end']),
     ]);
    }
   }
  }

  return back()->with('success', '修正申請を送信しました。');
 }

 public function requests()
 {
  $user = Auth::user();

  $pendingRequests = $user->attendanceRequests()
   ->where('status', 'pending')
   ->with(['attendance'])
   ->get();

  $approvedRequests = $user->attendanceRequests()
   ->where('status', 'approved')
   ->with(['attendance'])
   ->get();

  return view('attendance.requests', compact('pendingRequests', 'approvedRequests'));
 }
}
