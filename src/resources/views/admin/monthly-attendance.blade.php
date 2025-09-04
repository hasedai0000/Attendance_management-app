@extends('layouts.app')

@section('title', '月次勤怠一覧')

@section('content')
  <div class="admin-monthly-attendance-container">
    <div class="monthly-attendance-header">
      <h2>{{ $user->name }}さんの月次勤怠一覧</h2>
      <div class="month-navigation">
        @php
          $prevMonth = $month->copy()->subMonth();
          $nextMonth = $month->copy()->addMonth();
        @endphp
        <a href="{{ route('admin.monthly-attendance', ['userId' => $user->id, 'month' => $prevMonth->format('Y-m')]) }}"
          class="btn btn-secondary">&lt; 前月</a>
        <span class="current-month">{{ $month->format('Y年m月') }}</span>
        <a href="{{ route('admin.monthly-attendance', ['userId' => $user->id, 'month' => $nextMonth->format('Y-m')]) }}"
          class="btn btn-secondary">翌月 &gt;</a>
      </div>
      <div class="export-action">
        <a href="{{ route('admin.export-csv', ['userId' => $user->id, 'month' => $month->format('Y-m')]) }}"
          class="btn btn-primary">CSV出力</a>
      </div>
    </div>

    <div class="monthly-attendance-table">
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
                <a href="{{ route('admin.attendance-detail', $attendance->id) }}" class="btn btn-small">詳細</a>
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

    <div class="back-link">
      <a href="{{ route('admin.staff-list') }}" class="btn btn-secondary">スタッフ一覧に戻る</a>
    </div>
  </div>
@endsection
