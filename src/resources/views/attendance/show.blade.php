@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')
  <div class="attendance-container">
    <div class="attendance-header">
      <h2>勤怠打刻</h2>
      <div class="current-time">
        <p>現在時刻: <span id="current-time">{{ now()->format('Y年m月d日 H:i:s') }}</span></p>
      </div>
    </div>

    <div class="attendance-status">
      <h3>現在のステータス</h3>
      <div class="status-display">
        @php
          $statusText = [
              'off_duty' => '勤務外',
              'working' => '出勤中',
              'break' => '休憩中',
              'finished' => '退勤済',
          ];
          $currentStatus = $attendance->status ?? 'off_duty';
        @endphp
        <p class="status-{{ $currentStatus }}">{{ $statusText[$currentStatus] }}</p>
      </div>
    </div>

    <div class="attendance-actions">
      @if ($currentStatus === 'off_duty')
        <form action="{{ route('attendance.clockIn') }}" method="POST" class="action-form">
          @csrf
          <button type="submit" class="btn btn-primary">出勤</button>
        </form>
      @elseif($currentStatus === 'working')
        <div class="action-buttons">
          <form action="{{ route('attendance.breakStart') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-secondary">休憩</button>
          </form>
          <form action="{{ route('attendance.clockOut') }}" method="POST" class="action-form">
            @csrf
            <button type="submit" class="btn btn-danger">退勤</button>
          </form>
        </div>
      @elseif($currentStatus === 'break')
        <form action="{{ route('attendance.breakEnd') }}" method="POST" class="action-form">
          @csrf
          <button type="submit" class="btn btn-success">休憩戻</button>
        </form>
      @endif
    </div>

    @if ($attendance->id)
      <div class="attendance-info">
        <h3>今日の勤怠情報</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>出勤時刻:</label>
            <span>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '未出勤' }}</span>
          </div>
          <div class="info-item">
            <label>退勤時刻:</label>
            <span>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '未退勤' }}</span>
          </div>
          <div class="info-item">
            <label>休憩回数:</label>
            <span>{{ $attendance->breaks->count() }}回</span>
          </div>
        </div>
      </div>
    @endif
  </div>

  <script>
    function updateTime() {
      const now = new Date();
      const timeString = now.getFullYear() + '年' +
        String(now.getMonth() + 1).padStart(2, '0') + '月' +
        String(now.getDate()).padStart(2, '0') + '日 ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
      document.getElementById('current-time').textContent = timeString;
    }

    setInterval(updateTime, 1000);
  </script>
@endsection
