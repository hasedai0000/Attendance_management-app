@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
  <div class="attendance-detail-container">
    <div class="attendance-detail-header">
      <h2>勤怠詳細</h2>
      <p class="date">{{ $date }}</p>
    </div>

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <div class="attendance-info">
      <div class="info-section">
        <h3>基本情報</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>名前:</label>
            <span>{{ Auth::user()->name }}</span>
          </div>
          <div class="info-item">
            <label>日付:</label>
            <span>{{ $date }}</span>
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
      <h3>修正申請</h3>
      <form action="{{ route('attendance.update', $date) }}" method="POST" class="edit-form">
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

        <div class="form-group">
          <label>休憩時間:</label>
          <div class="break-inputs">
            @for ($i = 0; $i < max($attendance->breaks->count() + 1, 1); $i++)
              <div class="break-input-row">
                <input type="time" name="breaks[{{ $i }}][start]" placeholder="開始時刻"
                  value="{{ isset($attendance->breaks[$i]) ? $attendance->breaks[$i]->break_start->format('H:i') : '' }}">
                <span>-</span>
                <input type="time" name="breaks[{{ $i }}][end]" placeholder="終了時刻"
                  value="{{ isset($attendance->breaks[$i]) && $attendance->breaks[$i]->break_end ? $attendance->breaks[$i]->break_end->format('H:i') : '' }}">
              </div>
            @endfor
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">修正申請</button>
          <a href="{{ route('attendance.index') }}" class="btn btn-secondary">戻る</a>
        </div>
      </form>
    </div>
  </div>
@endsection
