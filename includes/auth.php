<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function start_session_once() {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => defined('SITE_HTTPS') && SITE_HTTPS,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function current_admin() {
    start_session_once();
    return $_SESSION['admin'] ?? null;
}

function require_admin() {
    start_session_once();
    if (empty($_SESSION['admin'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token() {
    start_session_once();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify() {
    start_session_once();
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(400);
        die('Invalid or expired form submission. Please go back and try again.');
    }
}

// Basic brute-force throttle: 5 failed attempts locks login for 5 minutes.
function login_is_locked() {
    start_session_once();
    $until = $_SESSION['login_locked_until'] ?? 0;
    return $until > time();
}

function login_record_failure() {
    start_session_once();
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_locked_until'] = time() + 300;
        $_SESSION['login_attempts'] = 0;
    }
}

function login_reset_failures() {
    start_session_once();
    unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
}

function attempt_login($username, $password) {
    if (login_is_locked()) {
        return false;
    }
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        login_record_failure();
        return false;
    }

    login_reset_failures();
    start_session_once();
    session_regenerate_id(true);
    $_SESSION['admin'] = ['id' => $user['id'], 'username' => $user['username']];

    $upd = db()->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id = ?');
    $upd->execute([$user['id']]);

    return true;
}

function logout_admin() {
    start_session_once();
    $_SESSION = [];
    session_destroy();
}
