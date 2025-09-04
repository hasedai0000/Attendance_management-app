@extends('layouts.app')

@section('title', '日次勤怠一覧')

@section('content')
  <div class="admin-daily-attendance-container">
    <div class="daily-attendance-header">
      <h2>日次勤怠一覧</h2>
      <div class="date-navigation">
        @php
          $prevDate = $date->copy()->subDay();
          $nextDate = $date->copy()->addDay();
        @endphp
        <a href="{{ route('admin.daily-attendance', $prevDate->format('Y-m-d')) }}" class="btn btn-secondary">&lt; 前日</a>
        <span class="current-date">{{ $date->format('Y年m月d日') }}</span>
        <a href="{{ route('admin.daily-attendance', $nextDate->format('Y-m-d')) }}" class="btn btn-secondary">翌日 &gt;</a>
      </div>
    </div>

    <div class="daily-attendance-table">
      <table>
        <thead>
          <tr>
            <th>氏名</th>
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
              <td>{{ $attendance->user->name }}</td>
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
                <a href="{{ route('admin.attendance-detail', $attendance->id) }}" class="btn btn-small">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="no-data">この日の勤怠データはありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
