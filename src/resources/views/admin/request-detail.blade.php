@extends('layouts.app')

@section('title', '修正申請詳細')

@section('content')
  <div class="admin-request-detail-container">
    <div class="request-detail-header">
      <h2>修正申請詳細</h2>
      <p class="applicant">申請者: {{ $request->user->name }}さん</p>
      <p class="date">対象日: {{ $request->attendance->date->format('Y年m月d日') }}</p>
      <p class="status">ステータス: {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</p>
    </div>

    <div class="request-info">
      <div class="info-section">
        <h3>申請内容</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>申請日時:</label>
            <span>{{ $request->created_at->format('Y年m月d日 H:i') }}</span>
          </div>
          @if ($request->status === 'approved')
            <div class="info-item">
              <label>承認日時:</label>
              <span>{{ $request->approved_at->format('Y年m月d日 H:i') }}</span>
            </div>
          @endif
        </div>
      </div>

      <div class="info-section">
        <h3>現在の勤怠情報</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>出勤時刻:</label>
            <span>{{ $request->attendance->clock_in ? $request->attendance->clock_in->format('H:i') : '未出勤' }}</span>
          </div>
          <div class="info-item">
            <label>退勤時刻:</label>
            <span>{{ $request->attendance->clock_out ? $request->attendance->clock_out->format('H:i') : '未退勤' }}</span>
          </div>
          <div class="info-item">
            <label>備考:</label>
            <span>{{ $request->attendance->note ?? '備考なし' }}</span>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3>申請された修正内容</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>出勤時刻:</label>
            <span>{{ $request->requested_clock_in ? $request->requested_clock_in->format('H:i') : '変更なし' }}</span>
          </div>
          <div class="info-item">
            <label>退勤時刻:</label>
            <span>{{ $request->requested_clock_out ? $request->requested_clock_out->format('H:i') : '変更なし' }}</span>
          </div>
          <div class="info-item">
            <label>備考:</label>
            <span>{{ $request->requested_note ?? '変更なし' }}</span>
          </div>
        </div>
      </div>

      @if ($request->breakRequests->count() > 0)
        <div class="info-section">
          <h3>休憩時間の修正</h3>
          <div class="break-requests">
            @foreach ($request->breakRequests as $breakRequest)
              <div class="break-request-item">
                <span>{{ $breakRequest->requested_break_start->format('H:i') }} -
                  {{ $breakRequest->requested_break_end ? $breakRequest->requested_break_end->format('H:i') : '未終了' }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    @if ($request->status === 'pending')
      <div class="request-actions">
        <h3>承認処理</h3>
        <form action="{{ route('admin.approve-request', $request->id) }}" method="POST" class="approve-form">
          @csrf
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">承認</button>
            <a href="{{ route('admin.request-list') }}" class="btn btn-secondary">戻る</a>
          </div>
        </form>
      </div>
    @else
      <div class="request-actions">
        <a href="{{ route('admin.request-list') }}" class="btn btn-secondary">戻る</a>
      </div>
    @endif
  </div>
@endsection
