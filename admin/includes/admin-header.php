<?php
/**
 * Shared admin chrome. Expects $admin_active (nav key) and $admin_page_title
 * to be set by the including page. Requires require_admin() to already
 * have been called by the page before this is required.
 */
$admin_active = $admin_active ?? '';
$admin_page_title = $admin_page_title ?? 'Dashboard';
$admin = current_admin();

function admin_nav_active($key, $active) {
    return $key === $active ? ' active' : '';
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($admin_page_title) ?> — Amex Admin</title>
    <link rel="icon" type="image/png" href="../img/core-img/logo.png">
    <style>
        :root {
            --a-dark:#071730; --a-blue:#0D47A1; --a-blue-light:#1976D2;
            --a-border:#e3e7ee; --a-bg:#f4f6fa; --a-text:#4a5568; --a-radius:10px;
        }
        * { box-sizing: border-box; }
        body { margin:0; font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif; background:var(--a-bg); color:var(--a-dark); }
        a { text-decoration:none; color:inherit; }
        .admin-shell { display:flex; min-height:100vh; }
        .admin-sidebar {
            width:230px; background:var(--a-dark); color:#fff; flex-shrink:0;
            padding:24px 0; position:sticky; top:0; height:100vh;
        }
        .admin-sidebar .brand { padding:0 20px 24px; font-weight:800; font-size:17px; border-bottom:1px solid rgba(255,255,255,.1); margin-bottom:12px; }
        .admin-sidebar .brand span { color:#4FC3F7; }
        .admin-nav a {
            display:flex; align-items:center; gap:10px; padding:11px 20px;
            font-size:14px; color:rgba(255,255,255,.75); transition:.15s;
        }
        .admin-nav a:hover { background:rgba(255,255,255,.06); color:#fff; }
        .admin-nav a.active { background:var(--a-blue); color:#fff; font-weight:600; }
        .admin-main { flex:1; min-width:0; }
        .admin-topbar {
            background:#fff; border-bottom:1px solid var(--a-border); padding:14px 28px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .admin-topbar h1 { font-size:19px; margin:0; }
        .admin-topbar .user { font-size:13px; color:var(--a-text); display:flex; align-items:center; gap:14px; }
        .admin-content { padding:28px; }
        .admin-card { background:#fff; border:1px solid var(--a-border); border-radius:var(--a-radius); padding:24px; margin-bottom:24px; }
        .admin-btn {
            display:inline-flex; align-items:center; gap:6px; border:none; border-radius:8px;
            padding:9px 18px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none;
        }
        .admin-btn-primary { background:linear-gradient(135deg,var(--a-blue),var(--a-blue-light)); color:#fff; }
        .admin-btn-secondary { background:#eef1f6; color:var(--a-dark); }
        .admin-btn-danger { background:#fdecea; color:#c62828; }
        table.admin-table { width:100%; border-collapse:collapse; font-size:14px; }
        table.admin-table th, table.admin-table td { padding:10px 12px; border-bottom:1px solid var(--a-border); text-align:left; vertical-align:top; }
        table.admin-table th { font-size:12px; text-transform:uppercase; letter-spacing:.4px; color:var(--a-text); }
        .admin-form label { display:block; font-size:13px; font-weight:700; margin:14px 0 6px; }
        .admin-form input, .admin-form select, .admin-form textarea {
            width:100%; padding:10px 12px; border:1px solid var(--a-border); border-radius:8px; font-size:14px; font-family:inherit;
        }
        .admin-form textarea { min-height:90px; resize:vertical; }
        .admin-flash { padding:12px 16px; border-radius:8px; font-size:14px; margin-bottom:18px; }
        .admin-flash.success { background:#e8f5e9; color:#2e7d32; }
        .admin-flash.error { background:#fdecea; color:#c62828; }
        .badge { display:inline-block; padding:2px 9px; border-radius:20px; font-size:11px; font-weight:700; }
        .badge-new { background:#e3f2fd; color:#0D47A1; }
        .badge-read { background:#eef1f6; color:#4a5568; }
        .badge-replied { background:#e8f5e9; color:#2e7d32; }
        .badge-archived { background:#f1f1f1; color:#888; }
    </style>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">Amex <span>Admin</span></div>
        <nav class="admin-nav">
            <a href="index.php" class="<?= admin_nav_active('dashboard', $admin_active) ?>">Dashboard</a>
            <a href="services.php" class="<?= admin_nav_active('services', $admin_active) ?>">Services</a>
            <a href="projects.php" class="<?= admin_nav_active('projects', $admin_active) ?>">Projects</a>
            <a href="team.php" class="<?= admin_nav_active('team', $admin_active) ?>">Team</a>
            <a href="images.php" class="<?= admin_nav_active('images', $admin_active) ?>">Images</a>
            <a href="messages.php" class="<?= admin_nav_active('messages', $admin_active) ?>">Messages</a>
            <a href="settings.php" class="<?= admin_nav_active('settings', $admin_active) ?>">Settings</a>
            <a href="../index.php" target="_blank">View Site ↗</a>
        </nav>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <h1><?= e($admin_page_title) ?></h1>
            <div class="user">
                <span><?= e($admin['username'] ?? '') ?></span>
                <a href="logout.php" class="admin-btn admin-btn-secondary">Log Out</a>
            </div>
        </div>
        <div class="admin-content">
