<?php
/**
 * Kindle PDF送信アプリの認証処理
 * 
 * 設定ファイルで認証が有効になっている場合に使用されます
 */

// セッションを開始
session_start();

// 設定ファイルの読み込み
$config = include 'config.php';

// 認証が無効に設定されている場合、認証済みとみなす
if (!isset($config['auth_enabled']) || $config['auth_enabled'] === false) {
    $_SESSION['authenticated'] = true;
    header('Location: index.html');
    exit;
}

// IP制限がある場合はチェック
if (!empty($config['allowed_ips']) && is_array($config['allowed_ips'])) {
    $client_ip = $_SERVER['REMOTE_ADDR'];
    if (in_array($client_ip, $config['allowed_ips'])) {
        $_SESSION['authenticated'] = true;
        header('Location: index.html');
        exit;
    }
}

// ログインフォームの処理
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $config['auth_username'] && $password === $config['auth_password']) {
        $_SESSION['authenticated'] = true;
        header('Location: index.html');
        exit;
    } else {
        $error = 'ユーザー名またはパスワードが正しくありません。';
    }
}

// 認証が済んでいれば、メインページにリダイレクト
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - Kindle PDF送信</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .login-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
        }
        h1 {
            color: #0066cc;
            margin-top: 0;
            font-weight: 500;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0055aa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Kindle PDF送信</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="ユーザー名" required autofocus>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>
