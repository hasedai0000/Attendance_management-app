@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
  <div class="auth-container">
    <div class="auth-form">
      <h2>メール認証が必要です</h2>

      <div class="verification-notice">
        <p>ご登録いただいたメールアドレスに認証メールを送信しました。</p>
        <p>メール内のリンクをクリックして認証を完了してください。</p>
      </div>

      <div class="verification-actions">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button type="submit" class="btn btn-primary">認証メール再送</button>
        </form>
      </div>

      <div class="auth-links">
        <a href="{{ route('login') }}">ログインに戻る</a>
      </div>
    </div>
  </div>
@endsection
