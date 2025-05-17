<?php
// エラーレポーティングを設定（本番環境では必要に応じて調整）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Composerのオートロードを読み込む
require 'vendor/autoload.php';

// PHPMailerクラスのインポート
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// セッションを開始（認証機能を実装する場合に必要）
session_start();

// 簡易認証チェック（必要に応じて有効化）
/*
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "認証が必要です。";
    exit;
}
*/

// 設定ファイルの読み込み
$config = include 'config.php';

// アップロードファイルのバリデーション
if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
    $errorMessage = "";
    
    if (isset($_FILES['pdf'])) {
        switch ($_FILES['pdf']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errorMessage = "ファイルサイズが大きすぎます。";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMessage = "ファイルが一部しかアップロードされませんでした。";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMessage = "ファイルが選択されていません。";
                break;
            default:
                $errorMessage = "アップロードエラー（コード: " . $_FILES['pdf']['error'] . "）";
        }
    } else {
        $errorMessage = "ファイルが送信されていません。";
    }
    
    echo $errorMessage;
    exit;
}

// ファイルタイプの確認
$finfo = new finfo(FILEINFO_MIME_TYPE);
$fileType = $finfo->file($_FILES['pdf']['tmp_name']);

if ($fileType !== 'application/pdf') {
    echo "PDFファイルのみアップロード可能です。";
    exit;
}

// ファイルサイズの確認（設定値を利用）
$maxFileSize = isset($config['max_file_size'])
    ? (int)$config['max_file_size']
    : 50 * 1024 * 1024; // デフォルト50MB

if ($_FILES['pdf']['size'] > $maxFileSize) {
    $limitMB = round($maxFileSize / (1024 * 1024));
    echo "ファイルサイズが制限（{$limitMB}MB）を超えています。";
    exit;
}

// 一時ファイルパスとファイル名を取得
$tempFilePath = $_FILES['pdf']['tmp_name'];
$fileName = $_FILES['pdf']['name'];

// メール送信処理
try {
    // PHPMailerのインスタンス作成
    $mail = new PHPMailer(true);
    
    // SMTPサーバー設定
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp_user'];
    $mail->Password   = $config['smtp_pass'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    
    // デバッグ出力（開発時のみ）
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    
    // 送信元・送信先設定
    $mail->setFrom($config['from_email'], $config['from_name'] ?? '');
    $mail->addAddress($config['kindle_email']);
    
    // メール設定
    $mail->Subject = 'convert'; // Kindle自動変換のための件名
    $mail->Body    = ''; // 本文は空でOK
    
    // PDFファイルを添付
    $mail->addAttachment($tempFilePath, $fileName);
    
    // メール送信
    $mail->send();
    
    // 成功メッセージを返す
    echo "Kindleに「{$fileName}」を送信しました！";
    
} catch (Exception $e) {
    // エラーメッセージを返す
    echo "送信に失敗しました: " . $mail->ErrorInfo;
}

// アップロードされた一時ファイルを削除
if (file_exists($tempFilePath)) {
    unlink($tempFilePath);
}
