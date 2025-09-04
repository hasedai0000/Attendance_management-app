@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
  <div class="admin-attendance-detail-container">
    <div class="attendance-detail-header">
      <h2>勤怠詳細</h2>
      <p class="staff-name">{{ $attendance->user->name }}さん</p>
      <p class="date">{{ $attendance->date->format('Y年m月d日') }}</p>
    </div>

    <div class="attendance-info">
      <div class="info-section">
        <h3>基本情報</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>氏名:</label>
            <span>{{ $attendance->user->name }}</span>
          </div>
          <div class="info-item">
            <label>日付:</label>
            <span>{{ $attendance->date->format('Y年m月d日') }}</span>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3>出勤・退勤</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>出勤時刻:</label>
            <span>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '未出勤' }}</span>
          </div>
          <div class="info-item">
            <label>退勤時刻:</label>
            <span>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '未退勤' }}</span>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3>休憩</h3>
        @if ($attendance->breaks->count() > 0)
          <div class="break-list">
            @foreach ($attendance->breaks as $break)
              <div class="break-item">
                <span>{{ $break->break_start->format('H:i') }} -
                  {{ $break->break_end ? $break->break_end->format('H:i') : '未終了' }}</span>
              </div>
            @endforeach
          </div>
        @else
          <p>休憩なし</p>
        @endif
      </div>

      <div class="info-section">
        <h3>備考</h3>
        <p>{{ $attendance->note ?? '備考なし' }}</p>
      </div>
    </div>

    <div class="attendance-edit">
      <h3>勤怠修正</h3>
      <form action="{{ route('admin.update-attendance', $attendance->id) }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="clock_in">出勤時刻:</label>
          <input type="time" id="clock_in" name="clock_in"
            value="{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}">
        </div>

        <div class="form-group">
          <label for="clock_out">退勤時刻:</label>
          <input type="time" id="clock_out" name="clock_out"
            value="{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}">
        </div>

        <div class="form-group">
          <label for="note">備考:</label>
          <textarea id="note" name="note" required>{{ $attendance->note ?? '' }}</textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">修正</button>
          <a href="{{ route('admin.daily-attendance', $attendance->date->format('Y-m-d')) }}"
            class="btn btn-secondary">戻る</a>
        </div>
      </form>
    </div>
  </div>
@endsection
