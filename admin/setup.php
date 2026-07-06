<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
start_session_once();

$existing = (int) db()->query('SELECT COUNT(*) c FROM admin_users')->fetch()['c'];
if ($existing > 0) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = db()->prepare('INSERT INTO admin_users (username, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
        header('Location: login.php?created=1');
        exit;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin Account — Amex Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:#071730; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
        .box { background:#fff; border-radius:12px; padding:36px; width:100%; max-width:380px; }
        h1 { font-size:20px; margin:0 0 6px; }
        p.sub { color:#4a5568; font-size:13px; margin:0 0 20px; }
        label { display:block; font-size:13px; font-weight:700; margin:14px 0 6px; }
        input { width:100%; padding:10px 12px; border:1px solid #e3e7ee; border-radius:8px; font-size:14px; box-sizing:border-box; }
        button { width:100%; margin-top:20px; padding:12px; border:none; border-radius:8px; background:linear-gradient(135deg,#0D47A1,#1976D2); color:#fff; font-weight:700; cursor:pointer; }
        .error { background:#fdecea; color:#c62828; padding:10px 14px; border-radius:8px; font-size:13px; margin-top:16px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Create the Admin Account</h1>
        <p class="sub">This runs once. After the first admin is created, this page will redirect to login.</p>
        <form method="post">
            <?= csrf_field() ?>
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password (min 8 characters)</label>
            <input type="password" name="password" required>
            <label>Confirm Password</label>
            <input type="password" name="confirm" required>
            <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
            <button type="submit">Create Account</button>
        </form>
    </div>
</body>
</html>
