@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
  <div class="auth-container">
    <div class="auth-form">
      <h2>ログイン</h2>

      <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
          @error('email')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="password">パスワード</label>
          <input type="password" id="password" name="password" required>
          @error('password')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            ログイン状態を保持する
          </label>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">ログイン</button>
        </div>

        <div class="auth-links">
          <a href="{{ route('register') }}">会員登録はこちら</a>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">パスワードを忘れた方はこちら</a>
          @endif
        </div>
      </form>
    </div>
  </div>
@endsection
