@extends('layouts.app')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/auth/admin-login.css') }}">
@endsection

@section('content')
  <div class="admin-login">
    <!-- メインコンテンツ -->
    <div class="admin-login__content">
      <div class="admin-login__form-container">
        <h1 class="admin-login__title">管理者ログイン</h1>

        <form class="admin-login__form" action="/admin/login" method="post">
          @csrf

          <div class="admin-login__field">
            <label class="admin-login__label">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" class="admin-login__input" required />
            @error('email')
              <div class="admin-login__error">{{ $message }}</div>
            @enderror
          </div>

          <div class="admin-login__field">
            <label class="admin-login__label">パスワード</label>
            <input type="password" name="password" class="admin-login__input" required />
            @error('password')
              <div class="admin-login__error">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="admin-login__button">
            管理者ログインする
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
