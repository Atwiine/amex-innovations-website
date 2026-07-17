<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$fields = [
    'company_email'   => 'Company Email',
    'company_phone'   => 'Company Phone (used for calls & WhatsApp)',
    'company_phone_2' => 'Second Phone (display only, no WhatsApp link — leave blank to hide)',
    'company_address' => 'Company Address',
    'facebook_url'    => 'Facebook URL',
    'twitter_url'     => 'Twitter / X URL',
    'linkedin_url'    => 'LinkedIn URL',
    'instagram_url'   => 'Instagram URL',
];

$seoFields = [
    'site_domain'       => 'Site Domain (e.g. https://amexinnovations.com — used for canonical links, sitemap, and social sharing)',
    'ga_measurement_id' => 'Google Analytics Measurement ID (e.g. G-XXXXXXXXXX — leave blank to disable)',
];

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $stmt = db()->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    // Only update keys that were actually part of the submitted form
    // (the page has two separate forms, each covering a subset of settings).
    $changedKeys = [];
    foreach (array_keys($fields + $seoFields) as $key) {
        if (array_key_exists($key, $_POST)) {
            $stmt->execute([$key, trim($_POST[$key])]);
            $changedKeys[] = $key;
        }
    }
    log_action('settings_update', implode(', ', $changedKeys));
    header('Location: settings.php?saved=1');
    exit;
}

$current = [];
foreach (db()->query('SELECT setting_key, setting_value FROM site_settings') as $row) {
    $current[$row['setting_key']] = $row['setting_value'];
}

$admin_active = 'settings';
$admin_page_title = 'Settings';
require __DIR__ . '/includes/admin-header.php';
?>

<?php if (isset($_GET['saved'])): ?><div class="admin-flash success">Settings saved.</div><?php endif; ?>

<div class="admin-card">
    <h3 style="margin-top:0;">Site Settings</h3>
    <p style="color:var(--a-text); font-size:13px;">These values control the contact details and social links shown across the public site footer, header, and contact page.</p>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <?php foreach ($fields as $key => $label): ?>
            <label><?= e($label) ?></label>
            <input type="text" name="<?= e($key) ?>" value="<?= e($current[$key] ?? '') ?>">
        <?php endforeach; ?>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;">Save Settings</button>
    </form>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">SEO &amp; Analytics</h3>
    <p style="color:var(--a-text); font-size:13px;">These control canonical URLs, the sitemap, social share previews, and analytics tracking.</p>
    <form method="post" class="admin-form">
        <?= csrf_field() ?>
        <?php foreach ($seoFields as $key => $label): ?>
            <label><?= e($label) ?></label>
            <input type="text" name="<?= e($key) ?>" value="<?= e($current[$key] ?? '') ?>">
        <?php endforeach; ?>
        <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:18px;">Save SEO Settings</button>
    </form>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
