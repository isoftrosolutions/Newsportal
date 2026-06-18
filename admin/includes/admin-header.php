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
<title><?= e($page_title ?? 'Admin') ?> — <?= BRAND_NAME ?> Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body">

<!-- Admin Wrapper -->
<div class="admin-wrapper">

<!-- ── Sidebar ──────────────────────────────────────── -->
<aside class="admin-sidebar">

  <!-- Sidebar Header -->
  <div class="sidebar-header">
    <div class="logo-container">
      <a href="index.php" class="logo-link">
        <div class="logo-icon">
          <i class="fa fa-newspaper"></i>
        </div>
        <div class="logo-text">
          <div class="sidebar-brand-name"><?= BRAND_NAME ?></div>
          <div class="sidebar-brand-sub"><?= SITE_NAME ?> — Admin Panel</div>
        </div>
      </a>
    </div>
    <div class="sidebar-header-actions">
      <button class="sidebar-collapse-btn desktop-only" id="sidebarCollapseBtn" aria-label="Collapse sidebar">
        <i class="fa fa-chevron-left"></i>
      </button>
      <button class="sidebar-close-mobile mobile-only" aria-label="Close sidebar">
        <i class="fa fa-times"></i>
      </button>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav">
    <ul class="nav-list">

      <!-- Main Section -->
      <li class="nav-section-title">मुख्य</li>

      <li class="nav-item">
        <a href="index.php" class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>">
          <i class="nav-icon fa fa-chart-line"></i>
          <span class="nav-text">ड्यासबोर्ड</span>
        </a>
      </li>

      <li class="nav-item">
        <div class="nav-link-wrapper">
          <a href="articles.php" class="nav-link <?= in_array($current_page, ['articles.php','add-article.php','edit-article.php']) ? 'active' : '' ?>">
            <i class="nav-icon fa fa-newspaper"></i>
            <span class="nav-text">समाचारहरू</span>
          </a>
          <button class="submenu-toggle" data-bs-toggle="collapse" data-bs-target="#articles-submenu" aria-expanded="false" aria-label="Toggle submenu">
            <i class="fa fa-chevron-down dropdown-toggle-icon"></i>
          </button>
        </div>
        <ul class="submenu collapse" id="articles-submenu">
          <li><a href="add-article.php" class="submenu-link <?= $current_page === 'add-article.php' ? 'active' : '' ?>"><i class="fa fa-plus"></i> नयाँ समाचार</a></li>
        </ul>
      </li>

      <!-- Settings Section -->
      <li class="nav-section-title">सेटिङ</li>

      <li class="nav-item">
        <a href="categories.php" class="nav-link <?= $current_page === 'categories.php' ? 'active' : '' ?>">
          <i class="nav-icon fa fa-tags"></i>
          <span class="nav-text">विभागहरू</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="breaking.php" class="nav-link <?= $current_page === 'breaking.php' ? 'active' : '' ?>">
          <i class="nav-icon fa fa-bolt"></i>
          <span class="nav-text">ताजा समाचार</span>
        </a>
      </li>

      <li class="nav-item">
        <div class="nav-link-wrapper">
          <a href="advertisements.php" class="nav-link <?= in_array($current_page, ['advertisements.php','add-advertisement.php','edit-advertisement.php']) ? 'active' : '' ?>">
            <i class="nav-icon fa fa-ad"></i>
            <span class="nav-text">विज्ञापनहरू</span>
          </a>
          <button class="submenu-toggle" data-bs-toggle="collapse" data-bs-target="#ads-submenu" aria-expanded="false" aria-label="Toggle submenu">
            <i class="fa fa-chevron-down dropdown-toggle-icon"></i>
          </button>
        </div>
        <ul class="submenu collapse" id="ads-submenu">
          <li><a href="add-advertisement.php" class="submenu-link <?= $current_page === 'add-advertisement.php' ? 'active' : '' ?>"><i class="fa fa-plus"></i> नयाँ विज्ञापन</a></li>
        </ul>
      </li>

    </ul>
  </nav>

  <!-- Sidebar Footer -->
  <div class="sidebar-footer">
    <div class="sidebar-footer-content">
      <div class="footer-info">
        <i class="fa fa-external-link-alt"></i>
        <span class="footer-text">
          <a href="<?= SITE_URL ?>/" target="_blank">वेबसाइट हेर्नुहोस्</a>
        </span>
      </div>
      <a href="logout.php" class="logout-btn">
        <i class="fa fa-right-from-bracket"></i>
        <span class="logout-text">लगआउट</span>
      </a>
    </div>
  </div>

</aside>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay"></div>

<!-- ── Main Content ─────────────────────────────────── -->
<div class="main-content">

  <!-- Advanced Header -->
  <header class="admin-header glass-header">
    <div class="header-inner">

      <!-- Left Section -->
      <div class="header-left">
        <!-- Mobile Menu Toggle -->
        <button class="icon-btn mobile-only" id="sidebarToggle" aria-label="Toggle sidebar">
          <i class="fa fa-bars"></i>
        </button>

        <!-- Page Title - Always Visible -->
        <div class="page-title-section">
          <h1 class="page-title"><?= e($page_title ?? 'Admin') ?></h1>
        </div>
      </div>

      <!-- Right Section -->
      <div class="header-right">
        <!-- Mobile Search Toggle -->
        <button class="icon-btn mobile-only" id="mobileSearchToggle" aria-label="Search">
          <i class="fa fa-search"></i>
        </button>

        <!-- Desktop Search -->
        <div class="search-container desktop-only">
          <div class="search-wrapper">
            <button class="search-category-btn" type="button">
              <span>सबै</span>
            </button>
            <input type="text" class="search-input" placeholder="खोज्नुहोस्..." autocomplete="off">
            <button class="search-btn" type="button">
              <i class="fa fa-search"></i>
            </button>
          </div>

          <!-- Search Dropdown -->
          <div class="search-results-dropdown" id="searchResults">
            <div class="search-results-header">
              <h6>Search Results</h6>
            </div>
            <div class="search-result-item">
              <div class="result-icon">
                <i class="fa fa-search"></i>
              </div>
              <div class="result-content">
                <div class="result-title">Start typing to search...</div>
                <div class="result-meta">Articles, categories, and more</div>
              </div>
              <div class="result-arrow">
                <i class="fa fa-chevron-right"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="dropdown-container">
          <button class="icon-btn" id="quickActionsBtn" title="Quick actions" aria-label="Quick actions">
            <i class="fa fa-plus"></i>
          </button>

          <!-- Quick Actions Dropdown -->
          <div class="dropdown-menu quick-actions-menu" id="quickActionsMenu" role="menu" aria-hidden="true">
            <a href="add-article.php" class="dropdown-item" role="menuitem">
              <i class="fa fa-plus"></i>
              <div>
                <div>New Article</div>
                <small>Create a new article</small>
              </div>
            </a>
            <a href="categories.php" class="dropdown-item" role="menuitem">
              <i class="fa fa-tags"></i>
              <div>
                <div>New Category</div>
                <small>Add a new category</small>
              </div>
            </a>
            <a href="#" class="dropdown-item" role="menuitem">
              <i class="fa fa-upload"></i>
              <div>
                <div>Import Data</div>
                <small>Import articles or data</small>
              </div>
            </a>
          </div>
        </div>

<!-- Notifications -->
        <button class="icon-btn" id="notificationsBtn" title="Notifications" aria-label="Notifications" style="display:none">
          <i class="fa fa-bell"></i>
        </button>

          <!-- Notifications Dropdown -->
          <div class="dropdown-menu notification-dropdown" id="notificationMenu" role="menu" aria-hidden="true">
            <div class="notification-header">
              <h6><i class="fa fa-bell"></i> Notifications</h6>
              <a href="#" class="btn btn-sm btn-outline">Mark all read</a>
            </div>
            <div class="notification-list">
              <div class="notification-item">
                <div class="result-icon">
                  <i class="fa fa-newspaper"></i>
                </div>
                <div class="result-content">
                  <div class="result-title">New article published</div>
                  <div class="result-meta">2 hours ago</div>
                </div>
              </div>
              <div class="notification-item">
                <div class="result-icon">
                  <i class="fa fa-user"></i>
                </div>
                <div class="result-content">
                  <div class="result-title">New user registered</div>
                  <div class="result-meta">5 hours ago</div>
                </div>
              </div>
            </div>
            <div class="notification-footer">
              <a href="#" class="btn btn-sm btn-outline">View all notifications</a>
            </div>
          </div>
        </div>

        <!-- User Menu -->
        <div class="dropdown-container">
          <button class="user-avatar-btn" id="userMenuBtn" aria-label="User menu">
            <div class="avatar-circle"><?= $admin_initials ?></div>
            <div class="user-info desktop-only">
              <span class="user-name"><?= e($admin_name) ?></span>
            </div>
            <i class="fa fa-chevron-down"></i>
          </button>

          <!-- User Menu Dropdown -->
          <div class="dropdown-menu user-menu" id="userMenu" role="menu" aria-hidden="true">
            <div class="user-menu-header">
              <div class="avatar-circle" style="width: 48px; height: 48px; margin: 0 auto 8px;"><?= $admin_initials ?></div>
              <div style="text-align: center;">
                <div style="font-weight: 600; color: var(--text-main);"><?= e($admin_name) ?></div>
                <div style="font-size: 12px; color: var(--text-muted);">Administrator</div>
              </div>
            </div>
            <a href="profile.php" class="dropdown-item">
              <i class="fa fa-user"></i>
              <span>Profile</span>
            </a>
            <a href="settings.php" class="dropdown-item">
              <i class="fa fa-cog"></i>
              <span>Settings</span>
            </a>
            <div class="dropdown-item" id="themeToggle">
              <i class="fa fa-moon"></i>
              <span>Dark Mode</span>
            </div>
            <div style="border-top: 1px solid var(--border-color); margin: 8px 0;"></div>
            <a href="logout.php" class="dropdown-item">
              <i class="fa fa-right-from-bracket"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>


      </div>
    </div>

    <!-- Mobile Search Bar -->
    <div class="mobile-search-bar" id="mobileSearchBar">
      <div class="mobile-search-wrapper">
        <button class="mobile-search-category-btn" type="button">
          <span>All</span>
        </button>
        <input type="text" class="mobile-search-input" placeholder="Search articles, categories..." autocomplete="off">
        <button class="mobile-search-close" id="mobileSearchClose" aria-label="Close search">
          <i class="fa fa-times"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Page Container -->
  <div class="page-container">
