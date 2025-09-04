@extends('layouts.app')

@section('title', 'スタッフ一覧')

@section('content')
  <div class="admin-staff-list-container">
    <div class="staff-list-header">
      <h2>スタッフ一覧</h2>
    </div>

    <div class="staff-table">
      <table>
        <thead>
          <tr>
            <th>氏名</th>
            <th>メールアドレス</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <a href="{{ route('admin.monthly-attendance', ['userId' => $user->id, 'month' => now()->format('Y-m')]) }}"
                  class="btn btn-small">詳細</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="no-data">スタッフが登録されていません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
