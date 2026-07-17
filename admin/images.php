<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$flash = '';
$flashType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $key = $_POST['image_key'] ?? '';
    $quality = (int) ($_POST['quality'] ?? 82);
    $row = db()->prepare('SELECT * FROM site_images WHERE image_key = ?');
    $row->execute([$key]);
    $row = $row->fetch();

    if (!$row) {
        $flash = 'Unknown image slot.';
        $flashType = 'error';
    } else {
        try {
            $path = upload_image('image', 'site', 1920, $quality);
            if ($path) {
                db()->prepare('UPDATE site_images SET path = ? WHERE image_key = ?')->execute([$path, $key]);
                log_action('image_replace', $row['label']);
                header('Location: images.php?saved=1');
                exit;
            }
            $flash = 'Please choose an image to upload.';
            $flashType = 'error';
        } catch (RuntimeException $e) {
            $flash = $e->getMessage();
            $flashType = 'error';
        }
    }
}

$images = db()->query('SELECT * FROM site_images ORDER BY page_group, sort_order')->fetchAll();
$grouped = [];
foreach ($images as $img) {
    $grouped[$img['page_group']][] = $img;
}

$admin_active = 'images';
$admin_page_title = 'Images';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Image updated.</div><?php endif; ?>
<?php if ($flash): ?><div class="admin-flash <?= e($flashType) ?>"><?= e($flash) ?></div><?php endif; ?>

<?php foreach ($grouped as $group => $rows): ?>
<div class="admin-card">
    <h3 style="margin-top:0;"><?= e($group) ?></h3>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:20px;">
        <?php foreach ($rows as $img): $k = e($img['image_key']); ?>
        <div style="border:1px solid var(--a-border); border-radius:10px; padding:14px;">
            <img id="preview-<?= $k ?>" src="../<?= e($img['path']) ?>" style="width:100%; height:120px; object-fit:cover; border-radius:8px; background:#eef1f6; margin-bottom:6px;">
            <div style="font-size:13px; font-weight:700;"><?= e($img['label']) ?></div>
            <div id="filesize-<?= $k ?>" style="font-size:12px; color:var(--a-text); margin-bottom:10px;">Current size: <?= e(admin_file_size($img['path']) ?? 'unknown') ?></div>
            <form method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="image_key" value="<?= $k ?>">
                <input type="file" name="image" accept="image/*" style="width:100%; font-size:12px; margin-bottom:10px;"
                       onchange="previewImage(this, 'preview-<?= $k ?>', 'filesize-<?= $k ?>')">

                <label style="font-size:12px; font-weight:700; display:flex; justify-content:space-between;">
                    <span>Compression quality</span>
                    <span id="qval-<?= $k ?>">82</span>
                </label>
                <input type="range" name="quality" min="50" max="95" value="82" style="width:100%; margin-bottom:10px;"
                       oninput="document.getElementById('qval-<?= $k ?>').textContent = this.value">
                <p style="font-size:11px; color:var(--a-text); margin:-6px 0 10px;">Lower = smaller file, more compression. Higher = better quality, larger file. Images are also auto-resized to a max of 1920px.</p>

                <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;">Replace</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

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
