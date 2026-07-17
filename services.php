<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'services';
$page_title = 'Our Services — Amex Innovations Ltd';
$page_description = 'Explore Amex Innovations services: custom software, SaaS platforms, IoT systems, websites, dashboards, consulting, and digital agriculture tools for organizations in Uganda.';
require __DIR__ . '/includes/header.php';

$services = db()->query('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
?>

    <!-- ===== PAGE HERO ===== -->
    <section class="ve-page-hero ve-page-hero-sm" style="background-image:url(<?= e(site_image('services_hero', 'img/bg-img/20.jpg')) ?>);">
        <div class="ve-page-hero-overlay"></div>
        <div class="container ve-page-hero-content">
            <span class="ve-section-tag">What We Do</span>
            <h1>Technology Services Built Around <span>Real Work</span></h1>
            <nav aria-label="breadcrumb">
                <ol class="ve-breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Services</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- ===== SERVICES INTRO ===== -->
    <section class="ve-section" style="padding-bottom:0;">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Our Expertise</span>
                <h2>Six Areas. One Goal: <span>Useful Technology.</span></h2>
                <p>Every service starts with the same question: what is the simplest, most practical way to solve this challenge for this organization? We match the technology to the need, not the other way around.</p>
            </div>
        </div>
    </section>

    <!-- ===== SERVICE CARDS ===== -->
    <section class="ve-section ve-services-section" style="padding-top:40px;">
        <div class="container">
            <div class="ve-services-grid">
                <?php foreach ($services as $i => $s): ?>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="<?= 100 * ($i + 1) ?>ms">
                    <div class="ve-service-icon"><i class="fa <?= e($s['icon']) ?>"></i></div>
                    <h4><?= e($s['title']) ?></h4>
                    <p><?= e($s['summary']) ?></p>
                    <?php if ($s['used_in']): ?>
                        <p style="margin-top:10px; font-size:13px; color:#888; font-style:italic;"><?= e($s['used_in']) ?></p>
                    <?php endif; ?>
                    <a href="contact.php" class="ve-card-link" style="margin-top:16px; display:inline-flex;">Start a conversation <i class="fa fa-long-arrow-right"></i></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== SECTORS WE SERVE ===== -->
    <section style="background:var(--ve-light); padding:80px 0;">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Who We Serve</span>
                <h2>Sectors We Have <span>Worked In</span></h2>
                <p>We are not limited to one industry. If an organization has a process, records, people, assets, or services to manage, we can usually help. These are the sectors where we have the most hands-on experience.</p>
            </div>
            <div class="row" style="margin-top:10px;">

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="100ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-hospital-o" style="font-size:30px; color:#1565C0; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">Healthcare</p>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="150ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-leaf" style="font-size:30px; color:#E64A19; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">Agriculture</p>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="200ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-graduation-cap" style="font-size:30px; color:#1565C0; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">Education</p>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="250ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-users" style="font-size:30px; color:#E64A19; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">NGOs &amp; CBOs</p>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="300ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-flask" style="font-size:30px; color:#1565C0; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">Laboratories</p>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="350ms" style="margin-bottom:24px; text-align:center;">
                    <div style="background:#fff; border:1px solid var(--ve-border); border-radius:14px; padding:24px 16px;">
                        <i class="fa fa-university" style="font-size:30px; color:#E64A19; display:block; margin-bottom:12px;"></i>
                        <p style="font-size:13px; font-weight:700; color:var(--ve-dark); margin:0;">Government</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== HOW WE WORK ===== -->
    <section class="ve-process-section">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">How We Work</span>
                <h2>From First Call to <span>Live System</span></h2>
                <p>We keep the process clear from the first call to launch, so your team understands the scope, timeline, cost, and next step at every stage.</p>
            </div>
            <div class="ve-process-grid">

                <div class="ve-process-step wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-process-num">01</div>
                    <h5>Discovery Call</h5>
                    <p>We begin by listening. You explain your organization, the challenges you face, and your definition of success. We ask thoughtful questions in clear, simple language, with no sales pitch.</p>
                </div>

                <div class="ve-process-arrow"><i class="fa fa-long-arrow-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="250ms">
                    <div class="ve-process-num">02</div>
                    <h5>Solution Assessment</h5>
                    <p>We map out exactly what needs to be built, what technology fits your context and budget, and how long it will realistically take. You get a clear scope and a fair estimate — before any work begins.</p>
                </div>

                <div class="ve-process-arrow"><i class="fa fa-long-arrow-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-process-num">03</div>
                    <h5>Build &amp; Iterate</h5>
                    <p>We develop in stages and show you working progress at each milestone. You test, give feedback, and we improve. By the time we reach launch, the system is already familiar to your team.</p>
                </div>

                <div class="ve-process-arrow"><i class="fa fa-long-arrow-right"></i></div>

                <div class="ve-process-step wow fadeInUp" data-wow-delay="550ms">
                    <div class="ve-process-num">04</div>
                    <h5>Deploy &amp; Support</h5>
                    <p>We deploy the system, train your team on how to use it, and stay available for issues, questions, and improvements. Delivery is not the end of our relationship — it's the beginning of it.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section class="ve-section ve-faq-section">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-12 col-lg-5 wow fadeInLeft" data-wow-delay="100ms">
                    <span class="ve-section-tag">Common Questions</span>
                    <h2>Questions We Get <span>Asked Often</span></h2>
                    <p>If your question isn't here, just <a href="contact.php" style="color:var(--ve-gold); font-weight:600;">reach out directly</a>. We respond to every serious inquiry within one business day.</p>
                    <a href="contact.php" class="ve-btn-primary mt-30">Talk to Us</a>
                </div>
                <div class="col-12 col-lg-7 wow fadeInRight" data-wow-delay="200ms">
                    <div class="ve-faq-list">

                        <div class="ve-faq-item open m-3">
                            <div class="ve-faq-q m-2">
                                <span>How long does it take to build a custom system?</span>
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="ve-faq-a m-3">It depends entirely on the scope. A simple management system or website typically takes 2–4 weeks. A full SaaS product or IoT solution can take 3–6 months. We give you a realistic timeline during the assessment phase — and we stick to it.</div>
                        </div>

                        <div class="ve-faq-item " style="display: none;">
                            <div class="ve-faq-q">
                                <span>Do you work with organizations that have a limited budget?</span>
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="ve-faq-a">Yes. We have worked with small NGOs, community organizations, and early-stage businesses. We are honest about what's achievable at different budget levels and will help you prioritize the features that matter most. We would rather build you a smaller system that works than a large one that costs too much to maintain.</div>
                        </div>

                        <div class="ve-faq-item m-2">
                            <div class="ve-faq-q m-3">
                                <span>What happens after the software is delivered?</span>
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="ve-faq-a m-3">We train your team, document how the system works, and offer ongoing support and maintenance. Most of our clients come back to us when they want to expand the system or add new features. We prefer long-term relationships over one-time projects.</div>
                        </div>

                        <div class="ve-faq-item m-2">
                            <div class="ve-faq-q m-3">
                                <span>Can you build on top of systems we already have?</span>
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="ve-faq-a m-3">Often yes. We can integrate with existing databases, add features to systems already in use, or migrate your data from older systems into something better. We assess what you already have before suggesting you replace it.</div>
                        </div>

                        <div class="ve-faq-item m-2">
                            <div class="ve-faq-q m-3">
                                <span>Do you only work in Uganda?</span>
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="ve-faq-a m-3">We are based in Mbarara, Uganda, and most of our clients are in Uganda and East Africa. However, we work remotely with organizations across the continent. Our focus is on African challenges and African contexts — wherever they are located.</div>
                        </div>

                    </div>
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
                    <h2>Not Sure Which Service <span>You Need?</span></h2>
                    <p>That is fine. Most clients first come to us with a challenge, not a service name. Tell us what is not working, and we will help you choose the right direction.</p>
                </div>
                <div class="col-12 col-lg-4 text-lg-right">
                    <a href="contact.php" class="ve-btn-white">Let's talk</a>
                </div>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
