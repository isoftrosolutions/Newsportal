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
<html class="light" lang="ne">
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

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">

<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      "colors": {
        "surface-container-low": "#f6f3f2",
        "surface-dim": "#dcd9d9",
        "surface-container-high": "#eae7e7",
        "tertiary-container": "#006ea1",
        "surface-variant": "#e5e2e1",
        "primary-container": "#ce1126",
        "surface-alt": "#F4F4F4",
        "surface-container-highest": "#e5e2e1",
        "primary": "#a30019",
        "outline-variant": "#e6bdba",
        "on-surface": "#1b1c1c",
        "inverse-surface": "#303030",
        "on-primary-fixed-variant": "#930015",
        "outline": "#916f6c",
        "on-tertiary-fixed-variant": "#004b70",
        "on-secondary": "#ffffff",
        "on-tertiary-fixed": "#001e30",
        "error": "#ba1a1a",
        "inverse-primary": "#ffb3ae",
        "border-subtle": "#E2E2E2",
        "background": "#fcf9f8",
        "secondary-fixed": "#d6e3ff",
        "on-primary-container": "#ffe0dd",
        "surface-container-lowest": "#ffffff",
        "primary-fixed": "#ffdad7",
        "on-primary-fixed": "#410004",
        "on-tertiary": "#ffffff",
        "surface-tint": "#c0001f",
        "secondary-fixed-dim": "#a9c7ff",
        "on-error-container": "#93000a",
        "tertiary-fixed": "#cae6ff",
        "tertiary-fixed-dim": "#8dcdff",
        "on-error": "#ffffff",
        "on-secondary-fixed": "#001b3d",
        "on-tertiary-container": "#d4eaff",
        "secondary-container": "#84b1fe",
        "primary-fixed-dim": "#ffb3ae",
        "on-primary": "#ffffff",
        "error-container": "#ffdad6",
        "on-background": "#1b1c1c",
        "surface-bright": "#fcf9f8",
        "inverse-on-surface": "#f3f0ef",
        "on-secondary-fixed-variant": "#02468c",
        "surface": "#fcf9f8",
        "secondary": "#2b5ea5",
        "text-muted": "#666666",
        "tertiary": "#00557d",
        "on-surface-variant": "#5c3f3d",
        "surface-container": "#f0eded",
        "on-secondary-container": "#004286"
      },
      "borderRadius": {
        "DEFAULT": "0.125rem",
        "lg": "0.25rem",
        "xl": "0.5rem",
        "full": "0.75rem"
      },
      "spacing": {
        "stack-lg": "32px",
        "margin-desktop": "40px",
        "margin-mobile": "16px",
        "stack-sm": "8px",
        "stack-md": "16px",
        "gutter": "24px"
      },
      "fontFamily": {
        "headline-lg": ["Hanken Grotesk"],
        "label-caps": ["Hanken Grotesk"],
        "headline-md": ["Hanken Grotesk"],
        "body-md": ["Noto Sans"],
        "body-lg": ["Noto Sans"],
        "caption": ["Noto Sans"],
        "display-xl": ["Hanken Grotesk"]
      },
      "fontSize": {
        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
        "headline-md": ["24px", {"lineHeight": "30px", "fontWeight": "600"}],
        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
        "caption": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
        "display-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "800"}],
        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}]
      }
    }
  }
}
</script>
<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    display: inline-block;
    vertical-align: middle;
}
.scrolling-ticker {
    display: flex;
    white-space: nowrap;
    animation: scroll 60s linear infinite;
}
.scrolling-ticker:hover { animation-play-state: paused; }
@keyframes scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 226, 226, 0.5);
}
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: #f1f1f1; }
::-webkit-scrollbar-thumb { background: #a30019; }
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.reading-progress {
    position: fixed;
    top: 0; left: 0;
    height: 3px;
    background: #a30019;
    z-index: 9999;
    width: 0%;
    transition: width 0.1s linear;
    display: none;
}
body.is-article .reading-progress { display: block; }
.ticker-animate {
    animation: ticker-scroll 25s linear infinite;
    white-space: nowrap;
}
@keyframes ticker-scroll {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
/* Mobile nav */
#navList {
    display: flex;
    align-items: center;
}
@media (max-width: 768px) {
    #navList {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fcf9f8;
        border-bottom: 1px solid #E2E2E2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 4px 0;
        z-index: 50;
    }
    #navList.open {
        display: flex;
    }
    #navList a {
        width: 100%;
        padding: 12px 16px;
        border-bottom: 1px solid #E2E2E2;
        text-align: left;
    }
}
/* Mobile bottom nav safe area */
body { padding-bottom: 0; }
@media (max-width: 768px) {
    body { padding-bottom: 64px; }
}
</style>
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden <?= $body_class ?? '' ?>">

<div class="reading-progress" id="readingProgress"></div>

<!-- Utility Top Bar -->
<div class="bg-surface-container-lowest border-b border-border-subtle py-1 hidden md:block">
<div class="max-w-7xl mx-auto flex justify-between items-center px-4">
<div class="flex items-center space-x-4 text-caption text-text-muted">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">calendar_today</span> <?= $nepali_date['day'] ?>, <?= $nepali_date['bs'] ?></span>
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">location_on</span> काठमाडौं, नेपाल</span>
</div>
<div class="flex items-center space-x-4">
<div class="top-social">
<a href="https://www.facebook.com/share/1M6PDqLoPw/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
<a href="https://www.tiktok.com/@gtnewsnepalupdate" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
<a href="https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
<a href="https://instagram.com/gtnewsnepal" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
</div>
<div class="h-3 w-[1px] bg-outline-variant mx-2"></div>
<a class="text-caption font-bold text-primary flex items-center gap-1" href="<?= SITE_URL ?>/admin/login.php">
<span class="material-symbols-outlined text-[18px]">person</span> लगइन
</a>
</div>
</div>
</div>

<!-- Main Header -->
<header class="bg-background py-stack-md border-b border-border-subtle hidden md:block">
<div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-stack-md">
<div class="flex-shrink-0">
<a href="<?= SITE_URL ?>/">
<img src="<?= SITE_URL ?>/assets/images/gt-logo.png" alt="<?= e(BRAND_NAME) ?> - <?= e(SITE_NAME) ?>" class="h-12 md:h-20 w-auto object-contain">
</a>
</div>
<div class="hidden md:block w-[728px] h-[90px] bg-surface-container-low border border-border-subtle flex items-center justify-center relative overflow-hidden">
<?= displayAdvertisement('header', 'banner') ?>
</div>
</div>
</header>

<!-- Sticky Navigation Bar -->
<nav class="sticky top-0 z-50 bg-background border-b border-border-subtle shadow-sm">
<div class="max-w-7xl mx-auto px-margin-mobile">
<!-- Mobile header row (menu + logo + search + person) -->
<div class="flex md:hidden items-center justify-between h-14">
<div class="flex items-center gap-4">
<button class="material-symbols-outlined text-primary text-[28px] active:scale-95 transition-transform" id="navToggle">menu</button>
<a href="<?= SITE_URL ?>/">
<img src="<?= SITE_URL ?>/assets/images/gt-logo.png" alt="<?= e(BRAND_NAME) ?>" class="h-8 w-auto">
</a>
</div>
<div class="flex items-center gap-4">
<a href="<?= url('search') ?>" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-1 rounded-full transition-colors">search</a>
<a href="<?= SITE_URL ?>/admin/login.php" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-1 rounded-full transition-colors">person</a>
</div>
</div>
<!-- Desktop nav row + Mobile nav drawer combined -->
<div class="flex items-center justify-between relative">
<div id="navList" class="overflow-x-auto no-scrollbar scroll-smooth">
<a class="px-4 py-4 text-primary border-b-2 border-primary font-bold whitespace-nowrap font-label-caps text-label-caps" href="<?= SITE_URL ?>/">गृहपृष्ठ</a>
<?php foreach ($categories as $cat): ?>
<a class="px-4 py-4 text-on-surface-variant hover:text-primary transition-colors font-medium whitespace-nowrap font-label-caps text-label-caps <?= ($current_route === 'category' && $current_cat === $cat['slug']) ? 'text-primary border-b-2 border-primary' : '' ?>" href="<?= url('category', $cat['slug']) ?>">
<?= e($cat['name']) ?>
</a>
<?php endforeach; ?>
</div>
<div class="hidden md:flex items-center space-x-2 pl-4 border-l border-border-subtle py-2">
<a href="<?= url('search') ?>" class="p-2 hover:bg-surface-variant rounded-full transition-colors">
<span class="material-symbols-outlined">search</span>
</a>
</div>
</div>
</div>
</nav>

<!-- Breaking News Ticker -->
<?php if (!empty($breaking)): ?>
<div class="hidden md:block bg-surface-alt py-2 border-b border-border-subtle overflow-hidden">
<div class="max-w-7xl mx-auto px-4 flex items-center">
<div class="bg-primary text-white px-3 py-1 font-bold text-label-caps mr-4 shrink-0 rounded-sm">ताजा अपडेट</div>
<div class="relative flex-1 overflow-hidden h-6">
<div class="scrolling-ticker flex items-center space-x-12">
<?php foreach ($breaking as $bn): ?>
<a class="text-body-md font-medium hover:text-primary transition-colors flex items-center gap-2 whitespace-nowrap" href="#">
<span class="w-1.5 h-1.5 bg-primary rounded-full shrink-0"></span>
<?= e($bn['text']) ?>
</a>
<?php endforeach; ?>
<?php foreach ($breaking as $bn): ?>
<a class="text-body-md font-medium hover:text-primary transition-colors flex items-center gap-2 whitespace-nowrap" href="#">
<span class="w-1.5 h-1.5 bg-primary rounded-full shrink-0"></span>
<?= e($bn['text']) ?>
</a>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<?php endif; ?>
