<?php
require_once __DIR__ . '/functions.php';
$categories = getCategories();
$breaking = getBreakingNews();
$nepali_date = getNepaliDate();
// Use centralized route info (set by index.php) with fallback for direct file access
if (isset($_current_route)) {
    $current_route = $_current_route;
    $current_cat   = $_current_slug;
} else {
    $_base_path   = rtrim(parse_url(SITE_URL, PHP_URL_PATH), '/');
    $_uri_path    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $_clean_path  = trim(substr($_uri_path, strlen($_base_path)), '/');
    $_route_parts = $_clean_path !== '' ? explode('/', $_clean_path) : [];
    $current_route = $_route_parts[0] ?? '';
    $current_cat   = $_route_parts[1] ?? ($_GET['slug'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
<title><?= e($page_title ?? SITE_NAME) ?></title>
<meta name="description" content="<?= e($page_desc ?? SITE_TAGLINE) ?>">
<!-- Open Graph -->
<meta property="og:type"        content="<?= isset($article) ? 'article' : 'website' ?>">
<meta property="og:title"       content="<?= e($page_title ?? SITE_NAME) ?>">
<meta property="og:description" content="<?= e($page_desc ?? SITE_TAGLINE) ?>">
<meta property="og:url"         content="<?= e((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
<meta property="og:site_name"   content="<?= e(BRAND_NAME) ?> — <?= e(SITE_NAME) ?>">
<?php if (isset($article) && $article['image']): ?>
<meta property="og:image" content="<?= e(getImageUrl($article['image'])) ?>">
<?php else: ?>
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/images/gt-logo.png">
<?php endif; ?>
<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= e($page_title ?? SITE_NAME) ?>">
 <meta name="twitter:description" content="<?= e($page_desc ?? SITE_TAGLINE) ?>">
<?php if (isset($article) && $article['image']): ?>
<meta name="twitter:image" content="<?= e(getImageUrl($article['image'])) ?>">
<?php else: ?>
<meta name="twitter:image" content="<?= e(SITE_URL) ?>/assets/images/gt-logo.png">
<?php endif; ?>

<!-- Canonical URL -->
<link rel="canonical" href="<?= e((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?= e(SITE_URL) ?>/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(SITE_URL) ?>/assets/images/favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?= e(SITE_URL) ?>/assets/images/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;0,700;1,600&family=Work+Sans:wght@400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Mobile-specific optimizations -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="<?= $body_class ?? '' ?>">

<div class="reading-progress" id="readingProgress"></div>

<!-- Top Bar -->
<div class="top-bar">
  <div class="container top-bar-inner">
    <div class="top-date">
      <span><i class="fa fa-calendar-alt"></i> <?= $nepali_date['day'] ?>, <?= $nepali_date['bs'] ?></span>
      <span class="ad-date"><?= $nepali_date['ad'] ?></span>
    </div>
    <div class="top-bar-right">
      <div class="top-social">
        <a href="https://www.facebook.com/share/1M6PDqLoPw/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.tiktok.com/@gtnewsnepalupdate" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
        <a href="https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
        <a href="https://instagram.com/gtnewsnepal" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- Site Header -->
<header class="site-header">
  <div class="container header-inner">
    <a href="<?= SITE_URL ?>" class="site-logo">
      <img src="<?= SITE_URL ?>/assets/images/gt-logo.png" alt="<?= BRAND_NAME ?> - <?= SITE_NAME ?>" class="logo-img">
      <span class="logo-tag"><?= BRAND_NAME ?> | <?= SITE_TAGLINE ?></span>
    </a>
    <div class="header-ad">
      <?= displayAdvertisement('header', 'banner') ?>
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
           class="<?= ($current_route === 'category' && $current_cat === $cat['slug']) ? 'active' : '' ?>">
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
<div class="content-with-ads">
  <aside class="content-ad-sidebar left">
    <div class="ad-placeholder">विज्ञापन</div>
  </aside>
  <div class="content-main">
    <div class="container">
