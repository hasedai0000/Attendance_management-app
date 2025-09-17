# 勤怠管理システム ER 図

```mermaid
erDiagram
    %% ユーザー関連
    USERS {
        char36 id PK "UUID"
        varchar255 name "ユーザー名"
        varchar255 email UK "メールアドレス"
        timestamp email_verified_at "メール認証日時"
        varchar255 password "パスワード（ハッシュ化）"
        boolean is_admin "管理者フラグ"
        varchar100 remember_token "ログイン記憶トークン"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    USER_SESSIONS {
        char36 id PK "UUID"
        char36 user_id FK "ユーザーID"
        varchar255 session_token UK "セッショントークン"
        varchar45 ip_address "IPアドレス"
        text user_agent "ユーザーエージェント"
        timestamp expires_at "有効期限"
        timestamp last_activity_at "最終アクティビティ"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    PASSWORD_RESETS {
        varchar255 email "メールアドレス"
        varchar255 token "リセットトークン"
        timestamp created_at "作成日時"
    }

    %% 勤怠関連
    ATTENDANCES {
        char36 id PK "UUID"
        char36 user_id FK "ユーザーID"
        date date "勤怠日"
        time clock_in "出勤時刻"
        time clock_out "退勤時刻"
        text note "備考"
        enum status "ステータス"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    BREAKS {
        char36 id PK "UUID"
        char36 attendance_id FK "勤怠ID"
        time break_start "休憩開始時刻"
        time break_end "休憩終了時刻"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    %% 申請関連
    ATTENDANCE_REQUESTS {
        char36 id PK "UUID"
        char36 attendance_id FK "勤怠ID"
        char36 user_id FK "申請者ID"
        datetime requested_clock_in "申請出勤時刻"
        datetime requested_clock_out "申請退勤時刻"
        text requested_note "申請備考"
        enum status "申請ステータス"
        timestamp approved_at "承認日時"
        char36 approved_by FK "承認者ID"
        text rejection_reason "却下理由"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    BREAK_REQUESTS {
        char36 id PK "UUID"
        char36 attendance_request_id FK "勤怠修正申請ID"
        char36 break_id FK "休憩ID"
        time requested_break_start "申請休憩開始時刻"
        time requested_break_end "申請休憩終了時刻"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    %% 監査・システム関連
    AUDIT_LOGS {
        char36 id PK "UUID"
        char36 user_id FK "ユーザーID"
        varchar100 action "操作内容"
        varchar100 target_entity "対象エンティティ"
        char36 target_id "対象ID"
        json old_values "変更前の値"
        json new_values "変更後の値"
        varchar45 ip_address "IPアドレス"
        text user_agent "ユーザーエージェント"
        timestamp created_at "作成日時"
    }

    FAILED_JOBS {
        bigint id PK "AUTO_INCREMENT"
        varchar255 uuid UK "UUID"
        text connection "接続情報"
        text queue "キュー名"
        longtext payload "ペイロード"
        longtext exception "例外情報"
        timestamp failed_at "失敗日時"
    }

    PERSONAL_ACCESS_TOKENS {
        bigint id PK "AUTO_INCREMENT"
        varchar255 tokenable_type "トークン対象タイプ"
        char36 tokenable_id "トークン対象ID"
        varchar255 name "トークン名"
        varchar64 token UK "トークン"
        text abilities "権限"
        timestamp last_used_at "最終使用日時"
        timestamp expires_at "有効期限"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    %% リレーションシップ
    USERS ||--o{ USER_SESSIONS : "has"
    USERS ||--o{ ATTENDANCES : "creates"
    USERS ||--o{ ATTENDANCE_REQUESTS : "requests"
    USERS ||--o{ ATTENDANCE_REQUESTS : "approves"
    USERS ||--o{ AUDIT_LOGS : "performs"
    USERS ||--o{ PASSWORD_RESETS : "resets"

    ATTENDANCES ||--o{ BREAKS : "contains"
    ATTENDANCES ||--o{ ATTENDANCE_REQUESTS : "has_requests"

    ATTENDANCE_REQUESTS ||--o{ BREAK_REQUESTS : "includes"
    BREAKS ||--o{ BREAK_REQUESTS : "modifies"
```

## エンティティ詳細

### USERS（ユーザー）

- **id**: 主キー（UUID）
- **name**: ユーザー名
- **email**: メールアドレス（ユニーク）
- **email_verified_at**: メール認証日時
- **password**: パスワード（ハッシュ化）
- **is_admin**: 管理者フラグ
- **remember_token**: ログイン記憶トークン
- **created_at**: 作成日時
- **updated_at**: 更新日時

### ATTENDANCES（勤怠）

- **id**: 主キー（UUID）
- **user_id**: ユーザー ID（外部キー）
- **date**: 勤怠日
- **clock_in**: 出勤時刻
- **clock_out**: 退勤時刻
- **note**: 備考
- **status**: ステータス（off_duty, working, break, finished）
- **created_at**: 作成日時
- **updated_at**: 更新日時

### BREAKS（休憩）

- **id**: 主キー（UUID）
- **attendance_id**: 勤怠 ID（外部キー）
- **break_start**: 休憩開始時刻
- **break_end**: 休憩終了時刻
- **created_at**: 作成日時
- **updated_at**: 更新日時

### ATTENDANCE_REQUESTS（勤怠修正申請）

- **id**: 主キー（UUID）
- **attendance_id**: 勤怠 ID（外部キー）
- **user_id**: 申請者 ID（外部キー）
- **requested_clock_in**: 申請出勤時刻
- **requested_clock_out**: 申請退勤時刻
- **requested_note**: 申請備考
- **status**: ステータス（pending, approved）
- **approved_at**: 承認日時
- **approved_by**: 承認者 ID（外部キー）
- **created_at**: 作成日時
- **updated_at**: 更新日時

### BREAK_REQUESTS（休憩修正申請）

- **id**: 主キー（UUID）
- **attendance_request_id**: 勤怠修正申請 ID（外部キー）
- **break_id**: 休憩 ID（外部キー）
- **requested_break_start**: 申請休憩開始時刻
- **requested_break_end**: 申請休憩終了時刻
- **created_at**: 作成日時
- **updated_at**: 更新日時

## 関係の詳細

1. **USERS → ATTENDANCES**: 1 対多（1 人のユーザーは複数の勤怠記録を持つ）
2. **ATTENDANCES → BREAKS**: 1 対多（1 つの勤怠記録は複数の休憩記録を持つ）
3. **ATTENDANCES → ATTENDANCE_REQUESTS**: 1 対多（1 つの勤怠記録は複数の修正申請を持つ）
4. **USERS → ATTENDANCE_REQUESTS**: 1 対多（1 人のユーザーは複数の勤怠修正申請を行う）
5. **USERS → ATTENDANCE_REQUESTS**: 1 対多（1 人の管理者は複数の申請を承認する）
6. **ATTENDANCE_REQUESTS → BREAK_REQUESTS**: 1 対多（1 つの勤怠修正申請は複数の休憩修正申請を持つ）
7. **BREAKS → BREAK_REQUESTS**: 1 対多（1 つの休憩記録は複数の休憩修正申請を持つ）
