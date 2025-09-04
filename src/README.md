# 勤怠管理システム

Laravel 8 を使用して構築された勤怠管理システムです。

## 機能

### 一般ユーザー機能

-   勤怠打刻（出勤・退勤・休憩）
-   勤怠一覧表示（月別）
-   勤怠詳細表示・修正申請
-   修正申請一覧（承認待ち・承認済み）

### 管理者機能

-   日次勤怠一覧
-   スタッフ一覧
-   スタッフ月次勤怠一覧
-   CSV 出力
-   勤怠詳細・修正
-   修正申請一覧・詳細・承認

## 技術仕様

-   **フレームワーク**: Laravel 8.75
-   **認証**: Laravel Fortify
-   **データベース**: MySQL
-   **フロントエンド**: Blade テンプレート + CSS
-   **その他**: Carbon（日時処理）

## セットアップ

### 1. 依存関係のインストール

```bash
composer install
npm install
```

### 2. 環境設定

```bash
cp .env.example .env
php artisan key:generate
```

### 3. データベース設定

`.env`ファイルでデータベース接続情報を設定してください。

### 4. マイグレーション実行

```bash
php artisan migrate
```

### 5. シーダー実行

```bash
php artisan db:seed
```

### 6. アプリケーション起動

```bash
php artisan serve
```

## テストアカウント

システム起動後、以下のアカウントでログインできます：

### 管理者アカウント

-   メール: admin@example.com
-   パスワード: password

### 一般ユーザーアカウント

-   メール: user@example.com
-   パスワード: password

## ディレクトリ構成

```
src/
├── app/
│   ├── Http/Controllers/
│   │   ├── AttendanceController.php    # 勤怠管理コントローラー
│   │   └── AdminController.php         # 管理者用コントローラー
│   ├── Models/
│   │   ├── Attendance.php              # 勤怠モデル
│   │   ├── AttendanceBreak.php        # 休憩モデル
│   │   ├── AttendanceRequest.php      # 修正申請モデル
│   │   └── BreakRequest.php           # 休憩修正申請モデル
│   └── ...
├── database/
│   ├── migrations/                     # データベースマイグレーション
│   └── seeders/                        # データシーダー
├── resources/views/
│   ├── attendance/                     # 勤怠管理画面
│   ├── admin/                          # 管理者画面
│   └── layouts/                        # レイアウト
└── public/css/
    └── attendance.css                  # 勤怠管理用スタイル
```

## 主要なルート

### 一般ユーザー

-   `GET /` - 勤怠打刻画面
-   `GET /attendance` - 勤怠一覧
-   `GET /attendance/{date}` - 勤怠詳細・修正申請
-   `GET /requests` - 修正申請一覧

### 管理者

-   `GET /admin/daily-attendance/{date?}` - 日次勤怠一覧
-   `GET /admin/staff` - スタッフ一覧
-   `GET /admin/staff/{userId}/monthly-attendance/{month?}` - スタッフ月次勤怠
-   `GET /admin/requests` - 修正申請一覧
-   `GET /admin/requests/{requestId}` - 修正申請詳細

## ライセンス

MIT License
