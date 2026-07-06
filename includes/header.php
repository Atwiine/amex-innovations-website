<?php
/**
 * Shared site header. Expects (optionally) $page_title, $page_description,
 * $page_image, $page_canonical, and $active set by the including page before
 * this file is required.
 */
require_once __DIR__ . '/functions.php';

$active = $active ?? '';
$page_title = $page_title ?? 'Amex Innovations Ltd';
$page_description = $page_description ?? 'Amex Innovations Ltd builds practical software, management systems, SaaS platforms, IoT tools, and digital solutions for businesses and organizations across Uganda.';
$page_image = $page_image ?? site_image('og_image', 'img/core-img/logo.png');
$page_canonical = $page_canonical ?? site_url();

$ga_id = setting('ga_measurement_id', '');

function nav_active($key, $active) {
    return $key === $active ? ' class="active"' : '';
}

$org_schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'LocalBusiness',
    'name'     => 'Amex Innovations Ltd',
    'url'      => setting('site_domain', ''),
    'logo'     => site_url(site_image('site_logo', 'img/core-img/logo.png')),
    'image'    => site_url($page_image),
    'telephone'=> setting('company_phone', ''),
    'email'    => setting('company_email', ''),
    'address'  => [
        '@type'          => 'PostalAddress',
        'addressLocality'=> setting('company_address', 'Mbarara, Uganda'),
        'addressCountry' => 'UG',
    ],
    'sameAs' => array_values(array_filter([
        setting('facebook_url', ''), setting('twitter_url', ''),
        setting('linkedin_url', ''), setting('instagram_url', ''),
    ], fn($u) => $u && $u !== '#')),
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= e($page_description) ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index, follow">
    <title><?= e($page_title) ?></title>
    <link rel="canonical" href="<?= e($page_canonical) ?>">
    <link rel="icon" type="image/png" href="img/core-img/logo.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/custom-override.css">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Amex Innovations Ltd">
    <meta property="og:title" content="<?= e($page_title) ?>">
    <meta property="og:description" content="<?= e($page_description) ?>">
    <meta property="og:url" content="<?= e($page_canonical) ?>">
    <meta property="og:image" content="<?= e(site_url($page_image)) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($page_title) ?>">
    <meta name="twitter:description" content="<?= e($page_description) ?>">
    <meta name="twitter:image" content="<?= e(site_url($page_image)) ?>">

    <!-- Structured data -->
    <script type="application/ld+json"><?= json_encode($org_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

    <?php if ($ga_id): ?>
    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga_id) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= e($ga_id) ?>');
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <header class="ve-header" id="ve-sticky">
        <div class="container-fluid ve-nav-wrap">
            <div class="ve-logo">
                <a href="index.php">
                    <span class="ve-logo-icon"><img src="<?= e(site_image('site_logo', 'img/core-img/logo.png')) ?>" alt="Amex Innovations"></span>
                    <span class="ve-logo-text">Amex <strong>Innovations</strong></span>
                </a>
            </div>
            <nav class="ve-nav">
                <ul>
                    <li><a href="index.php"<?= nav_active('home', $active) ?>>Home</a></li>
                    <li class="has-drop">
                        <a href="about.php"<?= nav_active('about', $active) ?>>About <i class="fa fa-angle-down"></i></a>
                        <ul class="ve-dropdown">
                            <li><a href="about.php">Who We Are</a></li>
                            <li><a href="about.php#mission">Mission &amp; Vision</a></li>
                            <li><a href="about.php#team">The Team</a></li>
                        </ul>
                    </li>
                    <li><a href="services.php"<?= nav_active('services', $active) ?>>Services</a></li>
                    <li><a href="projects.php"<?= nav_active('projects', $active) ?>>Projects</a></li>
                    <li><a href="contact.php"<?= nav_active('contact', $active) ?>>Contact</a></li>
                </ul>
            </nav>
            <div class="ve-nav-cta">
                <a href="contact.php" class="ve-cta-btn">Let's Talk <i class="fa fa-arrow-right"></i></a>
            </div>
            <button class="ve-toggler" id="ve-toggle"><span></span><span></span><span></span></button>
        </div>
        <div class="ve-mobile-menu" id="ve-mobile-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </header>
