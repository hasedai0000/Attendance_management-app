<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証関連ルート（ゲスト用）
Route::middleware('guest')->group(function () {
    // ログイン
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // 管理者ログイン
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);

    // 新規登録
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 認証関連ルート（認証済みユーザー用）
Route::middleware('auth')->group(function () {
    // ログアウト
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // メール認証
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// メインページ（ゲスト用）
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('attendance.show', ['date' => now()->format('Y-m-d')]);
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // 勤怠打刻画面（メイン画面）
    Route::get('/attendance/{date}', [AttendanceController::class, 'show'])->name('attendance.show');

    // 勤怠関連
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.breakStart');
    Route::post('/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.breakEnd');

    // 勤怠一覧・詳細
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::put('/attendance/{date}', [AttendanceController::class, 'update'])->name('attendance.update');

    // 修正申請一覧
    Route::get('/requests', [AttendanceController::class, 'requests'])->name('attendance.requests');
});

// 管理者用ルート
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // 日次勤怠一覧
    Route::get('/daily-attendance/{date?}', [AdminController::class, 'dailyAttendance'])->name('daily-attendance');

    // スタッフ一覧
    Route::get('/staff', [AdminController::class, 'staffList'])->name('staff-list');

    // スタッフ月次勤怠
    Route::get('/staff/{userId}/monthly-attendance/{month?}', [AdminController::class, 'monthlyAttendance'])->name('monthly-attendance');
    Route::get('/staff/{userId}/export/{month}', [AdminController::class, 'exportCsv'])->name('export-csv');

    // 勤怠詳細・修正
    Route::get('/attendance/{attendanceId}/detail', [AdminController::class, 'attendanceDetail'])->name('attendance-detail');
    Route::put('/attendance/{attendanceId}', [AdminController::class, 'updateAttendance'])->name('update-attendance');

    // 修正申請一覧・詳細・承認
    Route::get('/requests', [AdminController::class, 'requestList'])->name('request-list');
    Route::get('/requests/{requestId}', [AdminController::class, 'requestDetail'])->name('request-detail');
    Route::post('/requests/{requestId}/approve', [AdminController::class, 'approveRequest'])->name('approve-request');
});
