<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$perPage = 50;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if (!empty($_GET['admin'])) {
    $where[] = 'admin_username = ?';
    $params[] = $_GET['admin'];
}
if (!empty($_GET['action'])) {
    $where[] = 'action = ?';
    $params[] = $_GET['action'];
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$total = db()->prepare("SELECT COUNT(*) c FROM audit_log $whereSql");
$total->execute($params);
$total = (int) $total->fetch()['c'];

$stmt = db()->prepare("SELECT * FROM audit_log $whereSql ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$logs = $stmt->fetchAll();

$admins = db()->query('SELECT DISTINCT admin_username FROM audit_log ORDER BY admin_username')->fetchAll(PDO::FETCH_COLUMN);
$actions = db()->query('SELECT DISTINCT action FROM audit_log ORDER BY action')->fetchAll(PDO::FETCH_COLUMN);

$totalPages = max(1, (int) ceil($total / $perPage));

$admin_active = 'audit-log';
$admin_page_title = 'Audit Log';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-card">
    <h3 style="margin-top:0;">Activity Log</h3>
    <p style="color:var(--a-text); font-size:13px;">Every login and content change made through this admin panel, most recent first.</p>

    <form method="get" style="display:flex; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
        <select name="admin" onchange="this.form.submit()" style="padding:8px 12px; border:1px solid var(--a-border); border-radius:8px;">
            <option value="">All admins</option>
            <?php foreach ($admins as $a): ?>
                <option value="<?= e($a) ?>" <?= ($_GET['admin'] ?? '') === $a ? 'selected' : '' ?>><?= e($a) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="action" onchange="this.form.submit()" style="padding:8px 12px; border:1px solid var(--a-border); border-radius:8px;">
            <option value="">All actions</option>
            <?php foreach ($actions as $act): ?>
                <option value="<?= e($act) ?>" <?= ($_GET['action'] ?? '') === $act ? 'selected' : '' ?>><?= e($act) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($_GET['admin']) || !empty($_GET['action'])): ?>
            <a href="audit-log.php" class="admin-btn admin-btn-secondary">Clear Filters</a>
        <?php endif; ?>
    </form>

    <table class="admin-table">
        <thead><tr><th>When</th><th>Admin</th><th>Action</th><th>Details</th><th>IP</th></tr></thead>
        <tbody>
        <?php foreach ($logs as $l): ?>
            <tr>
                <td style="white-space:nowrap;"><?= e(date('M j, Y g:ia', strtotime($l['created_at']))) ?></td>
                <td><?= e($l['admin_username']) ?></td>
                <td><span class="badge badge-new"><?= e($l['action']) ?></span></td>
                <td><?= e($l['details']) ?></td>
                <td style="color:var(--a-text); font-size:12px;"><?= e($l['ip_address']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$logs): ?><tr><td colspan="5" style="color:var(--a-text);">No log entries found.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
    <div style="display:flex; gap:8px; margin-top:16px;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?= $p ?><?= !empty($_GET['admin']) ? '&admin=' . urlencode($_GET['admin']) : '' ?><?= !empty($_GET['action']) ? '&action=' . urlencode($_GET['action']) : '' ?>"
               class="admin-btn <?= $p === $page ? 'admin-btn-primary' : 'admin-btn-secondary' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
