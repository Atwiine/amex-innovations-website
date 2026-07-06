<?php
require_once __DIR__ . '/functions.php';

$company_email = setting('company_email', 'amexinnovationslt@gmail.com');
$company_phone = setting('company_phone', '+256 779 008858');
$company_address = setting('company_address', 'Mbarara City, Uganda');
$facebook_url  = setting('facebook_url', '#');
$twitter_url   = setting('twitter_url', '#');
$linkedin_url  = setting('linkedin_url', '#');
$instagram_url = setting('instagram_url', '#');
?>
    <!-- ===== FOOTER ===== -->
    <footer class="ve-footer">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-4 mb-50">
                    <div class="ve-footer-brand">
                        <a href="index.php" class="ve-footer-logo">
                            <span class="ve-logo-icon"><img src="<?= e(site_image('site_logo', 'img/core-img/logo.png')) ?>" alt="Amex Innovations"></span>
                            <span class="ve-logo-text">Amex <strong>Innovations</strong></span>
                        </a>
                        <p>We build practical software, SaaS platforms, websites, IoT tools, and management systems that help organizations across Uganda work clearly, serve people better, and grow with confidence.</p>
                        <div class="ve-social">
                            <a href="<?= e($facebook_url) ?>" title="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="<?= e($twitter_url) ?>" title="Twitter / X"><i class="fa fa-twitter"></i></a>
                            <a href="<?= e($linkedin_url) ?>" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
                            <a href="<?= e($instagram_url) ?>" title="Instagram"><i class="fa fa-instagram"></i></a>
                            <a href="https://wa.me/<?= e(preg_replace('/\D/', '', $company_phone)) ?>" target="_blank" title="WhatsApp"><i class="fa fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2 mb-50">
                    <h5 class="ve-footer-title">Quick Links</h5>
                    <ul class="ve-footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="projects.php">Projects</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">What We Build</h5>
                    <ul class="ve-footer-links">
                        <li><a href="services.php">Custom Software Systems</a></li>
                        <li><a href="services.php">Business Automation Tools</a></li>
                        <li><a href="services.php">Websites &amp; Online Stores</a></li>
                        <li><a href="services.php">Dashboards &amp; Reports</a></li>
                        <li><a href="services.php">Digital Agriculture Tools</a></li>
                    </ul>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">Reach Us</h5>
                    <ul class="ve-footer-contact">
                        <li><i class="fa fa-map-marker"></i> <?= e($company_address) ?></li>
                        <li><i class="fa fa-phone"></i> <a href="tel:<?= e(preg_replace('/\s+/', '', $company_phone)) ?>" style="color:inherit;"><?= e($company_phone) ?></a></li>
                        <li><i class="fa fa-envelope"></i> <a href="mailto:<?= e($company_email) ?>" style="color:inherit;"><?= e($company_email) ?></a></li>
                        <li><i class="fa fa-clock-o"></i> Mon – Fri &nbsp;|&nbsp; 8am – 6pm EAT</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ve-footer-bottom">
            <div class="container">
                <div class="ve-footer-bottom-inner">
                    <p>&copy; <script>document.write(new Date().getFullYear());</script> Amex Innovations Ltd. All rights reserved. Mbarara, Uganda.</p>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Use</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/popper.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/plugins/plugins.js"></script>
    <script src="js/active.js"></script>
    <script src="js/vaultedge.js"></script>
</body>
</html>
