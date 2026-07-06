<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$validStatuses = ['new', 'read', 'replied', 'archived'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id = (int) ($_POST['id'] ?? 0);
    if (isset($_POST['set_status']) && in_array($_POST['set_status'], $validStatuses, true)) {
        db()->prepare('UPDATE contact_messages SET status = ? WHERE id = ?')->execute([$_POST['set_status'], $id]);
    }
    header('Location: messages.php' . ($id ? '?view=' . $id : ''));
    exit;
}

if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    db()->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([(int) $_GET['delete']]);
    header('Location: messages.php?deleted=1');
    exit;
}

$filter = $_GET['status'] ?? '';
if ($filter && in_array($filter, $validStatuses, true)) {
    $stmt = db()->prepare('SELECT * FROM contact_messages WHERE status = ? ORDER BY created_at DESC');
    $stmt->execute([$filter]);
} else {
    $stmt = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
}
$messages = $stmt->fetchAll();

$viewing = null;
if (isset($_GET['view'])) {
    $vs = db()->prepare('SELECT * FROM contact_messages WHERE id = ?');
    $vs->execute([(int) $_GET['view']]);
    $viewing = $vs->fetch();
    if ($viewing && $viewing['status'] === 'new') {
        db()->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$viewing['id']]);
        $viewing['status'] = 'read';
    }
}

$admin_active = 'messages';
$admin_page_title = 'Messages';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Message deleted.</div><?php endif; ?>

<?php if ($viewing): ?>
<div class="admin-card">
    <h3 style="margin-top:0;"><?= e($viewing['name']) ?> <span class="badge badge-<?= e($viewing['status']) ?>"><?= e(ucfirst($viewing['status'])) ?></span></h3>
    <p style="color:var(--a-text); font-size:13px;">
        <?= e($viewing['email']) ?><?= $viewing['phone'] ? ' · ' . e($viewing['phone']) : '' ?>
        <?= $viewing['service'] ? ' · ' . e($viewing['service']) : '' ?>
        · <?= e(date('M j, Y g:ia', strtotime($viewing['created_at']))) ?>
    </p>
    <p style="white-space:pre-wrap; line-height:1.7;"><?= e($viewing['message']) ?></p>

    <form method="post" style="display:inline;">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $viewing['id'] ?>">
        <?php foreach (['read', 'replied', 'archived'] as $s): ?>
            <?php if ($s !== $viewing['status']): ?>
                <button type="submit" name="set_status" value="<?= e($s) ?>" class="admin-btn admin-btn-secondary">Mark <?= e(ucfirst($s)) ?></button>
            <?php endif; ?>
        <?php endforeach; ?>
    </form>
    <a href="mailto:<?= e($viewing['email']) ?>" class="admin-btn admin-btn-primary">Reply by Email</a>
    <a href="messages.php?delete=<?= (int) $viewing['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this message?');">Delete</a>
    <a href="messages.php" class="admin-btn admin-btn-secondary">Back to Inbox</a>
</div>
<?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;">Contact Messages</h3>
    <p>
        <a href="messages.php" class="admin-btn <?= $filter === '' ? 'admin-btn-primary' : 'admin-btn-secondary' ?>">All</a>
        <?php foreach ($validStatuses as $s): ?>
            <a href="messages.php?status=<?= e($s) ?>" class="admin-btn <?= $filter === $s ? 'admin-btn-primary' : 'admin-btn-secondary' ?>"><?= e(ucfirst($s)) ?></a>
        <?php endforeach; ?>
    </p>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Received</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($messages as $m): ?>
            <tr>
                <td><a href="messages.php?view=<?= (int) $m['id'] ?>"><?= e($m['name']) ?></a></td>
                <td><?= e($m['email']) ?></td>
                <td><?= e($m['service'] ?: '—') ?></td>
                <td><span class="badge badge-<?= e($m['status']) ?>"><?= e(ucfirst($m['status'])) ?></span></td>
                <td><?= e(date('M j, Y g:ia', strtotime($m['created_at']))) ?></td>
                <td><a href="messages.php?view=<?= (int) $m['id'] ?>" class="admin-btn admin-btn-secondary">View</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$messages): ?><tr><td colspan="6" style="color:var(--a-text);">No messages found.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
