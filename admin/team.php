<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$flash = '';
$flashType = 'success';
$platforms = ['linkedin', 'twitter', 'github', 'facebook', 'instagram'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id    = (int) ($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $role  = trim($_POST['role'] ?? '');
    $bio   = trim($_POST['bio'] ?? '');
    $sort  = (int) ($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    $social1Platform = in_array($_POST['social1_platform'] ?? '', $platforms, true) ? $_POST['social1_platform'] : 'linkedin';
    $social1Url = trim($_POST['social1_url'] ?? '#') ?: '#';
    $social2Platform = in_array($_POST['social2_platform'] ?? '', $platforms, true) ? $_POST['social2_platform'] : 'twitter';
    $social2Url = trim($_POST['social2_url'] ?? '#') ?: '#';

    if ($name === '' || $role === '') {
        $flash = 'Name and role are required.';
        $flashType = 'error';
    } else {
        $photoPath = null;
        $quality = (int) ($_POST['quality'] ?? 82);
        try {
            $photoPath = upload_image('photo', 'team', 1000, $quality);
        } catch (RuntimeException $e) {
            $flash = $e->getMessage();
            $flashType = 'error';
        }

        if (!$flash) {
            if ($id > 0) {
                if ($photoPath) {
                    $stmt = db()->prepare('UPDATE team_members SET name=?, role=?, bio=?, photo=?, social1_platform=?, social1_url=?, social2_platform=?, social2_url=?, sort_order=?, is_active=? WHERE id=?');
                    $stmt->execute([$name, $role, $bio, $photoPath, $social1Platform, $social1Url, $social2Platform, $social2Url, $sort, $active, $id]);
                } else {
                    $stmt = db()->prepare('UPDATE team_members SET name=?, role=?, bio=?, social1_platform=?, social1_url=?, social2_platform=?, social2_url=?, sort_order=?, is_active=? WHERE id=?');
                    $stmt->execute([$name, $role, $bio, $social1Platform, $social1Url, $social2Platform, $social2Url, $sort, $active, $id]);
                }
            } else {
                $stmt = db()->prepare('INSERT INTO team_members (name, role, bio, photo, social1_platform, social1_url, social2_platform, social2_url, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$name, $role, $bio, $photoPath ?: 'img/core-img/logo.png', $social1Platform, $social1Url, $social2Platform, $social2Url, $sort, $active]);
            }
            header('Location: team.php?saved=1');
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    db()->prepare('DELETE FROM team_members WHERE id = ?')->execute([(int) $_GET['delete']]);
    header('Location: team.php?deleted=1');
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM team_members WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$team = db()->query('SELECT * FROM team_members ORDER BY sort_order ASC')->fetchAll();

$admin_active = 'team';
$admin_page_title = 'Team';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Team member saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Team member deleted.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;"><?= $editing ? 'Edit Team Member' : 'Add a Team Member' ?></h3>
    <form method="post" class="admin-form" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <label>Name</label>
        <input type="text" name="name" required value="<?= e($editing['name'] ?? '') ?>">
        <label>Role</label>
        <input type="text" name="role" required value="<?= e($editing['role'] ?? '') ?>">
        <label>Bio</label>
        <textarea name="bio"><?= e($editing['bio'] ?? '') ?></textarea>
        <label>Photo <?= $editing ? '(leave empty to keep current)' : '' ?></label>
        <img id="photo-preview" src="../<?= e($editing['photo'] ?? 'img/core-img/logo.png') ?>" style="height:90px; width:90px; object-fit:cover; border-radius:8px; margin-bottom:8px; display:block;">
        <input type="file" name="photo" accept="image/*" onchange="previewImage(this, 'photo-preview', 'photo-filesize')">
        <div id="photo-filesize" style="font-size:12px; color:var(--a-text); margin-top:4px;">
            <?= $editing && $editing['photo'] ? 'Current size: ' . e(admin_file_size($editing['photo']) ?? 'unknown') : 'JPG, PNG, WEBP, or GIF' ?>
        </div>

        <label style="display:flex; justify-content:space-between; margin-top:14px;">
            <span>Compression quality</span>
            <span id="qval">82</span>
        </label>
        <input type="range" name="quality" min="50" max="95" value="82" style="width:100%;"
               oninput="document.getElementById('qval').textContent = this.value">
        <p style="font-size:11px; color:var(--a-text); margin:6px 0 0;">Lower = smaller file, more compression. Photos are also auto-resized to a max of 1000px and straightened if sideways.</p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <div>
                <label>Social Link 1 — Platform</label>
                <select name="social1_platform">
                    <?php foreach ($platforms as $p): ?>
                        <option value="<?= e($p) ?>" <?= ($editing['social1_platform'] ?? 'linkedin') === $p ? 'selected' : '' ?>><?= e(ucfirst($p)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Social Link 1 — URL</label>
                <input type="text" name="social1_url" value="<?= e($editing['social1_url'] ?? '#') ?>">
            </div>
            <div>
                <label>Social Link 2 — Platform</label>
                <select name="social2_platform">
                    <?php foreach ($platforms as $p): ?>
                        <option value="<?= e($p) ?>" <?= ($editing['social2_platform'] ?? 'twitter') === $p ? 'selected' : '' ?>><?= e(ucfirst($p)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Social Link 2 — URL</label>
                <input type="text" name="social2_url" value="<?= e($editing['social2_url'] ?? '#') ?>">
            </div>
        </div>
        <label>Sort Order</label>
        <input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
        <label style="display:flex; align-items:center; gap:8px; margin-top:14px;">
            <input type="checkbox" name="is_active" style="width:auto;" <?= (!$editing || $editing['is_active']) ? 'checked' : '' ?>> Active (shown on the public site)
        </label>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;"><?= $editing ? 'Save Changes' : 'Add Team Member' ?></button>
        <?php if ($editing): ?><a href="team.php" class="admin-btn admin-btn-secondary" style="margin-top:18px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">All Team Members</h3>
    <table class="admin-table">
        <thead><tr><th></th><th>Name</th><th>Role</th><th>Photo Size</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($team as $m): ?>
            <tr>
                <td><img src="../<?= e($m['photo']) ?>" style="width:40px; height:40px; border-radius:8px; object-fit:cover;"></td>
                <td><?= e($m['name']) ?></td>
                <td><?= e($m['role']) ?></td>
                <td><?= e(admin_file_size($m['photo']) ?? '—') ?></td>
                <td><?= $m['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="team.php?edit=<?= (int) $m['id'] ?>" class="admin-btn admin-btn-secondary">Edit</a>
                    <a href="team.php?delete=<?= (int) $m['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this team member?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function previewImage(input, previewId, sizeId) {
    const file = input.files[0];
    if (!file) return;
    const preview = document.getElementById(previewId);
    const sizeLabel = document.getElementById(sizeId);
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; };
    reader.readAsDataURL(file);
    const kb = file.size / 1024;
    const sizeText = kb < 1024 ? kb.toFixed(1) + ' KB' : (kb / 1024).toFixed(2) + ' MB';
    sizeLabel.textContent = 'Selected file: ' + sizeText + ' (before compression)';
}
</script>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
