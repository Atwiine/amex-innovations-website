<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'home';
$page_title = 'Amex Innovations Ltd - Practical Software for Growing Organizations';
$page_description = 'Amex Innovations Ltd builds practical software, management systems, SaaS platforms, IoT tools, and digital solutions for businesses and organizations across Uganda.';
require __DIR__ . '/includes/header.php';

$partners = db()->query('SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
?>

    <!-- ===== HERO ===== -->
    <section class="ve-hero">
        <div class="ve-hero-left">
            <span class="ve-hero-badge">Based in Mbarara, Uganda &nbsp;·&nbsp; Building practical technology for African organizations</span>
            <h1>Software That Helps Your Organization <span class="ve-highlight">Work Better.</span></h1>
            <p>We build custom systems, SaaS platforms, websites, IoT tools, and digital workflows that make daily operations easier to manage. If your team is losing time to paper records, scattered spreadsheets, manual reports, or disconnected tools, we turn that work into a clear system your people can actually use.</p>
            <div class="ve-hero-btns">
                <a href="services.php" class="ve-btn-primary">See What We Build</a>
                <a href="contact.php" class="ve-btn-ghost">Tell Us Your Challenge</a>
            </div>
            <div class="ve-hero-stats">
                <div class="ve-stat">
                    <strong>50+</strong>
                    <span>Systems Delivered</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>8+</strong>
                    <span>Industries Served</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>100%</strong>
                    <span>Built Around Your Work</span>
                </div>
            </div>
        </div>
        <div class="ve-hero-right">
            <div class="ve-hero-img-main bg-img" style="background-image:url(<?= e(site_image('home_hero_main', 'img/bg-img/1.jpg')) ?>);"></div>
            <!-- <div class="ve-hero-img-accent bg-img" style="background-image:url(<?= e(site_image('home_hero_accent', 'img/bg-img/3.jpg')) ?>);"></div> -->
            <div class="ve-float-card">
                <i class="fa fa-check-circle" style="color:#E64A19;"></i>
                <div>
                    <strong>Built for Real Work</strong>
                    <span>Simple to Use</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TRUST BAR ===== -->
    <div class="ve-trust-bar">
        <div class="ve-trust-inner">
            <span><i class="fa fa-code"></i> Custom Software — Built From Scratch</span>
            <span><i class="fa fa-cogs"></i> Business Automation — Less Manual Work</span>
            <span><i class="fa fa-globe"></i> Websites &amp; Online Stores</span>
            <span><i class="fa fa-database"></i> Management Systems for Every Organization</span>
            <span><i class="fa fa-line-chart"></i> Live Dashboards &amp; Automated Reports</span>
            <span><i class="fa fa-map-marker"></i> Mbarara, Uganda - Serving Uganda and East Africa</span>
            <span><i class="fa fa-mobile"></i> Works on Any Device, Anywhere</span>
            <span><i class="fa fa-code"></i> Custom Software — Built From Scratch</span>
            <span><i class="fa fa-cogs"></i> Business Automation — Less Manual Work</span>
            <span><i class="fa fa-globe"></i> Websites &amp; Online Stores</span>
        </div>
    </div>

    <!-- ===== SERVICES ===== -->
    <section class="ve-section ve-services-section">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">What We Build</span>
                <h2>Digital Solutions for <span>Everyday Business Needs</span></h2>
                <p>We do not push one-size-fits-all software. We learn how your organization works, identify what slows your team down, and build a system that fits your operations, budget, and growth plans.</p>
            </div>
            <div class="ve-services-grid">

                <div class="ve-service-card wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-service-icon"><i class="fa fa-code"></i></div>
                    <h4>Custom Software Built Around You</h4>
                    <p>When existing apps cannot support your workflow, we build the system from the ground up. Your forms, approvals, reports, users, and data are designed around the way your team already works.</p>
                </div>

                <div class="ve-service-card wow fadeInUp" data-wow-delay="200ms">
                    <div class="ve-service-icon"><i class="fa fa-cogs"></i></div>
                    <h4>Business Automation That Saves Time</h4>
                    <p>When everyday business needs create repeated work and slow down your team, valuable time is lost. We streamline those workflows so your team works faster, reduces errors, and spends more time serving customers and growing the business.</p>
                </div>

                <div class="ve-service-card wow fadeInUp" data-wow-delay="300ms">
                    <div class="ve-service-icon"><i class="fa fa-globe"></i></div>
                    <h4>Websites and Online Platforms</h4>
                    <p>We build professional websites, portals, and online stores that load fast, work well on phones, and make it easy for people to understand your services, contact you, or place an order.</p>
                </div>

                <div class="ve-service-card wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-service-icon"><i class="fa fa-th-large"></i></div>
                    <h4>Management Systems for Your Sector</h4>
                    <p>Hospitals, schools, churches, farms, shops, NGOs, real estate firms, and institutions all manage different information. We build systems that organize the records, tasks, and reports each team needs most.</p>
                </div>

                <div class="ve-service-card wow fadeInUp" data-wow-delay="500ms">
                    <div class="ve-service-icon"><i class="fa fa-line-chart"></i></div>
                    <h4>Dashboards and Reports You Can Trust</h4>
                    <p>Instead of waiting for month-end reports, your team can see sales, stock, assets, clients, tasks, or service activity as it happens. Clear dashboards help you make decisions with confidence.</p>
                </div>

                <div class="ve-service-card wow fadeInUp" data-wow-delay="600ms">
                    <div class="ve-service-icon"><i class="fa fa-refresh"></i></div>
                    <h4>Not Sure Where to Start?</h4>
                    <p>Many clients come to us with unique business operational challenges. We help clarify what needs to improve, what should be built first, and what a realistic budget and timeline should look like.</p>
                    <a href="contact.php" class="ve-card-link">Book a free session <i class="fa fa-long-arrow-right"></i></a>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== WHY US ===== -->
    <section class="ve-section ve-whyus-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-5">
                    <div class="ve-whyus-img-wrap wow fadeInLeft" data-wow-delay="100ms">
                        <div class="ve-whyus-img-main bg-img" style="background-image:url(<?= e(site_image('home_whyus', 'img/bg-img/5.jpg')) ?>);"></div>
                        <div class="ve-whyus-badge">
                            <strong>5+</strong>
                            <span>Years Building in Uganda</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-7 wow fadeInRight" data-wow-delay="200ms">
                    <div class="ve-whyus-content">
                        <span class="ve-section-tag">Why Amex Innovations Ltd</span>
                        <h2>We Build Technology That Fits <span>Your Reality</span></h2>
                        <p>There are plenty of software companies. Amex Innovations Ltd is different because we build close to the challenges our clients face every day.
                             We understand local market constraints, infrastructure capabilities, capacity building requirements, and proven operational approaches that drive success across Ugandan organizations.</p>
                        <div class="ve-checklist">
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div>
                                    <strong>We Start by Understanding the Work</strong>
                                    <p>We take time to learn your workflows, challenges, and objectives before recommending solutions. This deep understanding ensures our systems integrate seamlessly with your team's daily operations.</p>
                                </div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div>
                                    <strong>Solutions Built for Local Success</strong>
                                    <p>We design with deep knowledge of Uganda's business landscape, including mobile money systems, connectivity realities, and cost-effective implementation. Your success is our only measure.</p>
                                </div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div>
                                    <strong>Long-Term Partnership &amp; Support</strong>
                                    <p>We're committed to your success beyond launch. We provide comprehensive training, responsive technical support, and continuous platform improvements as your organization evolves and scales.</p>
                                </div>
                            </div>
                        </div>
                        <a href="about.php" class="ve-btn-primary mt-30">Learn More About Us</a>
                    </div>
                </div>
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
                    <strong class="counter" data-count="5">0</strong><span>+</span>
                    <p>Years of Excellence in Service</p>
                </div>
            </div>
        </div>
    </section>

    <?php if ($partners): ?>
    <!-- ===== PARTNERS ===== -->
    <section class="ve-section" style="background:var(--ve-light); padding:70px 0;">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Who We've Worked With</span>
                <h2>Our <span>Partners</span></h2>
            </div>
            <div style="display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:36px;">
                <?php foreach ($partners as $p):
                    $inner = $p['logo']
                        ? '<img src="' . e($p['logo']) . '" alt="' . e($p['name']) . '" style="max-height:56px; max-width:160px; object-fit:contain; filter:grayscale(100%); opacity:.75; transition:filter .2s, opacity .2s;" onmouseover="this.style.filter=\'none\';this.style.opacity=1;" onmouseout="this.style.filter=\'grayscale(100%)\';this.style.opacity=.75;">'
                        : '<span style="font-size:16px; font-weight:700; color:var(--ve-dark);">' . e($p['name']) . '</span>';
                ?>
                    <?php if ($p['website_url']): ?>
                        <a href="<?= e($p['website_url']) ?>" target="_blank" rel="noopener"><?= $inner ?></a>
                    <?php else: ?>
                        <?= $inner ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== CTA BANNER ===== -->
    <section class="ve-cta-banner bg-img" style="background-image:url(<?= e(site_image('cta_banner', 'img/bg-img/6.jpg')) ?>);">
        <div class="ve-cta-overlay"></div>
        <div class="container ve-cta-content">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <h2>Ready to Transform How Your Business <span>Operates?</span></h2>
                    <p>Whether it's chaotic record management, bottlenecked approvals, missing insights, inventory nightmares, or operations hanging on one person's shoulders we've solved it before. Let us show you what's truly possible and exactly what it takes to get there.</p>
                </div>
                <div class="col-12 col-lg-4 text-lg-right">
                    <a href="contact.php" class="ve-btn-white">Let's Talk - It's Free</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== NEWSLETTER ===== -->
    <section class="ve-newsletter-section">
        <div class="container">
            <div class="ve-newsletter-wrap">
                <div class="ve-nl-left">
                    <i class="fa fa-envelope-o"></i>
                    <div>
                        <h3>Clear, practical tech insights for your business</h3>
                        <p>We cover the real challenges Ugandan businesses face and show how technology can solve them. No jargon, no fluff — just useful guidance delivered twice a month.</p>
                    </div>
                </div>
                <div class="ve-nl-right">
                    <form class="ve-nl-form" action="#" method="post">
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
