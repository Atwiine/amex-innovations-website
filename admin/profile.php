<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$admin = current_admin();
$flash = '';
$flashType = 'success';

$stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
$stmt->execute([$admin['id']]);
$me = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $form = $_POST['form'] ?? '';

    if ($form === 'details') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($username === '' || $email === '') {
            $flash = 'Username and email are required.';
            $flashType = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $flash = 'Please enter a valid email address.';
            $flashType = 'error';
        } else {
            $dupe = db()->prepare('SELECT id FROM admin_users WHERE username = ? AND id != ?');
            $dupe->execute([$username, $me['id']]);
            if ($dupe->fetch()) {
                $flash = 'That username is already taken.';
                $flashType = 'error';
            } else {
                db()->prepare('UPDATE admin_users SET username = ?, email = ? WHERE id = ?')
                    ->execute([$username, $email, $me['id']]);
                $_SESSION['admin']['username'] = $username;
                log_action('profile_update', "Updated own details");
                header('Location: profile.php?saved=1');
                exit;
            }
        }
    } elseif ($form === 'password') {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($current, $me['password_hash'])) {
            $flash = 'Your current password is incorrect.';
            $flashType = 'error';
        } elseif (strlen($new) < 8) {
            $flash = 'New password must be at least 8 characters.';
            $flashType = 'error';
        } elseif ($new !== $confirm) {
            $flash = 'New passwords do not match.';
            $flashType = 'error';
        } else {
            db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $me['id']]);
            log_action('password_change', 'Changed own password');
            header('Location: profile.php?password_saved=1');
            exit;
        }
    }
}

$admin_active = 'profile';
$admin_page_title = 'My Profile';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Profile updated.</div><?php endif; ?>
<?php if (isset($_GET['password_saved'])): ?><div class="admin-flash success">Password changed.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;">My Details</h3>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <input type="hidden" name="form" value="details">
        <label>Username</label>
        <input type="text" name="username" required value="<?= e($me['username']) ?>">
        <label>Email</label>
        <input type="email" name="email" required value="<?= e($me['email']) ?>">
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;">Save Details</button>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">Change Password</h3>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <input type="hidden" name="form" value="password">
        <label>Current Password</label>
        <input type="password" name="current_password" required>
        <label>New Password (min 8 characters)</label>
        <input type="password" name="new_password" required>
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;">Change Password</button>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">Account Info</h3>
    <p style="color:var(--a-text); font-size:14px;">
        Account created: <?= e(date('M j, Y g:ia', strtotime($me['created_at']))) ?><br>
        Last login: <?= $me['last_login_at'] ? e(date('M j, Y g:ia', strtotime($me['last_login_at']))) : 'This session' ?>
    </p>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
