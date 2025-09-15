@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')
  <div class="attendance-list-container">
    <div class="attendance-list-header">
      <h2>勤怠一覧</h2>
      <div class="month-navigation">
        @php
          $currentMonth = Carbon\Carbon::parse($currentMonth);
          $prevMonth = $currentMonth->copy()->subMonth();
          $nextMonth = $currentMonth->copy()->addMonth();
        @endphp
        <a href="?month={{ $prevMonth->format('Y-m') }}" class="btn btn-secondary">&lt; 前月</a>
        <span class="current-month">{{ $currentMonth->format('Y年m月') }}</span>
        <a href="?month={{ $nextMonth->format('Y-m') }}" class="btn btn-secondary">翌月 &gt;</a>
      </div>
    </div>

    <div class="attendance-table">
      <table>
        <thead>
          <tr>
            <th>日付</th>
            <th>出勤時刻</th>
            <th>退勤時刻</th>
            <th>休憩時間</th>
            <th>備考</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($attendances as $attendance)
            <tr>
              <td>{{ $attendance->date->format('m/d') }}</td>
              <td>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}</td>
              <td>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}</td>
              <td>
                @php
                  $totalBreakMinutes = 0;
                  foreach ($attendance->breaks as $break) {
                      if ($break->break_end) {
                          $totalBreakMinutes += Carbon\Carbon::parse($break->break_start)->diffInMinutes(
                              $break->break_end,
                          );
                      }
                  }
                  $hours = intval($totalBreakMinutes / 60);
                  $minutes = $totalBreakMinutes % 60;
                @endphp
                @if ($totalBreakMinutes > 0)
                  {{ $hours }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                @endif
              </td>
              <td>{{ $attendance->note ?? '' }}</td>
              <td>
                <a href="{{ route('attendance.detail', $attendance->date->format('Y-m-d')) }}" class="btn btn-small">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="no-data">この月の勤怠データはありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
