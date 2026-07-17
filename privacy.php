<?php
require_once __DIR__ . '/includes/functions.php';

$active = '';
$page_title = 'Privacy Policy — Amex Innovations Ltd';
$page_description = 'How Amex Innovations Ltd collects, uses, and protects information submitted through this website.';
require __DIR__ . '/includes/header.php';

$company_email = setting('company_email', 'amexinnovationslt@gmail.com');
$company_phone = setting('company_phone', '+256 779 008858');
$company_address = setting('company_address', 'Mbarara City, Uganda');
$ga_enabled = (bool) setting('ga_measurement_id', '');
?>
    <section class="ve-page-hero ve-page-hero-sm" style="background-image:url(<?= e(site_image('about_hero', 'img/bg-img/13.jpg')) ?>);">
        <div class="ve-page-hero-overlay"></div>
        <div class="container ve-page-hero-content">
            <span class="ve-section-tag">Legal</span>
            <h1>Privacy Policy</h1>
            <nav aria-label="breadcrumb">
                <ol class="ve-breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Privacy Policy</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="ve-section">
        <div class="container" style="max-width:820px;">
            <p style="color:var(--ve-text); font-size:14px;">Last updated: <?= date('F j, Y') ?></p>

            <p>Amex Innovations Ltd ("Amex Innovations", "we", "us") operates this website to describe our services and let visitors get in touch with us. This policy explains what information we collect when you use this site, why, and how it's handled, in line with Uganda's Data Protection and Privacy Act, 2019.</p>

            <h3>Information we collect</h3>
            <p>We only collect information you choose to give us. The main way this happens is through the <a href="contact.php">contact form</a>, where we ask for your name, email address, phone number (optional), the service you're interested in (optional), and your message.</p>
            <p>We do not require you to create an account, and we do not collect payment information on this site.</p>

            <h3>How we use it</h3>
            <p>Information submitted through the contact form is used only to respond to your enquiry — for example, to email or call you back about the project or question you raised. It is stored securely in our database and is visible only to Amex Innovations staff who manage enquiries.</p>
            <p>We do not sell, rent, or share your information with third parties for marketing purposes.</p>

            <h3>How long we keep it</h3>
            <p>We retain contact form submissions for as long as reasonably necessary to respond to your enquiry and maintain a record of client communications. You can ask us to delete your information at any time using the contact details below.</p>

            <h3>Cookies and analytics</h3>
            <?php if ($ga_enabled): ?>
            <p>This site uses Google Analytics to understand how visitors use it (for example, which pages are most viewed). Google Analytics uses cookies and collects information such as your approximate location, device type, and browsing behavior on this site. This data is aggregated and does not identify you personally. You can opt out using a browser extension such as Google's Analytics Opt-out Add-on, or by using your browser's "Do Not Track" / cookie-blocking settings.</p>
            <?php else: ?>
            <p>This site does not currently use analytics or tracking cookies. If that changes, this policy will be updated to describe what's collected and how to opt out.</p>
            <?php endif; ?>

            <h3>Your rights</h3>
            <p>You may ask us to: tell you what information we hold about you; correct inaccurate information; or delete your information. To do any of these, contact us using the details below and we will respond within a reasonable time.</p>

            <h3>Contact us about privacy</h3>
            <p>
                <?= e($company_email) ?><br>
                <?= e($company_phone) ?><br>
                <?= e($company_address) ?>
            </p>

            <h3>Changes to this policy</h3>
            <p>We may update this policy from time to time as our website or practices change. The date at the top of this page shows when it was last revised.</p>
        </div>
    </section>
<?php require __DIR__ . '/includes/footer.php'; ?>
