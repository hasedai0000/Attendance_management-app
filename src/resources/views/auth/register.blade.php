@extends('layouts.app')

@section('title', '会員登録')

@section('content')
  <div class="auth-container">
    <div class="auth-form">
      <h2>会員登録</h2>

      <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <div class="form-group">
          <label for="name">お名前</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
          @error('name')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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
          <label for="password_confirmation">確認用パスワード</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required>
          @error('password_confirmation')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">会員登録</button>
        </div>

        <div class="auth-links">
          <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
      </form>
    </div>
  </div>
@endsection
