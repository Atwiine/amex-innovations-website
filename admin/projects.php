<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$flash = '';
$flashType = 'success';

$categories = ['health', 'agri', 'restate', 'business', 'church', 'edu', 'fintech', 'suite'];
$headerClasses = ['bg-blue', 'bg-orange', 'bg-teal', 'bg-purple', 'bg-dark', 'bg-green', 'bg-red'];

function project_slugify($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id          = (int) ($_POST['id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $category    = in_array($_POST['category'] ?? '', $categories, true) ? $_POST['category'] : 'business';
    $icon        = trim($_POST['icon'] ?? 'fa-cog');
    $headerClass = in_array($_POST['header_class'] ?? '', $headerClasses, true) ? $_POST['header_class'] : 'bg-blue';
    $lead        = trim($_POST['lead'] ?? '');
    $problem     = trim($_POST['problem'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $techs       = trim($_POST['techs'] ?? '');
    $projectUrl  = trim($_POST['project_url'] ?? '');
    $statusLabel = trim($_POST['status_label'] ?? 'Live');
    $statusType  = ($_POST['status_type'] ?? 'live') === 'deployed' ? 'deployed' : 'live';
    $sort        = (int) ($_POST['sort_order'] ?? 0);
    $active      = isset($_POST['is_active']) ? 1 : 0;

    if ($title === '' || $lead === '') {
        $flash = 'Title and lead description are required.';
        $flashType = 'error';
    } else {
        $slug = project_slugify($title);
        if ($id > 0) {
            $stmt = db()->prepare('UPDATE projects SET title=?, slug=?, category=?, icon=?, header_class=?, lead=?, problem=?, features=?, techs=?, project_url=?, status_label=?, status_type=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$title, $slug, $category, $icon, $headerClass, $lead, $problem, $features, $techs, $projectUrl ?: null, $statusLabel, $statusType, $sort, $active, $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO projects (title, slug, category, icon, header_class, lead, problem, features, techs, project_url, status_label, status_type, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$title, $slug, $category, $icon, $headerClass, $lead, $problem, $features, $techs, $projectUrl ?: null, $statusLabel, $statusType, $sort, $active]);
        }
        header('Location: projects.php?saved=1');
        exit;
    }
}

if (isset($_GET['delete'])) {
    if (!hash_equals(csrf_token(), $_GET['token'] ?? '')) {
        http_response_code(400);
        die('Invalid request.');
    }
    db()->prepare('DELETE FROM projects WHERE id = ?')->execute([(int) $_GET['delete']]);
    header('Location: projects.php?deleted=1');
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$projects = db()->query('SELECT * FROM projects ORDER BY sort_order ASC')->fetchAll();

$admin_active = 'projects';
$admin_page_title = 'Projects';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Project saved.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="admin-flash success">Project deleted.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;"><?= $editing ? 'Edit Project' : 'Add a Project' ?></h3>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <label>Title</label>
        <input type="text" name="title" required value="<?= e($editing['title'] ?? '') ?>">

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;">
            <div>
                <label>Category</label>
                <select name="category">
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= e($c) ?>" <?= ($editing['category'] ?? '') === $c ? 'selected' : '' ?>><?= e(project_category_meta($c)[0]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Icon (Font Awesome class)</label>
                <input type="text" name="icon" value="<?= e($editing['icon'] ?? 'fa-cog') ?>">
            </div>
            <div>
                <label>Card Header Color</label>
                <select name="header_class">
                    <?php foreach ($headerClasses as $h): ?>
                        <option value="<?= e($h) ?>" <?= ($editing['header_class'] ?? '') === $h ? 'selected' : '' ?>><?= e($h) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label>Lead (short summary shown on the card)</label>
        <textarea name="lead" required><?= e($editing['lead'] ?? '') ?></textarea>

        <label>Problem It Solves (shown in the detail modal)</label>
        <textarea name="problem"><?= e($editing['problem'] ?? '') ?></textarea>

        <label>Key Features (one per line)</label>
        <textarea name="features" style="min-height:130px;"><?= e($editing['features'] ?? '') ?></textarea>

        <label>Technology Used (one per line)</label>
        <textarea name="techs"><?= e($editing['techs'] ?? '') ?></textarea>

        <label>Live Project URL (optional)</label>
        <input type="text" name="project_url" value="<?= e($editing['project_url'] ?? '') ?>">

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;">
            <div>
                <label>Status Label</label>
                <input type="text" name="status_label" value="<?= e($editing['status_label'] ?? 'Live') ?>">
            </div>
            <div>
                <label>Status Type</label>
                <select name="status_type">
                    <option value="live" <?= ($editing['status_type'] ?? 'live') === 'live' ? 'selected' : '' ?>>Live</option>
                    <option value="deployed" <?= ($editing['status_type'] ?? '') === 'deployed' ? 'selected' : '' ?>>Deployed</option>
                </select>
            </div>
            <div>
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
            </div>
        </div>

        <label style="display:flex; align-items:center; gap:8px; margin-top:14px;">
            <input type="checkbox" name="is_active" style="width:auto;" <?= (!$editing || $editing['is_active']) ? 'checked' : '' ?>> Active (shown on the public site)
        </label>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;"><?= $editing ? 'Save Changes' : 'Add Project' ?></button>
        <?php if ($editing): ?><a href="projects.php" class="admin-btn admin-btn-secondary" style="margin-top:18px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">All Projects</h3>
    <table class="admin-table">
        <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Status</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($projects as $p): ?>
            <tr>
                <td><?= (int) $p['sort_order'] ?></td>
                <td><?= e($p['title']) ?></td>
                <td><?= e(project_category_meta($p['category'])[0]) ?></td>
                <td><?= e($p['status_label']) ?></td>
                <td><?= $p['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="projects.php?edit=<?= (int) $p['id'] ?>" class="admin-btn admin-btn-secondary">Edit</a>
                    <a href="projects.php?delete=<?= (int) $p['id'] ?>&token=<?= e(csrf_token()) ?>" class="admin-btn admin-btn-danger" onclick="return confirm('Delete this project?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
