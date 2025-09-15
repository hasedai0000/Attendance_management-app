<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
 public function __construct()
 {
  $this->middleware('auth');
  $this->middleware(function ($request, $next) {
   if (!Auth::user()->isAdmin()) {
    abort(403);
   }
   return $next($request);
  });
 }

 public function dailyAttendance($date = null)
 {
  $date = $date ? Carbon::parse($date) : Carbon::today();

  $attendances = Attendance::with(['user'])
   ->where('date', $date->format('Y-m-d'))
   ->get();

  return view('admin.daily-attendance', compact('attendances', 'date'));
 }

 public function staffList()
 {
  $users = User::where('is_admin', false)->get();

  return view('admin.staff-list', compact('users'));
 }

 public function monthlyAttendance($userId, $month = null)
 {
  $user = User::findOrFail($userId);
  $month = $month ? Carbon::parse($month) : Carbon::now();

  $attendances = $user->attendances()
   ->whereYear('date', $month->year)
   ->whereMonth('date', $month->month)
   ->orderBy('date', 'desc')
   ->get();

  return view('admin.monthly-attendance', compact('user', 'attendances', 'month'));
 }

 public function exportCsv($userId, $month)
 {
  $user = User::findOrFail($userId);
  $month = Carbon::parse($month);

  $attendances = $user->attendances()
   ->whereYear('date', $month->year)
   ->whereMonth('date', $month->month)
   ->orderBy('date', 'asc')
   ->get();

  $filename = "attendance_{$user->name}_{$month->format('Y_m')}.csv";

  $headers = [
   'Content-Type' => 'text/csv',
   'Content-Disposition' => "attachment; filename=\"$filename\"",
  ];

  $callback = function () use ($attendances) {
   $file = fopen('php://output', 'w');

   // CSVヘッダー
   fputcsv($file, ['日付', '出勤時刻', '退勤時刻', '休憩時間', '備考']);

   foreach ($attendances as $attendance) {
    $breakTime = $attendance->breaks->sum(function ($break) {
     if ($break->break_end) {
      return Carbon::parse($break->break_start)->diffInMinutes($break->break_end);
     }
     return 0;
    });

    fputcsv($file, [
     $attendance->date->format('Y-m-d'),
     $attendance->clock_in ? $attendance->clock_in->format('H:i') : '',
     $attendance->clock_out ? $attendance->clock_out->format('H:i') : '',
     $breakTime,
     $attendance->note ?? '',
    ]);
   }

   fclose($file);
  };

  return response()->stream($callback, 200, $headers);
 }

 public function attendanceDetail($attendanceId)
 {
  $attendance = Attendance::with(['user', 'breaks'])->findOrFail($attendanceId);

  return view('admin.attendance-detail', compact('attendance'));
 }

 public function updateAttendance(Request $request, $attendanceId)
 {
  $attendance = Attendance::findOrFail($attendanceId);

  $request->validate([
   'clock_in' => 'nullable|date_format:H:i',
   'clock_out' => 'nullable|date_format:H:i',
   'note' => 'required|string',
  ]);

  // 時間の妥当性チェック
  if ($request->clock_in && $request->clock_out) {
   if (Carbon::parse($request->clock_in)->gt(Carbon::parse($request->clock_out))) {
    return back()->withErrors(['clock_in' => '出勤時間もしくは退勤時間が不適切な値です']);
   }
  }

  $attendance->update([
   'clock_in' => $request->clock_in ? Carbon::parse($request->clock_in) : null,
   'clock_out' => $request->clock_out ? Carbon::parse($request->clock_out) : null,
   'note' => $request->note,
  ]);

  return back()->with('success', '勤怠情報を更新しました。');
 }

 public function requestList()
 {
  $pendingRequests = AttendanceRequest::with(['user', 'attendance'])
   ->where('status', 'pending')
   ->orderBy('created_at', 'desc')
   ->get();

  $approvedRequests = AttendanceRequest::with(['user', 'attendance'])
   ->where('status', 'approved')
   ->orderBy('approved_at', 'desc')
   ->get();

  // デバッグ情報を追加
  \Log::info('Admin requestList - Pending count: ' . $pendingRequests->count());
  \Log::info('Admin requestList - Approved count: ' . $approvedRequests->count());

  return view('admin.request-list', compact('pendingRequests', 'approvedRequests'));
 }

 public function requestDetail($requestId)
 {
  $request = AttendanceRequest::with(['user', 'attendance', 'breakRequests'])->findOrFail($requestId);

  return view('admin.request-detail', compact('request'));
 }

 public function approveRequest($requestId)
 {
  $request = AttendanceRequest::findOrFail($requestId);

  DB::transaction(function () use ($request) {
   // 勤怠情報を更新
   $attendance = $request->attendance;
   $attendance->update([
    'clock_in' => $request->requested_clock_in,
    'clock_out' => $request->requested_clock_out,
    'note' => $request->requested_note,
   ]);

   // 休憩時間も更新
   if ($request->breakRequests->isNotEmpty()) {
    $attendance->breaks()->delete();
    foreach ($request->breakRequests as $breakRequest) {
     $attendance->breaks()->create([
      'break_start' => $breakRequest->requested_break_start,
      'break_end' => $breakRequest->requested_break_end,
     ]);
    }
   }

   // 申請を承認済みに更新
   $request->update([
    'status' => 'approved',
    'approved_at' => Carbon::now(),
    'approved_by' => Auth::id(),
   ]);
  });

  return back()->with('success', '修正申請を承認しました。');
 }
}
