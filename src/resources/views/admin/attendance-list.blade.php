@extends('layouts.app')

@section('title', '勤怠一覧')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}">
@endsection

@section('content')
  <div class="admin-attendance-list">
    <!-- 日付選択エリア -->
    <div class="date-selector">
      <div class="date-selector__container">
        @php
          $prevDate = $date->copy()->subDay();
          $nextDate = $date->copy()->addDay();
        @endphp
        <a href="{{ route('admin.attendance-list', $prevDate->format('Y-m-d')) }}"
          class="date-selector__button date-selector__button--prev">
          <img src="{{ asset('images/arrow-left.svg') }}" alt="前日" class="date-selector__icon">
          <span class="date-selector__text">前日</span>
        </a>

        <div class="date-selector__current">
          <img src="{{ asset('images/calendar-icon.svg') }}" alt="カレンダー" class="date-selector__calendar-icon">
          <span class="date-selector__date">{{ $date->format('Y/m/d') }}</span>
        </div>

        <a href="{{ route('admin.attendance-list', $nextDate->format('Y-m-d')) }}"
          class="date-selector__button date-selector__button--next">
          <span class="date-selector__text">翌日</span>
          <img src="{{ asset('images/arrow-right.svg') }}" alt="翌日" class="date-selector__icon">
        </a>
      </div>
    </div>

    <!-- タイトル -->
    <div class="attendance-title">
      <h1 class="attendance-title__text">{{ $date->format('Y年m月d日') }}の勤怠</h1>
    </div>

    <!-- 勤怠一覧テーブル -->
    <div class="attendance-table-container">
      <div class="attendance-table-card">
        <table class="attendance-table">
          <thead class="attendance-table__header">
            <tr>
              <th class="attendance-table__header-cell">名前</th>
              <th class="attendance-table__header-cell">出勤</th>
              <th class="attendance-table__header-cell">退勤</th>
              <th class="attendance-table__header-cell">休憩</th>
              <th class="attendance-table__header-cell">合計</th>
              <th class="attendance-table__header-cell">詳細</th>
            </tr>
          </thead>
          <tbody class="attendance-table__body">
            @forelse($attendances as $attendance)
              <tr class="attendance-table__row">
                <td class="attendance-table__cell attendance-table__cell--name">{{ $attendance->user->name }}</td>
                <td class="attendance-table__cell">{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}
                </td>
                <td class="attendance-table__cell">
                  {{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}</td>
                <td class="attendance-table__cell">
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
                <td class="attendance-table__cell">
                  @php
                    if ($attendance->clock_in && $attendance->clock_out) {
                        $totalMinutes = Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(
                            $attendance->clock_out,
                        );
                        $workMinutes = $totalMinutes - $totalBreakMinutes;
                        $workHours = intval($workMinutes / 60);
                        $workMinutesRemainder = $workMinutes % 60;
                    }
                  @endphp
                  @if (isset($workHours))
                    {{ $workHours }}:{{ str_pad($workMinutesRemainder, 2, '0', STR_PAD_LEFT) }}
                  @endif
                </td>
                <td class="attendance-table__cell">
                  <a href="{{ route('admin.attendance-detail', $attendance->id) }}"
                    class="attendance-table__detail-link">詳細</a>
                </td>
              </tr>
            @empty
              <tr class="attendance-table__row">
                <td colspan="6" class="attendance-table__cell attendance-table__cell--no-data">
                  この日の勤怠データはありません
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
