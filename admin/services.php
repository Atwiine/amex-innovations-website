<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$flash = '';
$flashType = 'success';

function service_slugify($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

// ── Handle POST (create / update) ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id      = (int) ($_POST['id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $icon    = trim($_POST['icon'] ?? 'fa-cog');
    $summary = trim($_POST['summary'] ?? '');
    $usedIn  = trim($_POST['used_in'] ?? '');
    $sort    = (int) ($_POST['sort_order'] ?? 0);
    $active  = isset($_POST['is_active']) ? 1 : 0;

    if ($title === '' || $summary === '') {
        $flash = 'Title and summary are required.';
        $flashType = 'error';
    } else {
        $slug = service_slugify($title);
        if ($id > 0) {
            $stmt = db()->prepare('UPDATE services SET title=?, slug=?, icon=?, summary=?, used_in=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$title, $slug, $icon, $summary, $usedIn, $sort, $active, $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO services (title, slug, icon, summary, used_in, sort_order, is_active) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([$title, $slug, $icon, $summary, $usedIn, $sort, $active]);
        }
        header('Location: services.php?saved=1');
        exit;
    }
}

// ── Handle delete ───────────────────────────────────────────────
if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    db()->prepare('DELETE FROM services WHERE id = ?')->execute([(int) $_GET['delete']]);
    header('Location: services.php?deleted=1');
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$services = db()->query('SELECT * FROM services ORDER BY sort_order ASC')->fetchAll();

$admin_active = 'services';
$admin_page_title = 'Services';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Service saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Service deleted.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;"><?= $editing ? 'Edit Service' : 'Add a Service' ?></h3>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <label>Title</label>
        <input type="text" name="title" required value="<?= e($editing['title'] ?? '') ?>">
        <label>Icon (Font Awesome class, e.g. fa-cloud)</label>
        <input type="text" name="icon" value="<?= e($editing['icon'] ?? 'fa-cog') ?>">
        <label>Summary</label>
        <textarea name="summary" required><?= e($editing['summary'] ?? '') ?></textarea>
        <label>Used In (optional italic line shown under summary)</label>
        <input type="text" name="used_in" value="<?= e($editing['used_in'] ?? '') ?>">
        <label>Sort Order</label>
        <input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
        <label style="display:flex; align-items:center; gap:8px; margin-top:14px;">
            <input type="checkbox" name="is_active" style="width:auto;" <?= (!$editing || $editing['is_active']) ? 'checked' : '' ?>> Active (shown on the public site)
        </label>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;"><?= $editing ? 'Save Changes' : 'Add Service' ?></button>
        <?php if ($editing): ?><a href="services.php" class="admin-btn admin-btn-secondary" style="margin-top:18px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">All Services</h3>
    <table class="admin-table">
        <thead><tr><th>#</th><th>Title</th><th>Icon</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($services as $s): ?>
            <tr>
                <td><?= (int) $s['sort_order'] ?></td>
                <td><?= e($s['title']) ?></td>
                <td><i class="fa <?= e($s['icon']) ?>"></i> <?= e($s['icon']) ?></td>
                <td><?= $s['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="services.php?edit=<?= (int) $s['id'] ?>" class="admin-btn admin-btn-secondary">Edit</a>
                    <a href="services.php?delete=<?= (int) $s['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this service?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
