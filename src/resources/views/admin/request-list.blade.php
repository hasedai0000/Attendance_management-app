@extends('layouts.app')

@section('title', '修正申請一覧')

@section('content')
  <div class="admin-request-list-container">
    <div class="request-list-header">
      <h2>修正申請一覧</h2>
    </div>

    <div class="request-tabs">
      <div class="tab-header">
        <button class="tab-button active" onclick="showTab('pending')">承認待ち</button>
        <button class="tab-button" onclick="showTab('approved')">承認済み</button>
      </div>

      <div id="pending-tab" class="tab-content active">
        <h3>承認待ち</h3>
        @if ($pendingRequests->count() > 0)
          <div class="request-table">
            <table>
              <thead>
                <tr>
                  <th>申請日</th>
                  <th>申請者</th>
                  <th>対象日</th>
                  <th>申請内容</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($pendingRequests as $request)
                  <tr>
                    <td>{{ $request->created_at->format('m/d H:i') }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->attendance->date->format('m/d') }}</td>
                    <td>
                      @if ($request->requested_clock_in || $request->requested_clock_out)
                        時刻修正
                      @endif
                      @if ($request->requested_note)
                        備考修正
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('admin.request-detail', $request->id) }}" class="btn btn-small">詳細</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="no-data">承認待ちの申請はありません</p>
        @endif
      </div>

      <div id="approved-tab" class="tab-content">
        <h3>承認済み</h3>
        @if ($approvedRequests->count() > 0)
          <div class="request-table">
            <table>
              <thead>
                <tr>
                  <th>申請日</th>
                  <th>承認日</th>
                  <th>申請者</th>
                  <th>対象日</th>
                  <th>申請内容</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($approvedRequests as $request)
                  <tr>
                    <td>{{ $request->created_at->format('m/d H:i') }}</td>
                    <td>{{ $request->approved_at->format('m/d H:i') }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->attendance->date->format('m/d') }}</td>
                    <td>
                      @if ($request->requested_clock_in || $request->requested_clock_out)
                        時刻修正
                      @endif
                      @if ($request->requested_note)
                        備考修正
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('admin.request-detail', $request->id) }}" class="btn btn-small">詳細</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="no-data">承認済みの申請はありません</p>
        @endif
      </div>
    </div>
  </div>

  <script>
    function showTab(tabName) {
      // タブボタンのアクティブ状態を更新
      document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
      });
      event.target.classList.add('active');

      // タブコンテンツの表示を更新
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      document.getElementById(tabName + '-tab').classList.add('active');
    }
  </script>
@endsection
