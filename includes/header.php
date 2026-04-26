<?php
require_once __DIR__ . '/functions.php';
$categories = getCategories();
$breaking = getBreakingNews();
$nepali_date = getNepaliDate();
// Detect current route for active nav highlighting
$_base_path   = rtrim(parse_url(SITE_URL, PHP_URL_PATH), '/');
$_uri_path    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$_clean_path  = trim(substr($_uri_path, strlen($_base_path)), '/');
$_route_parts = $_clean_path !== '' ? explode('/', $_clean_path) : [];
$current_route = $_route_parts[0] ?? '';
$current_cat   = $_route_parts[1] ?? ($_GET['slug'] ?? '');
?>
<!DOCTYPE html>
<html lang="ne">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($page_title ?? SITE_NAME) ?></title>
<meta name="description" content="<?= e($page_desc ?? SITE_TAGLINE) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
  <div class="container top-bar-inner">
    <div class="top-date">
      <span><i class="fa fa-calendar-alt"></i> <?= $nepali_date['day'] ?>, <?= $nepali_date['bs'] ?></span>
      <span class="ad-date"><?= $nepali_date['ad'] ?></span>
    </div>
    <div class="top-social">
      <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
      <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
      <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</div>

<!-- Site Header -->
<header class="site-header">
  <div class="container header-inner">
    <a href="<?= SITE_URL ?>" class="site-logo">
      <span class="logo-text"><?= SITE_NAME ?></span>
      <span class="logo-tag"><?= SITE_TAGLINE ?></span>
    </a>
    <div class="header-ad">
      <div class="ad-placeholder">विज्ञापन स्थान</div>
    </div>
    <div class="header-search">
      <form action="<?= url('search') ?>" method="GET">
        <input type="search" name="q" placeholder="समाचार खोज्नुहोस्..." value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>
</header>

<!-- Main Navigation -->
<nav class="main-nav">
  <div class="container">
    <button class="nav-toggle" id="navToggle"><i class="fa fa-bars"></i> मेनु</button>
    <ul class="nav-list" id="navList">
      <li><a href="<?= SITE_URL ?>/" class="<?= $current_route === '' ? 'active' : '' ?>">गृहपृष्ठ</a></li>
      <?php foreach ($categories as $cat): ?>
      <li>
        <a href="<?= url('category', $cat['slug']) ?>"
           class="<?= ($current_route === 'category' && $current_cat === $cat['slug']) ? 'active' : '' ?>"
           style="border-bottom: 3px solid <?= e($cat['color']) ?>00;">
          <?= e($cat['name']) ?>
        </a>
      </li>
      <?php endforeach; ?>
      <li><a href="<?= url('search') ?>" class="<?= $current_route === 'search' ? 'active' : '' ?>">खोजी</a></li>
    </ul>
  </div>
</nav>

<!-- Breaking News Ticker -->
<?php if (!empty($breaking)): ?>
<div class="breaking-news">
  <div class="container breaking-inner">
    <span class="breaking-label"><i class="fa fa-bolt"></i> ताजा समाचार</span>
    <div class="ticker-wrap">
      <div class="ticker">
        <?php foreach ($breaking as $bn): ?>
          <span class="ticker-item"><?= e($bn['text']) ?> &nbsp;&nbsp;&bull;&nbsp;&nbsp;</span>
        <?php endforeach; ?>
        <?php foreach ($breaking as $bn): ?>
          <span class="ticker-item"><?= e($bn['text']) ?> &nbsp;&nbsp;&bull;&nbsp;&nbsp;</span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<main class="site-main">
<div class="container">
