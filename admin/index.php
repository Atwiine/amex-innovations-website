<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$counts = [
    'services' => (int) db()->query('SELECT COUNT(*) c FROM services')->fetch()['c'],
    'projects' => (int) db()->query('SELECT COUNT(*) c FROM projects')->fetch()['c'],
    'team'     => (int) db()->query('SELECT COUNT(*) c FROM team_members')->fetch()['c'],
    'new_msgs' => (int) db()->query("SELECT COUNT(*) c FROM contact_messages WHERE status = 'new'")->fetch()['c'],
];

$recent = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5')->fetchAll();

$admin_active = 'dashboard';
$admin_page_title = 'Dashboard';
require __DIR__ . '/includes/admin-header.php';
?>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:18px; margin-bottom:24px;">
    <div class="admin-card"><div style="font-size:28px; font-weight:800;"><?= $counts['services'] ?></div><div style="color:var(--a-text); font-size:13px;">Services</div></div>
    <div class="admin-card"><div style="font-size:28px; font-weight:800;"><?= $counts['projects'] ?></div><div style="color:var(--a-text); font-size:13px;">Projects</div></div>
    <div class="admin-card"><div style="font-size:28px; font-weight:800;"><?= $counts['team'] ?></div><div style="color:var(--a-text); font-size:13px;">Team Members</div></div>
    <div class="admin-card"><div style="font-size:28px; font-weight:800; color:#0D47A1;"><?= $counts['new_msgs'] ?></div><div style="color:var(--a-text); font-size:13px;">New Messages</div></div>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">Recent Contact Messages</h3>
    <?php if (!$recent): ?>
        <p style="color:var(--a-text);">No messages yet.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Received</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $m): ?>
            <tr>
                <td><a href="messages.php?view=<?= (int) $m['id'] ?>"><?= e($m['name']) ?></a></td>
                <td><?= e($m['email']) ?></td>
                <td><?= e($m['service'] ?: '—') ?></td>
                <td><span class="badge badge-<?= e($m['status']) ?>"><?= e(ucfirst($m['status'])) ?></span></td>
                <td><?= e(date('M j, Y g:ia', strtotime($m['created_at']))) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p style="margin-top:14px;"><a href="messages.php" class="admin-btn admin-btn-secondary">View All Messages</a></p>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
