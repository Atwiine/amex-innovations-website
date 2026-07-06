<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
start_session_once();

$existing = (int) db()->query('SELECT COUNT(*) c FROM admin_users')->fetch()['c'];
if ($existing === 0) {
    header('Location: setup.php');
    exit;
}

if (current_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$justCreated = isset($_GET['created']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    if (login_is_locked()) {
        $error = 'Too many failed attempts. Please wait a few minutes and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (attempt_login($username, $password)) {
            header('Location: index.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login — Amex Innovations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:#071730; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
        .box { background:#fff; border-radius:12px; padding:36px; width:100%; max-width:360px; }
        h1 { font-size:20px; margin:0 0 20px; }
        label { display:block; font-size:13px; font-weight:700; margin:14px 0 6px; }
        input { width:100%; padding:10px 12px; border:1px solid #e3e7ee; border-radius:8px; font-size:14px; box-sizing:border-box; }
        button { width:100%; margin-top:20px; padding:12px; border:none; border-radius:8px; background:linear-gradient(135deg,#0D47A1,#1976D2); color:#fff; font-weight:700; cursor:pointer; }
        .error { background:#fdecea; color:#c62828; padding:10px 14px; border-radius:8px; font-size:13px; margin-top:16px; }
        .success { background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Amex Admin Login</h1>
        <?php if ($justCreated): ?><div class="success">Admin account created. Please log in.</div><?php endif; ?>
        <form method="post">
            <?= csrf_field() ?>
            <label>Username</label>
            <input type="text" name="username" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>
