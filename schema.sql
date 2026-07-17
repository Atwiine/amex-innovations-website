-- Amex Innovations Ltd — site database
-- Import via phpMyAdmin / mysql CLI. Safe to re-run: uses CREATE TABLE IF NOT EXISTS
-- and only seeds rows when tables are empty (see final INSERT guards below).

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS amex_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE amex_db;

-- ─────────────────────────────────────────────────────────────
-- admin_users
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP    NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- audit_log — records who did what in the admin panel
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS audit_log (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id       INT UNSIGNED NULL,
    admin_username VARCHAR(50)  NOT NULL,
    action         VARCHAR(100) NOT NULL,
    details        VARCHAR(255) NOT NULL DEFAULT '',
    ip_address     VARCHAR(45)  NOT NULL DEFAULT '',
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- services
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS services (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(150) NOT NULL,
    slug       VARCHAR(160) NOT NULL UNIQUE,
    icon       VARCHAR(60)  NOT NULL DEFAULT 'fa-cog',
    summary    TEXT         NOT NULL,
    used_in    VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT          NOT NULL DEFAULT 0,
    is_active  TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- projects
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS projects (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(150) NOT NULL,
    slug         VARCHAR(160) NOT NULL UNIQUE,
    category     VARCHAR(30)  NOT NULL DEFAULT 'business',
    icon         VARCHAR(60)  NOT NULL DEFAULT 'fa-cog',
    header_class VARCHAR(30)  NOT NULL DEFAULT 'bg-blue',
    image        VARCHAR(255) NULL,
    lead         TEXT         NOT NULL,
    problem      TEXT         NOT NULL,
    features     TEXT         NOT NULL,
    techs        TEXT         NOT NULL,
    project_url  VARCHAR(255) NULL,
    status_label VARCHAR(150) NOT NULL DEFAULT 'Live',
    status_type  ENUM('live','deployed') NOT NULL DEFAULT 'live',
    sort_order   INT          NOT NULL DEFAULT 0,
    is_active    TINYINT(1)   NOT NULL DEFAULT 1,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Existing installs created before the `image` column existed:
ALTER TABLE projects ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL AFTER header_class;

-- ─────────────────────────────────────────────────────────────
-- team_members
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS team_members (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(150) NOT NULL,
    role             VARCHAR(150) NOT NULL,
    bio              TEXT         NOT NULL,
    photo            VARCHAR(255) NOT NULL,
    social1_platform VARCHAR(20)  NOT NULL DEFAULT 'linkedin',
    social1_url      VARCHAR(255) NOT NULL DEFAULT '#',
    social2_platform VARCHAR(20)  NOT NULL DEFAULT 'twitter',
    social2_url      VARCHAR(255) NOT NULL DEFAULT '#',
    sort_order       INT          NOT NULL DEFAULT 0,
    is_active        TINYINT(1)   NOT NULL DEFAULT 1,
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- partners — organizations/clients showcased on the public site
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS partners (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    logo        VARCHAR(255) NULL,
    website_url VARCHAR(255) NULL,
    sort_order  INT          NOT NULL DEFAULT 0,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- contact_messages
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS contact_messages (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    phone      VARCHAR(50)  NOT NULL DEFAULT '',
    service    VARCHAR(150) NOT NULL DEFAULT '',
    message    TEXT         NOT NULL,
    status     ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- site_settings
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key   VARCHAR(100) PRIMARY KEY,
    setting_value TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES
    ('company_email',   'amexinnovationslt@gmail.com'),
    ('company_phone',   '+256 705 104052'),
    ('company_phone_2', '+256 772 068696'),
    ('company_address', 'Mbarara City, Uganda'),
    ('facebook_url',    '#'),
    ('twitter_url',     '#'),
    ('linkedin_url',    '#'),
    ('instagram_url',   '#'),
    ('site_domain',       'https://amexinnovations.com'),
    ('ga_measurement_id', '');

-- ─────────────────────────────────────────────────────────────
-- site_images — editable background/hero images across public pages
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS site_images (
    image_key  VARCHAR(60)  PRIMARY KEY,
    label      VARCHAR(150) NOT NULL,
    page_group VARCHAR(60)  NOT NULL DEFAULT 'general',
    path       VARCHAR(255) NOT NULL,
    sort_order INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO site_images (image_key, label, page_group, path, sort_order) VALUES
    ('site_logo',      'Site Logo (header & footer)',              'Site-Wide', 'img/core-img/logo.png', 1),
    ('cta_banner',     'Call-to-Action Banner Background',         'Site-Wide', 'img/bg-img/6.jpg', 2),
    ('og_image',       'Social Share Image (shown when links are shared)', 'Site-Wide', 'img/core-img/logo.png', 3),
    ('home_hero_main',   'Homepage Hero — Main Image',              'Home', 'img/bg-img/1.jpg', 1),
    ('home_hero_accent', 'Homepage Hero — Accent Image',            'Home', 'img/bg-img/3.jpg', 2),
    ('home_whyus',       'Homepage — Why Us Section Image',         'Home', 'img/bg-img/5.jpg', 3),
    ('insight_1',        'Homepage — Insight Card 1',               'Home', 'img/bg-img/10.jpg', 4),
    ('insight_2',        'Homepage — Insight Card 2',               'Home', 'img/bg-img/11.jpg', 5),
    ('insight_3',        'Homepage — Insight Card 3',               'Home', 'img/bg-img/12.jpg', 6),
    ('about_hero',       'About Page — Hero Background',            'About', 'img/bg-img/13.jpg', 1),
    ('about_img_1',      'About Page — Story Image 1',              'About', 'img/bg-img/14.jpg', 2),
    ('about_img_2',      'About Page — Story Image 2',              'About', 'img/bg-img/5.jpg', 3),
    ('services_hero',    'Services Page — Hero Background',         'Services', 'img/bg-img/20.jpg', 1),
    ('projects_hero',    'Projects Page — Hero Background',         'Projects', 'img/bg-img/20.jpg', 1);

-- ─────────────────────────────────────────────────────────────
-- Seed: services (current site copy, so nothing is lost going dynamic)
-- ─────────────────────────────────────────────────────────────
INSERT IGNORE INTO services (id, title, slug, icon, summary, used_in, sort_order) VALUES
(1, 'SaaS Product Development', 'saas-product-development', 'fa-cloud',
 'We design and build cloud-based software that teams can use from anywhere without managing servers or installations. Our SaaS platforms are built to grow from one organization to many users, locations, or branches without starting over.',
 'Used in: healthcare, agriculture, education, government institutions', 1),
(2, 'IoT & Embedded Systems', 'iot-embedded-systems', 'fa-wifi',
 'We build connected systems that combine devices, sensors, firmware, connectivity, and dashboards. Whether you need to monitor farm conditions, lab equipment, temperatures, or remote assets, we make the data easy to capture and act on.',
 'Used in: smart farming, laboratory monitoring, asset tracking, environmental sensing', 2),
(3, 'Custom Software Solutions', 'custom-software-solutions', 'fa-code',
 'When off-the-shelf tools do not fit your workflow, we build something that does. Your forms, reports, approval steps, users, and data structure are designed around the way your team works.',
 'Used in: hospitals, NGOs, schools, businesses, churches, government agencies', 3),
(4, 'Research & Innovation', 'research-innovation', 'fa-search',
 'Not every idea should become a full product immediately. We help you prototype, test, and validate before committing a large budget, so you can make decisions with evidence instead of guesswork.',
 'Used in: emerging tech exploration, grant-funded projects, academic partnerships, pilot programs', 4),
(5, 'Tech Consulting', 'tech-consulting', 'fa-comments',
 'Before you build, you need clarity on what to build, what it will cost, and whether your current setup can support it. We help with technology planning, system reviews, vendor assessment, and practical digital transformation roadmaps.',
 'Used in: organizations starting digital transformation, teams reviewing existing systems, funders evaluating tech proposals', 5),
(6, 'Digital Agriculture Tools', 'digital-agriculture-tools', 'fa-leaf',
 'We build digital tools that help farmers, cooperatives, and agribusinesses keep records, access market information, receive useful growing guidance, and connect with buyers. FarmEase is our flagship agriculture platform for smallholder farmers.',
 'Used in: smallholder farming, agricultural extension services, cooperatives, agribusinesses', 6);

-- ─────────────────────────────────────────────────────────────
-- Seed: team_members
-- ─────────────────────────────────────────────────────────────
INSERT IGNORE INTO team_members (id, name, role, bio, photo, social1_platform, social1_url, social2_platform, social2_url, sort_order) VALUES
(1, 'Harman Atwiine', 'Co-Founder & CEO',
 'Leads strategy, client relationships, and ensures every project delivers real value — not just a finished product.',
 'img/team/harman.jpg', 'linkedin', '#', 'twitter', '#', 1),
(2, 'Mark Matsiko', 'Co-Founder & Engineer',
 'Architects the technical structure of every system we build. If it runs well, it''s because of the care that goes into this role.',
 'img/team/mark.png', 'linkedin', '#', 'github', '#', 2),
(3, 'Prossy Akatukunda', 'Marketing & Sales',
 'Drives growth and client outreach — the voice that connects organizations to the right solution before a single line of code is written.',
 'img/team/prossy.jpg', 'linkedin', '#', 'twitter', '#', 3),
(4, 'Brighton Kato', 'Operations & Partnerships',
 'Keeps every project on schedule and every partner relationship strong — the person making sure nothing falls through the cracks.',
 'img/team/kato.jpg', 'linkedin', '#', 'twitter', '#', 4);

-- ─────────────────────────────────────────────────────────────
-- Seed: projects
-- ─────────────────────────────────────────────────────────────
INSERT IGNORE INTO projects (id, title, slug, category, icon, header_class, lead, problem, features, techs, status_label, status_type, sort_order, is_active) VALUES
(1, 'FarmEase', 'farmease', 'agri', 'fa-leaf', 'bg-green',
 'FarmEase is a digital agriculture platform built for smallholder farmers across Uganda — giving them practical tools to manage crops, access markets, and grow income without needing expensive equipment or a fast internet connection.',
 'Most smallholder farmers in Uganda run their operations entirely from memory. No records, no price benchmarks, no way to plan the next season based on what worked last time. When buyers come, farmers have no data to negotiate with. When banks ask for records for credit, farmers have nothing to show. FarmEase fixes that.',
 'Digital crop and season record-keeping\nMarket price information and buyer connections\nPersonalized growing advice by crop type and season\nExpense and income tracking per harvest\nMobile-friendly and low-bandwidth optimized\nMulti-farmer support for cooperatives and extension workers',
 'PHP\nMySQL\nBootstrap\nJavaScript\nMobile-first Design',
 'Live & Deployed', 'live', 1, 1),

(2, 'ICT Inventory System (IIS)', 'iis', 'health', 'fa-database', 'bg-blue',
 'IIS is a hospital ICT asset management system deployed with Uganda''s Ministry of Health — tracking every computer, printer, router, and ICT device across hospital departments with full lifecycle visibility.',
 'Hospitals across Uganda receive donated and purchased ICT equipment regularly. Without a proper tracking system, equipment disappears between departments, maintenance is missed, and administrators have no clear picture of what they own, where it is, or when it was last serviced. IIS solves the visibility problem completely.',
 'Full ICT equipment registry with department assignment\nEquipment request and approval workflow\nMaintenance logs and service scheduling\nStores management for equipment movement\nPDF reports for audits and accountability\nAdmin panel with role-based access control\nMulti-hospital deployment support',
 'PHP\nMySQL\nBootstrap\nFPDF (PDF generation)\nPHPMailer',
 'Live — Ministry of Health, Uganda', 'live', 2, 1),

(3, 'LabTrack', 'labtrack', 'health', 'fa-flask', 'bg-dark',
 'LabTrack is a SaaS platform purpose-built for laboratories — helping them track equipment, schedule preventive maintenance, and monitor usage analytics so nothing breaks down unnoticed and nothing goes missing.',
 'Laboratories lose thousands of dollars each year to equipment that breaks down without warning, goes out of calibration without anyone noticing, or simply disappears from inventory with no record of where it went. Manual logbooks and spreadsheets don''t cut it when you''re managing sensitive, expensive instruments. LabTrack replaces that chaos with a clean, always-on digital system.',
 'Equipment registry with full specifications and location tracking\nPreventive maintenance scheduling and reminders\nService log and repair history per equipment\nUsage analytics and reporting\nMulti-user access with role permissions\nEmail notifications via PHPMailer\nAccount status management',
 'PHP\nMySQL\nBootstrap\nPHPMailer\nJavaScript',
 'Live & Deployed', 'live', 3, 1),

(4, 'NestUG', 'nestug', 'restate', 'fa-home', 'bg-orange',
 'NestUG is Uganda''s property discovery platform — connecting people looking for rentals and properties for sale with verified owners across Mbarara, Bushenyi, Ntungamo, and beyond.',
 'Finding a house or rental space in Ugandan towns still happens largely through word of mouth, notice boards, and middlemen who charge high fees. There was no clean, reliable online platform where property owners could list and tenants could search with confidence. NestUG fills that gap for Western Uganda.',
 'Property listings for rentals and sale with photos and details\nOwner portal: register, list, and manage properties\nLocation-based search across multiple districts\nVerified listing system for trust and safety\nMobile-friendly and fast-loading\nContact owner directly from listing\nAdmin dashboard for platform management',
 'HTML5\nCSS3\nJavaScript\nPHP\nMySQL\nGoogle Fonts\nFont Awesome',
 'Live', 'live', 4, 1),

(5, 'CalmCare', 'calmcare', 'health', 'fa-heartbeat', 'bg-teal',
 'CalmCare is a patient care management platform designed for mental health and wellness services — giving care providers and patients a shared digital space to coordinate care, track progress, and communicate securely.',
 'Mental health services in Uganda often struggle with disorganized patient records, missed appointments, and no structured way to monitor patient progress over time. Care providers work from paper files that get lost and have no overview of all their active cases. CalmCare brings structure to that environment.',
 'Patient registration and secure profile management\nAppointment booking and calendar management\nReal-time messaging between providers and patients\nCare dashboard with patient overview\nAppointment history and treatment tracking\nRole-based access: admin, provider, patient\nNotifications and reminders',
 'PHP\nMySQL\nBootstrap\nJavaScript\nChart.js\nDataTables',
 'Deployed', 'deployed', 5, 1),

(6, 'Bejoojo — LoanTrack', 'bejoojo', 'fintech', 'fa-money', 'bg-purple',
 'Bejoojo is a full loan management system — built for lending institutions and microfinance organizations to manage clients, track loans, calculate repayment schedules, and maintain a clean financial audit trail with two-factor authentication security.',
 'Many small lending institutions in Uganda manage loans in exercise books, Excel sheets, and WhatsApp messages. This creates errors in interest calculations, missed payment follow-ups, difficulty spotting defaulters early, and zero audit trail when disputes arise. Bejoojo digitizes the entire lending operation.',
 'Client registration with document management\nLoan creation, disbursement, and interest calculation\nAutomated repayment schedule generation\nPayment recording and receipt tracking\nPayment reminder system for overdue loans\nCash opening and daily float management\nAudit logs for every transaction and action\nTwo-factor authentication (2FA) for security\nRole and permission management',
 'PHP\nMySQL\nBootstrap\nPHPMailer\n2FA (TOTP)\nJavaScript',
 'Live', 'live', 6, 1),

(7, 'BSU Resource Management System', 'bsu', 'edu', 'fa-university', 'bg-teal',
 'A comprehensive multi-module resource management and tracking platform built for an institution — covering ICT equipment, fleet vehicles, furniture, and departmental stores inventory under one unified system.',
 'Large institutions manage dozens of asset types across multiple departments and locations. Without a central system, assets go missing, maintenance is skipped, and departments have no visibility into what''s available or where it is. This system gives administrators full control and accountability across all asset categories.',
 'ICT equipment registry and department assignment\nFleet vehicle management and usage logging\nFurniture inventory by department and location\nStores management with request and approval workflow\nPDF report generation for audits\nMulti-department user access with role control\nEquipment maintenance scheduling and logging',
 'PHP\nMySQL\nBootstrap\nFPDF\nPHPMailer',
 'Deployed', 'deployed', 7, 0),

(8, 'Daystar Cathedral Mbarara', 'daystar', 'church', 'fa-sun-o', 'bg-red',
 'A full dynamic website for Daystar Cathedral Mbarara — a vibrant Christian church serving Mbarara and its surrounding communities — with content management, branch information, blog, and a contact system.',
 'The church needed a professional online presence that the team could manage without depending on a developer for every update. They also needed a way to share sermons, event announcements, branch locations, and news with their growing congregation online.',
 'Dynamic about page with mission, vision, and leadership\nBlog and sermon posts with admin management\nMultiple branch locations with details\nEvents calendar and announcements\nContact and inquiry form with PHP mail handling\nMobile-responsive design\nFull CMS for non-technical admin updates',
 'PHP\nMySQL\nBootstrap 5\nJavaScript\nOwl Carousel\nGoogle Fonts',
 'Live — Daystar Cathedral Mbarara', 'live', 8, 1),

(9, 'Fero Motor Spare', 'fero', 'business', 'fa-cog', 'bg-orange',
 'A full-stack e-commerce and business management platform for a motor spare parts shop — allowing customers to browse, order, and buy parts online while giving the admin team full control over inventory, orders, and product management.',
 'The spare parts business was running entirely in-person with no way for customers to check availability, place orders, or view pricing online. The admin team managed everything manually with no digital record of what stock was available or what orders were pending. The platform changed all of that.',
 'Product catalog with categories, search, and filtering\nShopping cart and checkout system\nOrder management and order status tracking\nAdmin panel for product and inventory management\nCustomer accounts and order history\nAJAX-powered real-time interactions\nSupplier and stock management',
 'PHP\nMySQL\nBootstrap\nJavaScript\nAJAX\njQuery',
 'Live — Fero Motor Spare', 'live', 9, 1),

(10, 'Jim Dental Clinic', 'jimdental', 'health', 'fa-medkit', 'bg-blue',
 'A patient management system built specifically for a dental clinic — organizing patient records, appointment scheduling, treatment history, and billing so the clinic runs cleanly without paper files and confusion.',
 'The clinic was managing patient records across paper files and notebooks. Scheduling appointments meant flipping through pages, finding a patient''s history required searching multiple folders, and billing was handled separately from the clinical records. Everything was slowing down the practice.',
 'Patient registration and digital record management\nAppointment booking and scheduling calendar\nTreatment history and dental procedure logging\nBilling and payment tracking per patient\nRole-based access: admin and staff\nPatient search and record lookup\nSecure login with session management',
 'PHP\nMySQL\nBootstrap\nJavaScript',
 'Live — Jim Dental Clinic', 'live', 10, 1),

(11, 'Lira Medical Clinic', 'lira', 'health', 'fa-hospital-o', 'bg-teal',
 'A complete clinic management platform for Lira Medical Clinic — handling patient registration, diagnosis records, and a dedicated billing system with a workspace for managing pending items, invoices, and payments.',
 'The clinic needed a system that could handle both the clinical side (patient records, diagnoses) and the financial side (billing, payment tracking) in one place — without two separate systems that don''t talk to each other.',
 'Patient registration and profile management\nDiagnosis and treatment recording\nBilling dashboard with full financial overview\nPending billing items workspace\nInvoice generation and payment tracking\nAdmin dashboard with clinic-wide analytics\nSecure multi-user access',
 'PHP\nMySQL\nBootstrap\nJavaScript',
 'Live — Lira Medical Clinic', 'live', 11, 1),

(12, 'Mbarara Referral Hospital', 'mbarara', 'health', 'fa-plus-square', 'bg-dark',
 'A hospital administration and patient management system designed for a major referral hospital in Western Uganda — built to handle the complexity and patient volumes of a large, multi-department institution.',
 'Referral hospitals manage far more patients, departments, and staff than smaller clinics. The manual systems in place couldn''t keep up with the volume, creating gaps in patient records, delays in service, and difficulty in tracking patient movement across departments.',
 'Patient registration and department assignment\nAdmission, discharge, and transfer tracking\nDepartmental patient record management\nAdmin dashboard with hospital-wide overview\nStaff and role management\nPatient history and visit records\nSecure access control',
 'PHP\nMySQL\nBootstrap\nJavaScript\nAdmin Panel',
 'Deployed — Mbarara Referral Hospital', 'deployed', 12, 0),

(13, 'Pharmacy Management System', 'nispa', 'health', 'fa-medkit', 'bg-green',
 'A secure pharmacy management system for NISPA Health Pharmacy — controlling staff access, managing drug inventory, tracking prescriptions, and giving the team a clean dashboard for daily pharmacy operations.',
 'Pharmacies handle sensitive medications and need strict access control, accurate inventory, and a reliable record of every prescription dispensed. Running this on paper creates errors, enables losses, and makes audits nearly impossible. The system brought proper structure to the entire pharmacy workflow.',
 'Drug inventory management with stock level tracking\nPrescription recording and dispensing log\nPatient record linkage for prescription history\nSecure login with role-based access control\nDaily operations dashboard\nLow stock alerts\nStaff activity monitoring',
 'PHP\nMySQL\nBootstrap\nJavaScript\nSecure Authentication',
 'Live — NISPA Health Pharmacy', 'live', 13, 1),

(14, 'ELN — Entrepreneurial Leadership Network', 'ean', 'edu', 'fa-users', 'bg-purple',
 'A multi-page website and member platform for the Entrepreneurial Alumni Network — supporting three types of members (Individuals, Businesses, and Investors) with a searchable directory, dynamic registration, and a community blog.',
 'The network had no central platform to connect alumni, showcase member businesses, or facilitate introductions between entrepreneurs and investors. Everything happened informally — through WhatsApp groups and word of mouth. A proper platform gave the community structure, visibility, and reach.',
 'Three-type member registration: Individual, Business, Investor\nDynamic registration forms that adapt by member type\nAJAX-powered searchable member directory with filters\nBusiness and investor profile pages\nCommunity blog with admin management\nContact and inquiry system\nMobile-responsive, Bootstrap 5 design',
 'Bootstrap 5\nPHP\nMySQL\nAJAX\nVanilla JavaScript\nGoogle Fonts',
 'Live — Entrepreneurial Alumni Network', 'live', 14, 1),

(15, 'Amex Suite', 'amexsuite', 'suite', 'fa-th-large', 'bg-blue',
 'Amex Suite is Amex Innovations'' own integrated management platform — bringing together the IIS (ICT Inventory), LabTrack, and Biomedical equipment tracking systems under one unified admin interface for institutions that manage multiple asset types.',
 'Organizations like hospitals and universities often need to manage several different categories of assets — ICT equipment, laboratory instruments, biomedical devices, and fleet vehicles — each requiring its own tracking logic. Jumping between separate systems is inefficient. Amex Suite unifies them into one coherent platform.',
 'Unified admin dashboard across all modules\nICT Inventory System (IIS) module\nLabTrack laboratory equipment module\nBiomedical equipment tracking module\nCross-module reporting and analytics\nRole-based access per module\nDesigned for institutions managing multiple asset categories',
 'PHP\nMySQL\nBootstrap\nJavaScript\nMulti-module Architecture',
 'Deployed — Internal Amex Product', 'deployed', 15, 0);
