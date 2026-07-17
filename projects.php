<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'projects';
$page_title = 'Our Projects — Amex Innovations Ltd';
$page_description = 'See practical software, SaaS, healthcare, agriculture, real estate, education, and business systems built by Amex Innovations for organizations in Uganda.';
require __DIR__ . '/includes/header.php';

$projects = db()->query('SELECT * FROM projects WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
?>
<style>
    /* ── Filter bar ─────────────────────────────────── */
    .ai-filter-bar { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:48px; }
    .ai-filter-btn {
        padding:9px 22px; border-radius:50px; border:2px solid var(--ve-border);
        background:#fff; color:var(--ve-text); font-size:13px; font-weight:700;
        cursor:pointer; transition:all 0.25s ease; letter-spacing:.3px;
    }
    .ai-filter-btn:hover, .ai-filter-btn.active {
        background:var(--ve-gold); color:#fff; border-color:var(--ve-gold);
    }
    .ai-filter-btn[data-filter="all"].active { background:var(--ve-dark); border-color:var(--ve-dark); }

    /* ── Project grid ────────────────────────────────── */
    .ai-projects-grid {
        display:grid; grid-template-columns:repeat(3,1fr); gap:28px;
    }

    /* ── Project card ────────────────────────────────── */
    .ai-project-card {
        background:#fff; border-radius:16px; border:1px solid var(--ve-border);
        overflow:hidden; transition:all 0.28s ease; cursor:pointer;
        box-shadow:0 4px 16px rgba(7,23,48,0.06);
    }
    .ai-project-card:hover { transform:translateY(-6px); box-shadow:0 16px 40px rgba(7,23,48,0.13); }

    .ai-card-header {
        height:160px; display:flex; align-items:center; justify-content:center;
        position:relative; overflow:hidden;
    }
    .ai-card-header-overlay {
        position:absolute; inset:0; background:rgba(7,23,48,0.45); z-index:1;
    }
    .ai-card-header .ai-card-icon {
        width:72px; height:72px; border-radius:20px; display:flex;
        align-items:center; justify-content:center; font-size:30px; color:#fff;
        background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.3);
        z-index:2; position:relative;
    }
    .ai-card-body { padding:22px; }
    .ai-card-tag {
        display:inline-block; font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:1px; border-radius:4px; padding:3px 10px; margin-bottom:10px;
    }
    .ai-card-body h4 { font-size:17px; font-weight:800; color:var(--ve-dark); margin-bottom:8px; line-height:1.3; }
    .ai-card-body p { font-size:13px; color:var(--ve-text); line-height:1.7; margin-bottom:16px; }

    /* ── Modal ───────────────────────────────────────── */
    .ai-modal-backdrop {
        display:none; position:fixed; inset:0; background:rgba(7,23,48,0.72);
        z-index:10000; align-items:center; justify-content:center; padding:20px;
        backdrop-filter:blur(4px);
    }
    .ai-modal-backdrop.open { display:flex; }
    .ai-modal {
        background:#fff; border-radius:20px; max-width:760px; width:100%;
        max-height:90vh; overflow-y:auto; position:relative;
        box-shadow:0 40px 100px rgba(7,23,48,0.35);
        animation: modalIn 0.25s ease;
    }
    @keyframes modalIn { from { transform:translateY(20px); opacity:0; } to { transform:translateY(0); opacity:1; } }
    .ai-modal-header {
        height:200px; display:flex; align-items:center; justify-content:center;
        position:relative; border-radius:20px 20px 0 0;
    }
    .ai-modal-icon {
        width:90px; height:90px; border-radius:24px; display:flex;
        align-items:center; justify-content:center; font-size:38px; color:#fff;
        background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.35);
    }
    .ai-modal-close {
        position:absolute; top:16px; right:16px; width:36px; height:36px;
        border-radius:50%; background:rgba(255,255,255,0.15); border:none;
        color:#fff; font-size:18px; cursor:pointer; display:flex;
        align-items:center; justify-content:center; transition:background 0.2s;
    }
    .ai-modal-close:hover { background:rgba(255,255,255,0.3); }
    .ai-modal-body { padding:36px; }
    .ai-modal-body .ai-modal-tag { margin-bottom:8px; }
    .ai-modal-body h2 { font-size:28px; font-weight:900; color:var(--ve-dark); margin-bottom:16px; }
    .ai-modal-lead { font-size:16px; color:var(--ve-dark); font-weight:500; line-height:1.8; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid var(--ve-border); }
    .ai-modal-section { margin-bottom:24px; }
    .ai-modal-section h5 { font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:var(--ve-gold); margin-bottom:12px; }
    .ai-modal-section p { font-size:14px; color:var(--ve-text); line-height:1.8; }
    .ai-feature-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:8px; }
    .ai-feature-list li { display:flex; align-items:flex-start; gap:10px; font-size:14px; color:var(--ve-text); }
    .ai-feature-list li::before { content:"✓"; color:var(--ve-gold); font-weight:900; font-size:13px; margin-top:2px; flex-shrink:0; }
    .ai-modal-techs { display:flex; flex-wrap:wrap; gap:8px; }
    .ai-modal-tech { background:var(--ve-light); border:1px solid var(--ve-border); border-radius:6px; padding:5px 12px; font-size:12px; font-weight:700; color:var(--ve-dark); }
    .ai-modal-footer { padding:20px 36px; border-top:1px solid var(--ve-border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }

    /* ── Colour presets for card/modal headers ──────── */
    .bg-blue   { background:linear-gradient(135deg,#0D47A1,#1976D2); }
    .bg-orange { background:linear-gradient(135deg,#E64A19,#FF7043); }
    .bg-teal   { background:linear-gradient(135deg,#00695C,#00897B); }
    .bg-purple { background:linear-gradient(135deg,#512DA8,#7B1FA2); }
    .bg-dark   { background:linear-gradient(135deg,#071730,#1565C0); }
    .bg-green  { background:linear-gradient(135deg,#2E7D32,#43A047); }
    .bg-red    { background:linear-gradient(135deg,#B71C1C,#E53935); }

    /* ── Tag colours ────────────────────────────────── */
    .tag-health   { background:#e8f4fd; color:#0D47A1; }
    .tag-agri     { background:#e8f5e9; color:#2E7D32; }
    .tag-restate  { background:#fff3e0; color:#E64A19; }
    .tag-business { background:#f3e5f5; color:#512DA8; }
    .tag-church   { background:#fce4ec; color:#c62828; }
    .tag-edu      { background:#e0f7fa; color:#00695C; }
    .tag-fintech  { background:#e8eaf6; color:#3949AB; }
    .tag-suite    { background:#f1f8e9; color:#558B2F; }

    /* ── Responsive ─────────────────────────────────── */
    @media(max-width:1100px){ .ai-projects-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:700px){
        .ai-projects-grid { grid-template-columns:1fr; }
        .ai-modal-body { padding:24px; }
        .ai-modal-footer { padding:16px 24px; flex-direction:column; align-items:flex-start; }
    }
</style>

    <!-- ===== PAGE HERO ===== -->
    <section class="ve-page-hero ve-page-hero-sm" style="background-image:url(<?= e(site_image('projects_hero', 'img/bg-img/20.jpg')) ?>);">
        <div class="ve-page-hero-overlay"></div>
        <div class="container ve-page-hero-content">
            <span class="ve-section-tag">What We've Built</span>
            <h1>Proof Is in <span>the Product</span></h1>
            <nav aria-label="breadcrumb">
                <ol class="ve-breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Projects</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- ===== PROJECTS ===== -->
    <section class="ve-section" style="background:var(--ve-light);">
        <div class="container">

            <div class="ve-section-header text-center" style="margin-bottom:36px;">
                <span class="ve-section-tag">Project Highlights</span>
                <h2>Practical Systems. Real Users. <span>Measurable Impact.</span></h2>
                <p>Behind each project is an organization that needed clarity and consistency and got it.</p>
            </div>

            <!-- Filter Bar -->
            <div class="ai-filter-bar">
                <button class="ai-filter-btn active" data-filter="all">All Projects</button>
                <button class="ai-filter-btn" data-filter="health">Healthcare</button>
                <button class="ai-filter-btn" data-filter="agri">AgriTech</button>
                <button class="ai-filter-btn" data-filter="restate">Real Estate</button>
                <button class="ai-filter-btn" data-filter="business">Business</button>
                <button class="ai-filter-btn" data-filter="fintech">FinTech</button>
                <button class="ai-filter-btn" data-filter="church">Church &amp; Community</button>
                <button class="ai-filter-btn" data-filter="edu">Education</button>
            </div>

            <!-- Project Grid -->
            <div class="ai-projects-grid">
                <?php foreach ($projects as $p):
                    [$tagLabel, $tagClass] = project_category_meta($p['category']);
                ?>
                <div class="ai-project-card" data-category="<?= e($p['category']) ?>" data-project="<?= e($p['slug']) ?>">
                    <div class="ai-card-header <?= e($p['header_class']) ?>" <?= !empty($p['image']) ? 'style="background-image:url(' . e($p['image']) . ');background-size:cover;background-position:center;"' : '' ?>>
                        <?php if (!empty($p['image'])): ?><div class="ai-card-header-overlay"></div><?php endif; ?>
                        <div class="ai-card-icon"><i class="fa <?= e($p['icon']) ?>"></i></div>
                    </div>
                    <div class="ai-card-body">
                        <span class="ai-card-tag <?= e($tagClass) ?>"><?= e($tagLabel) ?></span>
                        <h4><?= e($p['title']) ?></h4>
                        <p><?= e($p['lead']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div><!-- /grid -->
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="ve-cta-banner bg-img" style="background-image:url(<?= e(site_image('cta_banner', 'img/bg-img/6.jpg')) ?>);">
        <div class="ve-cta-overlay"></div>
        <div class="container ve-cta-content">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <h2>Want a System Like One of These <span>Built for You?</span></h2>
                    <p>Every project above started with a practical conversation about a real problem. Tell us what your organization is trying to manage, improve, or automate, and we will help you map the next step.</p>
                </div>
                <div class="col-12 col-lg-4 text-lg-right">
                    <a href="contact.php" class="ve-btn-white">Start a Conversation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROJECT DETAIL MODAL ===== -->
    <div class="ai-modal-backdrop" id="projectModal">
        <div class="ai-modal" id="modalBox">
            <div class="ai-modal-header" id="modalHeader">
                <div class="ai-modal-icon" id="modalIcon"></div>
                <button class="ai-modal-close" id="modalClose">&#x2715;</button>
            </div>
            <div class="ai-modal-body">
                <span class="ai-card-tag ai-modal-tag" id="modalTag"></span>
                <h2 id="modalTitle"></h2>
                <p class="ai-modal-lead" id="modalLead"></p>
                <div class="ai-modal-section">
                    <h5>The Problem It Solves</h5>
                    <p id="modalProblem"></p>
                </div>
                <div class="ai-modal-section">
                    <h5>Key Features</h5>
                    <ul class="ai-feature-list" id="modalFeatures"></ul>
                </div>
                <div class="ai-modal-section">
                    <h5>Technology Used</h5>
                    <div class="ai-modal-techs" id="modalTechs"></div>
                </div>
            </div>
            <div class="ai-modal-footer" style="justify-content:flex-end;">
                <a href="contact.php" class="ve-btn-primary" style="font-size:14px; padding:11px 24px;">Discuss a Similar System</a>
            </div>
        </div>
    </div>

<script>
// ── Project data (rendered from the database) ──────────────────────
const projects = <?php
    $jsData = [];
    foreach ($projects as $p) {
        [$tagLabel, $tagClass] = project_category_meta($p['category']);
        $jsData[$p['slug']] = [
            'title'      => $p['title'],
            'tag'        => $tagLabel,
            'tagClass'   => $tagClass,
            'headerClass'=> $p['header_class'],
            'icon'       => $p['icon'],
            'image'      => $p['image'] ?: '',
            'lead'       => $p['lead'],
            'problem'    => $p['problem'],
            'features'   => lines_to_array($p['features']),
            'techs'      => lines_to_array($p['techs']),
        ];
    }
    echo json_encode($jsData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>;

// ── Filter logic ─────────────────────────────────────────────────
document.querySelectorAll('.ai-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.ai-filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.ai-project-card').forEach(card => {
            const cat = card.dataset.category || '';
            card.style.display = (filter === 'all' || cat.includes(filter)) ? '' : 'none';
        });
    });
});

// ── Modal logic ──────────────────────────────────────────────────
const backdrop = document.getElementById('projectModal');
const modalHeader = document.getElementById('modalHeader');
const modalIcon = document.getElementById('modalIcon');
const modalTag = document.getElementById('modalTag');
const modalTitle = document.getElementById('modalTitle');
const modalLead = document.getElementById('modalLead');
const modalProblem = document.getElementById('modalProblem');
const modalFeatures = document.getElementById('modalFeatures');
const modalTechs = document.getElementById('modalTechs');

document.querySelectorAll('.ai-project-card').forEach(card => {
    card.addEventListener('click', function() {
        const key = this.dataset.project;
        const p = projects[key];
        if (!p) return;

        modalHeader.className = 'ai-modal-header ' + p.headerClass;
        if (p.image) {
            modalHeader.style.backgroundImage = 'linear-gradient(rgba(7,23,48,0.45),rgba(7,23,48,0.45)), url(' + encodeURI(p.image) + ')';
            modalHeader.style.backgroundSize = 'cover';
            modalHeader.style.backgroundPosition = 'center';
        } else {
            modalHeader.style.backgroundImage = '';
        }
        modalIcon.innerHTML = '<i class="fa ' + p.icon + '"></i>';
        modalTag.textContent = p.tag;
        modalTag.className = 'ai-card-tag ai-modal-tag ' + p.tagClass;
        modalTitle.textContent = p.title;
        modalLead.textContent = p.lead;
        modalProblem.textContent = p.problem;

        modalFeatures.innerHTML = p.features.map(f => '<li>' + f + '</li>').join('');
        modalTechs.innerHTML = p.techs.map(t => '<span class="ai-modal-tech">' + t + '</span>').join('');

        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
});

document.getElementById('modalClose').addEventListener('click', closeModal);
backdrop.addEventListener('click', function(e) { if (e.target === backdrop) closeModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

function closeModal() {
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
