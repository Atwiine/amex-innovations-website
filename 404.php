<?php
require_once __DIR__ . '/includes/functions.php';

http_response_code(404);

$active = '';
$page_title = 'Page Not Found — Amex Innovations Ltd';
$page_description = 'The page you are looking for could not be found.';
require __DIR__ . '/includes/header.php';
?>
    <section class="ve-section" style="padding:120px 0; text-align:center;">
        <div class="container">
            <h1 style="font-size:64px; margin-bottom:10px;">404</h1>
            <h2>Page Not Found</h2>
            <p style="max-width:480px; margin:0 auto 30px;">The page you're looking for may have moved or no longer exists. Let's get you back on track.</p>
            <a href="index.php" class="ve-btn-primary">Back to Homepage</a>
        </div>
    </section>
<?php require __DIR__ . '/includes/footer.php'; ?>
