<?php
/**
 * Kindle PDF送信アプリの設定ファイル
 * 
 * このファイルはGitに含めないでください
 * .gitignoreに追加してください
 */

return [
    // Kindleのメールアドレス（送信先）
    'kindle_email' => 'your-kindle-email@kindle.com',
    
    // 送信元メールアドレス（Amazonで承認済みのアドレス）
    'from_email' => 'your-email@example.com',
    
    // 送信者名（任意）
    'from_name' => 'Kindle PDF送信アプリ',
    
    // SMTPサーバー設定
    'smtp_host' => 'smtp.example.com',  // SMTPサーバーのホスト名
    'smtp_user' => 'your-email@example.com',  // SMTPユーザー名
    'smtp_pass' => 'your-smtp-password',  // SMTPパスワード
    
    // セキュリティ設定
    'allowed_ips' => [
        '127.0.0.1',  // ローカルホスト
        // 'xxx.xxx.xxx.xxx',  // 必要に応じて許可するIPを追加
    ],
    
    // 認証設定（簡易認証を使用する場合）
    'auth_enabled' => false,  // trueに設定すると認証が有効になる
    'auth_username' => 'admin',
    'auth_password' => 'password',  // 本番環境では強力なパスワードを使用してください
    
    // アップロード設定
    'max_file_size' => 50 * 1024 * 1024,  // 最大ファイルサイズ（バイト単位）、デフォルト50MB
];
