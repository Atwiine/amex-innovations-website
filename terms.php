<?php
require_once __DIR__ . '/includes/functions.php';

$active = '';
$page_title = 'Terms of Use — Amex Innovations Ltd';
$page_description = 'Terms governing use of the Amex Innovations Ltd website.';
require __DIR__ . '/includes/header.php';

$company_email = setting('company_email', 'amexinnovationslt@gmail.com');
$company_address = setting('company_address', 'Mbarara City, Uganda');
?>
    <section class="ve-page-hero ve-page-hero-sm" style="background-image:url(<?= e(site_image('about_hero', 'img/bg-img/13.jpg')) ?>);">
        <div class="ve-page-hero-overlay"></div>
        <div class="container ve-page-hero-content">
            <span class="ve-section-tag">Legal</span>
            <h1>Terms of Use</h1>
            <nav aria-label="breadcrumb">
                <ol class="ve-breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Terms of Use</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="ve-section">
        <div class="container" style="max-width:820px;">
            <p style="color:var(--ve-text); font-size:14px;">Last updated: <?= date('F j, Y') ?></p>

            <p>These terms govern your use of this website, operated by Amex Innovations Ltd ("Amex Innovations", "we", "us"), based in Mbarara, Uganda. By browsing this site or submitting the contact form, you agree to these terms.</p>

            <h3>Purpose of this site</h3>
            <p>This website describes the software development services Amex Innovations offers and showcases past projects. It is informational — it does not process payments, create user accounts, or deliver software directly to visitors.</p>

            <h3>Project descriptions</h3>
            <p>The systems described on our <a href="projects.php">Projects page</a> were built by Amex Innovations for the named or described clients. Details are shared to illustrate our work and are kept general enough to respect client confidentiality; specifics of any engagement (pricing, timelines, proprietary features) remain between Amex Innovations and that client.</p>

            <h3>No professional advice</h3>
            <p>Content on this site (including service descriptions and FAQs) is general information about what we do, not a binding quote, specification, or professional advice for your specific situation. Any actual engagement is governed by a separate written agreement between Amex Innovations and the client.</p>

            <h3>Intellectual property</h3>
            <p>The text, images, and design of this website belong to Amex Innovations Ltd unless otherwise credited, and may not be copied or reused without permission.</p>

            <h3>Acceptable use</h3>
            <p>Please don't use this site to submit false information, attempt to access data that isn't yours, or interfere with the site's normal operation (including the contact form).</p>

            <h3>Limitation of liability</h3>
            <p>This site is provided "as is." While we take reasonable care to keep it accurate and available, Amex Innovations is not liable for losses arising from reliance on information published here, or from temporary unavailability of the site.</p>

            <h3>Changes</h3>
            <p>We may update these terms as our services or this website change. Continued use of the site after an update means you accept the revised terms.</p>

            <h3>Contact</h3>
            <p><?= e($company_email) ?><br><?= e($company_address) ?></p>
        </div>
    </section>
<?php require __DIR__ . '/includes/footer.php'; ?>
