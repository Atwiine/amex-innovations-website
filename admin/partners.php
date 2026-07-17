<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$flash = '';
$flashType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id         = (int) ($_POST['id'] ?? 0);
    $name       = trim($_POST['name'] ?? '');
    $websiteUrl = trim($_POST['website_url'] ?? '');
    $sort       = (int) ($_POST['sort_order'] ?? 0);
    $active     = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '') {
        $flash = 'Name is required.';
        $flashType = 'error';
    } else {
        $logoPath = null;
        try {
            $logoPath = upload_image('logo', 'partners', 500);
        } catch (RuntimeException $e) {
            $flash = $e->getMessage();
            $flashType = 'error';
        }

        if (!$flash) {
            if ($id > 0) {
                if ($logoPath) {
                    $stmt = db()->prepare('UPDATE partners SET name=?, logo=?, website_url=?, sort_order=?, is_active=? WHERE id=?');
                    $stmt->execute([$name, $logoPath, $websiteUrl ?: null, $sort, $active, $id]);
                } else {
                    $stmt = db()->prepare('UPDATE partners SET name=?, website_url=?, sort_order=?, is_active=? WHERE id=?');
                    $stmt->execute([$name, $websiteUrl ?: null, $sort, $active, $id]);
                }
                log_action('partner_update', $name);
            } else {
                $stmt = db()->prepare('INSERT INTO partners (name, logo, website_url, sort_order, is_active) VALUES (?,?,?,?,?)');
                $stmt->execute([$name, $logoPath, $websiteUrl ?: null, $sort, $active]);
                log_action('partner_create', $name);
            }
            header('Location: partners.php?saved=1');
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    $deleteId = (int) $_GET['delete'];
    $target = db()->prepare('SELECT name FROM partners WHERE id = ?');
    $target->execute([$deleteId]);
    $target = $target->fetch();
    db()->prepare('DELETE FROM partners WHERE id = ?')->execute([$deleteId]);
    log_action('partner_delete', $target['name'] ?? "#$deleteId");
    header('Location: partners.php?deleted=1');
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM partners WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$partners = db()->query('SELECT * FROM partners ORDER BY sort_order ASC')->fetchAll();

$admin_active = 'partners';
$admin_page_title = 'Partners';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Partner saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Partner deleted.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;"><?= $editing ? 'Edit Partner' : 'Add a Partner' ?></h3>
    <p style="color:var(--a-text); font-size:13px;">Shown in the "Our Partners" section on the homepage. Only add organizations that have agreed to be publicly listed.</p>
    <form method="post" class="admin-form" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <label>Name</label>
        <input type="text" name="name" required value="<?= e($editing['name'] ?? '') ?>">
        <label>Website URL (optional — makes the logo/name clickable)</label>
        <input type="text" name="website_url" value="<?= e($editing['website_url'] ?? '') ?>">
        <label>Logo <?= $editing ? '(leave empty to keep current)' : '(optional — shown as a text badge if left blank)' ?></label>
        <img id="logo-preview" src="../<?= e($editing['logo'] ?? '') ?>" style="height:60px; max-width:160px; object-fit:contain; border-radius:6px; margin-bottom:8px; display:<?= ($editing['logo'] ?? '') ? 'block' : 'none' ?>; background:#f4f6fa; padding:6px;">
        <input type="file" name="logo" accept="image/*" onchange="previewImage(this, 'logo-preview', 'logo-filesize')">
        <div id="logo-filesize" style="font-size:12px; color:var(--a-text); margin-top:4px;">
            <?= $editing && $editing['logo'] ? 'Current size: ' . e(admin_file_size($editing['logo']) ?? 'unknown') : '' ?>
        </div>
        <label>Sort Order</label>
        <input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
        <label style="display:flex; align-items:center; gap:8px; margin-top:14px;">
            <input type="checkbox" name="is_active" style="width:auto;" <?= (!$editing || $editing['is_active']) ? 'checked' : '' ?>> Active (shown on the public site)
        </label>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;"><?= $editing ? 'Save Changes' : 'Add Partner' ?></button>
        <?php if ($editing): ?><a href="partners.php" class="admin-btn admin-btn-secondary" style="margin-top:18px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">All Partners</h3>
    <?php if (!$partners): ?>
        <p style="color:var(--a-text);">No partners added yet — the "Our Partners" section stays hidden on the homepage until you add at least one.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead><tr><th></th><th>Name</th><th>Website</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($partners as $p): ?>
            <tr>
                <td><?php if ($p['logo']): ?><img src="../<?= e($p['logo']) ?>" style="height:32px; max-width:100px; object-fit:contain;"><?php else: ?>—<?php endif; ?></td>
                <td><?= e($p['name']) ?></td>
                <td><?= $p['website_url'] ? e($p['website_url']) : '—' ?></td>
                <td><?= $p['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="partners.php?edit=<?= (int) $p['id'] ?>" class="admin-btn admin-btn-secondary">Edit</a>
                    <a href="partners.php?delete=<?= (int) $p['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this partner?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
function previewImage(input, previewId, sizeId) {
    const file = input.files[0];
    if (!file) return;
    const preview = document.getElementById(previewId);
    const sizeLabel = document.getElementById(sizeId);
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(file);
    const kb = file.size / 1024;
    const sizeText = kb < 1024 ? kb.toFixed(1) + ' KB' : (kb / 1024).toFixed(2) + ' MB';
    sizeLabel.textContent = 'Selected file: ' + sizeText + ' (before compression)';
}
</script>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
