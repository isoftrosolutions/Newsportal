<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/../admin/login.php');
    exit;
}
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
$admin_name   = $_SESSION['admin_name'] ?? 'Admin';
$admin_initials = mb_strtoupper(mb_substr($admin_name, 0, 1));
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ne">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($page_title ?? 'Admin') ?> — <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body">

<!-- ── Sidebar ──────────────────────────────────────── -->
<aside class="admin-sidebar">

  <div class="sidebar-brand">
    <div class="sidebar-brand-icon"><i class="fa fa-newspaper"></i></div>
    <div class="sidebar-brand-text">
      <div class="sidebar-brand-name"><?= SITE_NAME ?></div>
      <div class="sidebar-brand-sub">Admin Panel</div>
    </div>
  </div>

  <nav class="admin-nav">

    <span class="nav-section-label">मुख्य</span>

    <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-chart-line"></i></span>
        <span class="nav-item-label">ड्यासबोर्ड</span>
      </span>
    </a>

    <a href="articles.php" class="<?= in_array($current_page, ['articles.php','add-article.php','edit-article.php']) ? 'active' : '' ?>">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-newspaper"></i></span>
        <span class="nav-item-label">समाचारहरू</span>
      </span>
    </a>

    <a href="add-article.php" class="nav-sub <?= $current_page === 'add-article.php' ? 'active' : '' ?>">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-plus"></i></span>
        <span class="nav-item-label">नयाँ समाचार</span>
      </span>
    </a>

    <span class="nav-section-label">सेटिङ</span>

    <a href="categories.php" class="<?= $current_page === 'categories.php' ? 'active' : '' ?>">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-tags"></i></span>
        <span class="nav-item-label">विभागहरू</span>
      </span>
    </a>

    <a href="breaking.php" class="<?= $current_page === 'breaking.php' ? 'active' : '' ?>">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-bolt"></i></span>
        <span class="nav-item-label">ताजा समाचार</span>
      </span>
    </a>

    <div class="nav-divider"></div>

    <a href="<?= SITE_URL ?>/" target="_blank">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-arrow-up-right-from-square"></i></span>
        <span class="nav-item-label">वेबसाइट हेर्नुहोस्</span>
      </span>
    </a>

    <a href="logout.php" class="nav-logout">
      <span class="nav-item-inner">
        <span class="nav-icon-wrap"><i class="fa fa-right-from-bracket"></i></span>
        <span class="nav-item-label">लगआउट</span>
      </span>
    </a>

  </nav>

  <!-- User footer -->
  <div class="sidebar-user">
    <div class="sidebar-avatar"><?= $admin_initials ?></div>
    <div class="sidebar-user-info">
      <div class="sidebar-user-name"><?= e($admin_name) ?></div>
      <div class="sidebar-user-role">सम्पादक</div>
    </div>
    <a href="logout.php" class="sidebar-logout-btn" title="लगआउट">
      <i class="fa fa-right-from-bracket"></i>
    </a>
  </div>

</aside>

<!-- ── Main ─────────────────────────────────────────── -->
<div class="admin-main">

  <header class="admin-topbar">
    <div class="topbar-left">
      <span class="topbar-page"><?= e($page_title ?? 'Admin') ?></span>
    </div>
    <div class="topbar-right">
      <a href="<?= SITE_URL ?>/" target="_blank" class="topbar-btn">
        <i class="fa fa-arrow-up-right-from-square"></i>
        <span>वेबसाइट</span>
      </a>
      <a href="add-article.php" class="topbar-btn" style="background:var(--accent);border-color:var(--accent);color:#fff;">
        <i class="fa fa-plus"></i>
        <span>नयाँ समाचार</span>
      </a>
      <div class="topbar-user">
        <div class="topbar-avatar"><?= $admin_initials ?></div>
        <span class="topbar-username"><?= e($admin_name) ?></span>
      </div>
    </div>
  </header>

  <div class="admin-content">
