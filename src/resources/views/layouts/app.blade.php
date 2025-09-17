<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('css/attendance.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  @yield('css')
</head>

<body>
  @if (request()->is('admin/login'))
    <!-- 管理者ログイン画面専用ヘッダー -->
    <header class="admin-login__header">
      <div class="admin-login__logo">
        <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" class="admin-login__logo-img">
      </div>
    </header>
  @else
    <!-- 通常のヘッダー -->
    <header class="header">
      <div class="header__inner">
        <a class="header__logo" href="/">
          <img src="{{ asset('images/Free Market App Logo.svg') }}" alt="CorpTech フリマ">
        </a>
        <nav>
          <ul class="header-nav">
            @if (Auth::check() && Auth::user()->hasVerifiedEmail())
              @if (Auth::user()->isAdmin())
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/attendance">勤怠一覧</a>
                </li>
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/admin/staff">スタッフ一覧</a>
                </li>
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/admin/requests">申請一覧</a>
                </li>
              @else
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/">勤怠</a>
                </li>
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/attendance">勤怠一覧</a>
                </li>
                <li class="header-nav__item">
                  <a class="header-nav__link" href="/requests">申請</a>
                </li>
              @endif
              <li class="header-nav__item">
                <form class="form" action="/logout" method="post">
                  @csrf
                  <button class="header-nav__button">ログアウト</button>
                </form>
              </li>
            @else
              <li class="header-nav__item">
                <a class="header-nav__link" href="/login">ログイン</a>
              </li>
              <li class="header-nav__item">
                <a class="header-nav__link" href="/register">新規登録</a>
              </li>
            @endif
          </ul>
        </nav>
      </div>
    </header>
  @endif

  <main>
    @yield('content')
  </main>
</body>

</html>
