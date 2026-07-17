<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$me = current_admin();
$flash = '';
$flashType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id       = (int) ($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '') {
        $flash = 'Username and email are required.';
        $flashType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $flash = 'Please enter a valid email address.';
        $flashType = 'error';
    } elseif ($id === 0 && strlen($password) < 8) {
        $flash = 'Password must be at least 8 characters for a new admin.';
        $flashType = 'error';
    } else {
        $dupe = db()->prepare('SELECT id FROM admin_users WHERE username = ? AND id != ?');
        $dupe->execute([$username, $id]);
        if ($dupe->fetch()) {
            $flash = 'That username is already taken.';
            $flashType = 'error';
        } else {
            if ($id > 0) {
                if ($password !== '') {
                    if (strlen($password) < 8) {
                        $flash = 'Password must be at least 8 characters.';
                        $flashType = 'error';
                    } else {
                        db()->prepare('UPDATE admin_users SET username=?, email=?, password_hash=? WHERE id=?')
                            ->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $id]);
                        log_action('admin_update', "Updated admin #{$id} ({$username}), password reset");
                    }
                } else {
                    db()->prepare('UPDATE admin_users SET username=?, email=? WHERE id=?')
                        ->execute([$username, $email, $id]);
                    log_action('admin_update', "Updated admin #{$id} ({$username})");
                }
                if ($id === (int) $me['id'] && !$flash) {
                    $_SESSION['admin']['username'] = $username;
                }
            } else {
                db()->prepare('INSERT INTO admin_users (username, email, password_hash) VALUES (?,?,?)')
                    ->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
                log_action('admin_create', "Created admin ({$username})");
            }

            if (!$flash) {
                header('Location: admins.php?saved=1');
                exit;
            }
        }
    }
}

if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    $deleteId = (int) $_GET['delete'];
    $total = (int) db()->query('SELECT COUNT(*) c FROM admin_users')->fetch()['c'];

    if ($deleteId === (int) $me['id']) {
        $flash = 'You cannot delete your own account while logged in.';
        $flashType = 'error';
    } elseif ($total <= 1) {
        $flash = 'Cannot delete the last remaining admin account.';
        $flashType = 'error';
    } else {
        $target = db()->prepare('SELECT username FROM admin_users WHERE id = ?');
        $target->execute([$deleteId]);
        $target = $target->fetch();
        db()->prepare('DELETE FROM admin_users WHERE id = ?')->execute([$deleteId]);
        log_action('admin_delete', 'Deleted admin #' . $deleteId . ' (' . ($target['username'] ?? '') . ')');
        header('Location: admins.php?deleted=1');
        exit;
    }
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$admins = db()->query('SELECT * FROM admin_users ORDER BY created_at ASC')->fetchAll();

$admin_active = 'admins';
$admin_page_title = 'Admin Users';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Admin saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Admin deleted.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;"><?= $editing ? 'Edit Admin' : 'Add a New Admin' ?></h3>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <label>Username</label>
        <input type="text" name="username" required value="<?= e($editing['username'] ?? '') ?>">
        <label>Email</label>
        <input type="email" name="email" required value="<?= e($editing['email'] ?? '') ?>">
        <label>Password <?= $editing ? '(leave empty to keep current)' : '(min 8 characters)' ?></label>
        <input type="password" name="password" <?= $editing ? '' : 'required' ?>>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;"><?= $editing ? 'Save Changes' : 'Add Admin' ?></button>
        <?php if ($editing): ?><a href="admins.php" class="admin-btn admin-btn-secondary" style="margin-top:18px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">All Admins</h3>
    <table class="admin-table">
        <thead><tr><th>Username</th><th>Email</th><th>Created</th><th>Last Login</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($admins as $a): ?>
            <tr>
                <td><?= e($a['username']) ?><?= (int) $a['id'] === (int) $me['id'] ? ' <span style="color:var(--a-text); font-size:12px;">(you)</span>' : '' ?></td>
                <td><?= e($a['email']) ?></td>
                <td><?= e(date('M j, Y', strtotime($a['created_at']))) ?></td>
                <td><?= $a['last_login_at'] ? e(date('M j, Y g:ia', strtotime($a['last_login_at']))) : 'Never' ?></td>
                <td>
                    <a href="admins.php?edit=<?= (int) $a['id'] ?>" class="admin-btn admin-btn-secondary">Edit</a>
                    <?php if ((int) $a['id'] !== (int) $me['id']): ?>
                        <a href="admins.php?delete=<?= (int) $a['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this admin account?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
