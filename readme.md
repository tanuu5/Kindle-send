# Kindle PDF送信アプリの設置手順

このドキュメントでは、Kindle PDF送信アプリのセットアップ方法を説明します。

## 1. 必要な環境

- PHP 7.4以上
- Composerがインストールされていること
- SMTPサーバーへのアクセス情報

## 2. インストール手順

### 2.1 ファイルの配置

1. リポジトリのファイルをWebサーバーの公開ディレクトリに配置します。

### 2.2 PHPMailerのインストール

1. ターミナルを開き、プロジェクトディレクトリに移動します。
2. 以下のコマンドを実行してPHPMailerをインストールします。

```bash
composer require phpmailer/phpmailer
```

### 2.3 設定ファイルの編集

1. `config.php`ファイルを開き、以下の設定を編集します。
    - `kindle_email`: KindleのEメールアドレス
    - `from_email`: Amazon承認済みの送信元Eメールアドレス
    - `smtp_host`: SMTPサーバーのホスト名
    - `smtp_user`: SMTPユーザー名
    - `smtp_pass`: SMTPパスワード
    - セキュリティ設定（必要に応じて）

### 2.4 ディレクトリのパーミッション設定

1. PHPからファイルが書き込めるように、一時ディレクトリのパーミッションを設定します。

```bash
chmod 755 /path/to/temp
```

## 3. セキュリティ設定

### 3.1 Amazonでの送信元メールアドレス承認

1. Amazonアカウントにログインします。
2. 「アカウントサービス」→「コンテンツと端末の管理」→「設定」→「パーソナル・ドキュメント設定」を開きます。
3. 「承認済みEメールリスト」に使用する送信元メールアドレスを追加します。

### 3.2 認証設定（オプション）

1. 認証を有効にする場合は、`config.php`の`auth_enabled`を`true`に設定します。
2. `auth_username`と`auth_password`を安全な値に変更します。

### 3.3 IP制限（オプション）

1. 特定のIPアドレスからのみアクセスを許可する場合は、`config.php`の`allowed_ips`配列に許可するIPアドレスを追加します。

## 4. 動作確認

1. Webブラウザでアプリにアクセスします。
2. PDFファイルを選択して「Kindleに送信」ボタンをクリックします。
3. 成功メッセージが表示されることを確認します。
4. Kindleデバイスまたはアプリで、送信したPDFファイルが表示されることを確認します。

## 5. トラブルシューティング

### 5.1 ファイルサイズの制限

PHPの設定でアップロードファイルサイズの制限を確認し、必要に応じて`php.ini`を編集します。

```
upload_max_filesize = 50M
post_max_size = 55M
```

### 5.2 メール送信の問題

- SMTPサーバーの設定が正しいか確認します。
- Amazonの承認済みEメールリストに送信元アドレスが追加されているか確認します。
- メールサーバーのログを確認します。

### 5.3 アップロードの問題

- アップロードディレクトリのパーミッションを確認します。
- PHPのエラーログを確認します。

## 6. 補足

- セキュリティを考慮して、`config.php`をGitリポジトリに含めないようにしてください。
- 本番環境では、HTTPS接続を使用することを強く推奨します。
- 定期的にPHPMailerを更新して、セキュリティパッチを適用してください。
