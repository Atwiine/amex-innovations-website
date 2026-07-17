<?php
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

$pages = [
    ['path' => 'index.php',    'priority' => '1.0', 'changefreq' => 'weekly'],
    ['path' => 'about.php',    'priority' => '0.8', 'changefreq' => 'monthly'],
    ['path' => 'services.php', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['path' => 'projects.php', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['path' => 'contact.php',  'priority' => '0.7', 'changefreq' => 'monthly'],
    ['path' => 'privacy.php',  'priority' => '0.2', 'changefreq' => 'yearly'],
    ['path' => 'terms.php',    'priority' => '0.2', 'changefreq' => 'yearly'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $p): ?>
    <url>
        <loc><?= e(site_url($p['path'])) ?></loc>
        <changefreq><?= e($p['changefreq']) ?></changefreq>
        <priority><?= e($p['priority']) ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
