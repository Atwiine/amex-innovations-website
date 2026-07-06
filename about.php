<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'about';
$page_title = 'About Us — Amex Innovations Ltd';
$page_description = 'Amex Innovations Ltd is a Mbarara-based technology company building practical software, SaaS platforms, IoT tools, and management systems for organizations across Uganda.';
require __DIR__ . '/includes/header.php';

$team = db()->query('SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();

$social_icon = [
    'linkedin'  => 'fa-linkedin',
    'twitter'   => 'fa-twitter',
    'github'    => 'fa-github',
    'facebook'  => 'fa-facebook',
    'instagram' => 'fa-instagram',
];
?>

    <!-- ===== PAGE HERO ===== -->
    <section class="ve-page-hero ve-page-hero-sm" style="background-image:url(<?= e(site_image('about_hero', 'img/bg-img/13.jpg')) ?>);">
        <div class="ve-page-hero-overlay"></div>
        <div class="container ve-page-hero-content">
            <span class="ve-section-tag">About Amex Innovations</span>
            <h1>Innovative digital solutions for modern organizations.</h1>
        </div>
    </section>

    <!-- ===== OUR STORY ===== -->
    <section class="ve-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 wow fadeInLeft" data-wow-delay="100ms">
                    <div class="ve-about-img-stack">
                        <div class="ve-about-img-1 bg-img" style="background-image:url(<?= e(site_image('about_img_1', 'img/bg-img/14.jpg')) ?>);"></div>
                        <div class="ve-about-img-2 bg-img" style="background-image:url(<?= e(site_image('about_img_2', 'img/bg-img/5.jpg')) ?>);"></div>
                        <div class="ve-about-ribbon">
                            <strong>5+</strong>
                            <span>Years in Uganda</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 wow fadeInRight" data-wow-delay="200ms">
                    <div class="ve-about-text">
                        <span class="ve-section-tag">Our Journey</span>
                        <h2>Built to help teams work smarter, not harder.</h2>
                        <p class="ve-lead">At Amex Innovations, we design systems that bring clarity, speed, and consistency to everyday operations.</p>
                        <p>Many organizations still rely on disconnected tools, manual processes, and outdated spreadsheets. We create digital platforms that connect people, data, and workflows in one place.</p>
                        <p>Everything we build starts with listening closely: <em>"What does success look like in your organization, and how can technology make that easier to achieve?"</em></p>
                        <div class="ve-about-features">
                            <div class="ve-af-item"><i class="fa fa-check"></i><span>Systems shaped around the client's actual workflow</span></div>
                            <div class="ve-af-item"><i class="fa fa-check"></i><span>Designed for how your team really works</span></div>
                            <div class="ve-af-item"><i class="fa fa-check"></i><span>Training and ongoing support included with every project</span></div>
                            <div class="ve-af-item"><i class="fa fa-check"></i><span>Clear, fair pricing with no hidden costs after sign-off</span></div>
                        </div>
                        <a href="services.php" class="ve-btn-primary mt-30">See What We Build</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MISSION / VISION / VALUES ===== -->
    <section class="ve-mvv-section" id="mission">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">What Drives Us</span>
                <h2 style="color:#fff;">Mission, Vision <span>&amp; Values</span></h2>
                <p style="color:rgba(255,255,255,0.6);">These principles keep our work practical, honest, and useful from the first conversation to long-term support.</p>
            </div>
            <div class="ve-mvv-grid">
                <div class="ve-mvv-card wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-mvv-icon"><i class="fa fa-bullseye"></i></div>
                    <h4>Our Mission</h4>
                    <p>To build practical digital tools that help organizations work faster, safer, and more reliably.</p>
                </div>
                <div class="ve-mvv-card wow fadeInUp" data-wow-delay="250ms">
                    <div class="ve-mvv-icon"><i class="fa fa-eye"></i></div>
                    <h4>Our Vision</h4>
                    <p>To make technology simple and reliable for organizations so they can focus on their work and grow with confidence.</p>
                </div>
                <div class="ve-mvv-card wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-mvv-icon"><i class="fa fa-handshake-o"></i></div>
                    <h4>How We Work</h4>
                    <p>We listen first, explain options clearly, agree on realistic timelines, charge transparently, and stay accountable after delivery with training, support, and improvements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW WE WORK — PROCESS ===== -->
    <section class="ve-section ve-process-section" style="background:#fff;">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Our Process</span>
                <h2>From Your First Call to <span>Go-Live and Beyond</span></h2>
                <p>We follow a clear process on every project so your team always knows what is happening, what comes next, and what each stage will cost.</p>
            </div>
            <div class="ve-process-grid">

                <div class="ve-process-step wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-process-num">01</div>
                    <h5>We Listen First</h5>
                    <p>No proposals or quotes until we fully understand your operations, your challenge, and what success actually looks like for you.</p>
                </div>
                <div class="ve-process-arrow"><i class="fa fa-angle-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="200ms">
                    <div class="ve-process-num">02</div>
                    <h5>We Design the Solution</h5>
                    <p>We define exactly what the system will do, how each component works, and what it will cost in clear, easy-to-understand terms.</p>
                </div>
                <div class="ve-process-arrow"><i class="fa fa-angle-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="300ms">
                    <div class="ve-process-num">03</div>
                    <h5>We Build in Phases</h5>
                    <p>We develop your system in stages, showing you working progress throughout so you can give feedback before anything is finalized.</p>
                </div>
                <div class="ve-process-arrow"><i class="fa fa-angle-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-process-num">04</div>
                    <h5>We Train Your Team</h5>
                        <p>It doesn't end when we hand over the system. Every person who will use it gets full training and support until they're completely comfortable — not just briefly shown around.</p>
                </div>
                <div class="ve-process-arrow"><i class="fa fa-angle-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="500ms">
                    <div class="ve-process-num">05</div>
                    <h5>We Stay With You</h5>
                    <p>After launch we monitor the system, provide ongoing support, and keep improving things as your business grows and your needs change.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== WHO WE SERVE ===== -->
    <section class="ve-section" style="background:var(--ve-light);">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Who We Serve</span>
                <h2>Technology for Every Kind of <span>Organization</span></h2>
                <p>We've built systems for a wide range of clients across Uganda. If you're managing people, money, stock, records, or services we've likely solved a similar challenge before.</p>
            </div>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="100ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-shopping-cart" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Retail &amp;<br>Businesses</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="150ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-hospital-o" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Hospitals &amp;<br>Clinics</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="200ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-graduation-cap" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Schools &amp;<br>Institutions</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="250ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-users" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Churches &amp;<br>NGOs</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="300ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-home" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Real Estate<br>Firms</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="350ms" style="margin-bottom:30px;">
                    <div style="text-align:center; padding:28px 16px; background:#fff; border-radius:var(--ve-radius); border:1px solid var(--ve-border); height:100%;">
                        <i class="fa fa-rocket" style="font-size:32px; color:var(--ve-gold); display:block; margin-bottom:14px;"></i>
                        <p style="font-size:14px; font-weight:700; color:var(--ve-dark); margin:0;">Startups &amp;<br>Entrepreneurs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TEAM ===== -->
    <section class="ve-section ve-team-section" id="team">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">The People Behind the Work</span>
                <h2>A Small Team That <span>Gets Things Done</span></h2>
                <p>We are a focused, hands-on team of developers, designers, and project leads based in Uganda. When you work with Amex, you talk to the people actually building your system.</p>
            </div>
            <div class="row justify-content-center">
                <?php foreach ($team as $i => $m): ?>
                <div class="col-12 col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="<?= 100 * ($i + 1) ?>ms">
                    <div class="ve-team-card">
                        <div class="ve-team-img bg-img" style="background-image:url(<?= e($m['photo']) ?>);"></div>
                        <div class="ve-team-info">
                            <h5><?= e($m['name']) ?></h5>
                            <h6><?= e($m['role']) ?></h6>
                            <span><?= e($m['bio']) ?></span>
                            <div class="ve-team-social">
                                <a href="<?= e($m['social1_url']) ?>" title="<?= e(ucfirst($m['social1_platform'])) ?>"><i class="fa <?= e($social_icon[$m['social1_platform']] ?? 'fa-link') ?>"></i></a>
                                <a href="<?= e($m['social2_url']) ?>" title="<?= e(ucfirst($m['social2_platform'])) ?>"><i class="fa <?= e($social_icon[$m['social2_platform']] ?? 'fa-link') ?>"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== COUNTERS ===== -->
    <section class="ve-counter-section">
        <div class="container">
            <div class="ve-counter-grid">
                <div class="ve-counter-item wow fadeInUp" data-wow-delay="100ms">
                    <i class="fa fa-check-square-o"></i>
                    <strong class="counter" data-count="50">0</strong><span>+</span>
                    <p>Systems Built</p>
                </div>
                <div class="ve-counter-item wow fadeInUp" data-wow-delay="200ms">
                    <i class="fa fa-users"></i>
                    <strong class="counter" data-count="30">0</strong><span>+</span>
                    <p>Clients &amp; Organizations</p>
                </div>
                <div class="ve-counter-item wow fadeInUp" data-wow-delay="300ms">
                    <i class="fa fa-building"></i>
                    <strong class="counter" data-count="8">0</strong><span>+</span>
                    <p>Sectors Served</p>
                </div>
                <div class="ve-counter-item wow fadeInUp" data-wow-delay="400ms">
                    <i class="fa fa-map-marker"></i>
                    <strong class="counter" data-count="5">0</strong><span>+ Yrs</span>
                    <p>Working in Uganda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="ve-cta-banner bg-img" style="background-image:url(<?= e(site_image('cta_banner', 'img/bg-img/6.jpg')) ?>);">
        <div class="ve-cta-overlay"></div>
        <div class="container ve-cta-content">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <h2>Facing a challenge you&#8217;re not sure <span>technology can solve?</span></h2>
                    <p>Tell us the issue in your own words. We&#8217;ll clarify what can be done, what should come first, and how to build it effectively.</p>
                </div>
                <div class="col-12 col-lg-4 text-lg-right">
                    <a href="contact.php" class="ve-btn-white">Start the Conversation</a>
                </div>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
