<?php
require_once __DIR__ . '/config.php';

$page_title = SITE_NAME . ' - ' . SITE_TAGLINE;
$page_desc  = SITE_TAGLINE;

require_once __DIR__ . '/includes/header.php';

// Organization Schema Markup
$org_schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'NewsMediaOrganization',
            '@id' => SITE_URL . '#organization',
            'name' => BRAND_NAME . ' - ' . SITE_NAME,
            'alternateName' => BRAND_NAME,
            'description' => SITE_TAGLINE,
            'url' => SITE_URL,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => SITE_URL . '/assets/images/gt-logo.png',
                'width' => 200,
                'height' => 200
            ],
            'sameAs' => [
                'https://www.facebook.com/share/1M6PDqLoPw/',
                'https://www.tiktok.com/@gtnewsnepalupdate',
                'https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7',
                'https://instagram.com/gtnewsnepal'
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'addressRegion' => 'Parsa',
                'addressCountry' => 'NP'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+977-9829406332',
                'contactType' => 'customer service',
                'availableLanguage' => ['ne', 'en']
            ],
            'foundingDate' => '2024',
            'knowsAbout' => ['News', 'Politics', 'Local Events', 'Nepal', 'Parsa District'],
            'publishingPrinciples' => 'https://www.presscouncilnepal.org.np/',
            'ethicsPolicy' => 'https://www.presscouncilnepal.org.np/',
            'diversityPolicy' => 'https://www.presscouncilnepal.org.np/',
            'correctionsPolicy' => 'https://www.presscouncilnepal.org.np/',
            'ownershipFundingInfo' => SITE_URL . '/about',
            'masthead' => SITE_URL . '/about'
        ],
        [
            '@type' => 'LocalBusiness',
            '@id' => SITE_URL . '#localbusiness',
            'name' => SITE_NAME,
            'description' => 'Local news media organization serving Parsa district and Nepal',
            'url' => SITE_URL,
            'address' => [
                '@type' => 'PostalAddress',
                'addressRegion' => 'Parsa',
                'addressCountry' => 'NP'
            ],
            'telephone' => '+977-9829406332',
            'priceRange' => 'Free',
            'areaServed' => [
                [
                    '@type' => 'GeoCircle',
                    'geoMidpoint' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => 27.0833,
                        'longitude' => 84.8667
                    ],
                    'geoRadius' => 50000
                ]
            ]
        ]
    ]
];
?>
<script type="application/ld+json">
<?= json_encode($org_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php
$featured  = getFeaturedArticles(5);
$latest    = getLatestArticles(10);
$popular   = getPopularArticles(5);
$cats      = getCategories();
?>

<main class="max-w-7xl mx-auto px-4 py-stack-lg">

<!-- ==================== DESKTOP LAYOUT (hidden on mobile) ==================== -->
<div class="hidden md:block">

<!-- Hero Section -->
<?php if (!empty($featured)): ?>
<section class="mb-stack-lg border-b border-border-subtle pb-stack-lg">
<div class="flex flex-col lg:flex-row gap-gutter">
<div class="lg:w-8/12">
<div class="relative group cursor-pointer">
<div class="overflow-hidden rounded-lg aspect-[16/9]">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
src="<?= getImageUrl($featured[0]['image'], null, 'large') ?>"
srcset="<?= getImageUrl($featured[0]['image'], null, 'medium') ?> 600w,
<?= getImageUrl($featured[0]['image'], null, 'large') ?> 800w"
sizes="(max-width: 768px) 100vw, 800px"
alt="<?= e($featured[0]['title']) ?>">
</div>
<div class="mt-stack-md space-y-stack-sm">
<span class="bg-primary text-white text-label-caps font-label-caps px-2 py-0.5 rounded-sm"><?= e($featured[0]['cat_name']) ?></span>
<h2 class="text-headline-lg md:text-display-xl font-display-xl leading-tight group-hover:text-primary transition-colors">
<a href="<?= url('article', $featured[0]['slug']) ?>"><?= e($featured[0]['title']) ?></a>
</h2>
<p class="text-body-lg text-on-surface-variant font-body-lg"><?= e($featured[0]['excerpt']) ?></p>
<div class="flex items-center text-caption text-text-muted space-x-4">
<span class="font-bold text-on-surface"><?= e($featured[0]['author']) ?></span>
<span><?= timeAgo($featured[0]['published_at']) ?></span>
<button class="bookmark-btn ml-auto" data-slug="<?= e($featured[0]['slug']) ?>" data-title="<?= e($featured[0]['title']) ?>" data-image="<?= getImageUrl($featured[0]['image'], null, 'small') ?>" data-cat="<?= e($featured[0]['cat_name']) ?>" data-time="<?= timeAgo($featured[0]['published_at']) ?>">
<span class="material-symbols-outlined bookmark-icon text-[18px] text-on-surface-variant">bookmark</span>
</button>
</div>
</div>
</div>
</div>
<!-- Top Stories Sidebar -->
<div class="lg:w-4/12 flex flex-col gap-stack-md">
<?php if (!empty($popular)): ?>
<div class="bg-surface-container-lowest p-stack-md border border-border-subtle rounded-lg">
<h3 class="text-headline-md font-headline-md border-l-4 border-primary pl-3 mb-stack-md">पढ्नै पर्ने</h3>
<div class="space-y-stack-md">
<?php foreach (array_slice($popular, 0, 3) as $art): ?>
<a href="<?= url('article', $art['slug']) ?>" class="flex gap-stack-sm group cursor-pointer no-underline">
<div class="w-24 h-24 shrink-0 overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform" src="<?= getImageUrl($art['image'], null, 'small') ?>" alt="<?= e($art['title']) ?>" loading="lazy">
</div>
<div>
<h4 class="text-body-md font-bold leading-snug group-hover:text-primary transition-colors text-on-surface"><?= e($art['title']) ?></h4>
<p class="text-caption text-text-muted mt-1"><?= e($art['cat_name']) ?></p>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
<!-- Sidebar Ad -->
<div class="w-full min-h-[250px] bg-surface-container-low border border-border-subtle flex items-center justify-center relative overflow-hidden">
<?= displayAdvertisement('sidebar', 'medium') ?>
</div>
</div>
</div>
</section>
<?php endif; ?>

<!-- Category Sections -->
<?php foreach ($cats as $cat_index => $cat): ?>
<?php $cat_arts = getArticlesByCategory($cat['id'], 5); ?>
<?php if (empty($cat_arts) || $cat_index >= 6) continue; ?>

<?php if ($cat_index === 0): ?>
<!-- First category: Bento Grid Layout -->
<section class="mb-stack-lg">
<div class="flex items-center justify-between mb-stack-md">
<h3 class="text-headline-md font-headline-md border-l-4 border-primary pl-3"><?= e($cat['name']) ?></h3>
<a class="text-caption font-bold text-primary hover:underline flex items-center" href="<?= url('category', $cat['slug']) ?>">थप हेर्नुहोस् <span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
<a href="<?= url('article', $cat_arts[0]['slug']) ?>" class="md:col-span-2 row-span-2 group cursor-pointer no-underline">
<div class="relative overflow-hidden rounded-lg aspect-square md:aspect-auto md:h-full">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="<?= getImageUrl($cat_arts[0]['image'], null, 'large') ?>" alt="<?= e($cat_arts[0]['title']) ?>" loading="lazy">
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-0 left-0 p-stack-md text-white">
<h4 class="text-headline-md font-headline-md mb-2"><?= e($cat_arts[0]['title']) ?></h4>
<p class="text-body-md opacity-80 line-clamp-2"><?= e($cat_arts[0]['excerpt']) ?></p>
</div>
</div>
</a>
<?php foreach (array_slice($cat_arts, 1, 4) as $art): ?>
<a href="<?= url('article', $art['slug']) ?>" class="md:col-span-1 group cursor-pointer no-underline">
<div class="overflow-hidden rounded-lg aspect-video mb-3">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform" src="<?= getImageUrl($art['image'], null, 'medium') ?>" alt="<?= e($art['title']) ?>" loading="lazy">
</div>
<h4 class="text-body-md font-bold group-hover:text-primary transition-colors text-on-surface"><?= e($art['title']) ?></h4>
</a>
<?php endforeach; ?>
</div>
</section>

<?php elseif ($cat_index === 2): ?>
<!-- Video/Reels section after 2nd category -->
<section class="mb-stack-lg bg-inverse-surface -mx-4 px-4 py-stack-lg md:rounded-xl">
<div class="flex items-center justify-between mb-stack-md">
<h3 class="text-headline-md font-headline-md text-white border-l-4 border-primary-fixed pl-3"><?= e($cat['name']) ?> रिल्स्</h3>
<span class="text-primary-fixed material-symbols-outlined text-[32px]">play_circle</span>
</div>
<div class="flex gap-gutter overflow-x-auto no-scrollbar pb-4">
<?php foreach ($cat_arts as $art): ?>
<a href="<?= url('article', $art['slug']) ?>" class="flex-shrink-0 w-48 aspect-[9/16] relative group cursor-pointer rounded-xl overflow-hidden no-underline">
<img class="w-full h-full object-cover" src="<?= getImageUrl($art['image'], null, 'medium') ?>" alt="<?= e($art['title']) ?>" loading="lazy">
<div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-all flex items-end p-stack-sm">
<p class="text-white text-caption font-bold"><?= e($art['title']) ?></p>
</div>
</a>
<?php endforeach; ?>
</div>
</section>

<?php else: ?>
<!-- Standard Category Section -->
<section class="mb-stack-lg">
<div class="flex items-center justify-between mb-stack-md">
<h3 class="text-headline-md font-headline-md border-l-4 border-primary pl-3"><?= e($cat['name']) ?></h3>
<a class="text-caption font-bold text-primary hover:underline flex items-center" href="<?= url('category', $cat['slug']) ?>">थप हेर्नुहोस् <span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<?php foreach (array_slice($cat_arts, 0, 4) as $art): ?>
<a href="<?= url('article', $art['slug']) ?>" class="group cursor-pointer no-underline">
<div class="overflow-hidden rounded-lg aspect-video mb-3">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= getImageUrl($art['image'], null, 'medium') ?>" alt="<?= e($art['title']) ?>" loading="lazy">
</div>
<h4 class="text-body-md font-bold group-hover:text-primary transition-colors text-on-surface"><?= e($art['title']) ?></h4>
</a>
<?php endforeach; ?>
</div>
</section>
<?php endif; ?>

<?php endforeach; ?>

<!-- Horizontal Banner Ad -->
<div class="mb-stack-lg w-full min-h-[120px] bg-surface-container-low border border-border-subtle flex items-center justify-center relative overflow-hidden">
<?= displayAdvertisement('footer', 'banner') ?>
</div>

<!-- Multi-Section Grid: Business, Tech, Opinion -->
<?php if (!empty($cats)): ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-stack-lg">
<?php $mid_cats = array_slice($cats, 0, 3); ?>
<?php foreach ($mid_cats as $cat): ?>
<?php $mid_arts = getArticlesByCategory($cat['id'], 4); ?>
<?php if (empty($mid_arts)) continue; ?>
<section>
<div class="flex items-center justify-between mb-stack-md">
<h3 class="text-headline-md font-headline-md border-l-4 border-primary pl-3"><?= e($cat['name']) ?></h3>
</div>
<div class="space-y-stack-md">
<a href="<?= url('article', $mid_arts[0]['slug']) ?>" class="group cursor-pointer no-underline block">
<div class="overflow-hidden rounded-lg aspect-video mb-2">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform" src="<?= getImageUrl($mid_arts[0]['image'], null, 'medium') ?>" alt="<?= e($mid_arts[0]['title']) ?>" loading="lazy">
</div>
<h4 class="text-body-md font-bold group-hover:text-primary transition-colors text-on-surface"><?= e($mid_arts[0]['title']) ?></h4>
</a>
<ul class="space-y-3">
<?php foreach (array_slice($mid_arts, 1, 3) as $art): ?>
<li class="border-b border-border-subtle pb-2 group">
<a href="<?= url('article', $art['slug']) ?>" class="no-underline">
<h5 class="text-body-md font-medium group-hover:text-primary transition-colors text-on-surface"><?= e($art['title']) ?></h5>
</a>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php endforeach; ?>
</div>
<?php endif; ?>

</div><!-- /desktop layout -->

<!-- ==================== MOBILE LAYOUT (hidden on desktop) ==================== -->
<div class="block md:hidden">

<?php
// Market indices placeholder
$indices = [
    ['name' => 'NEPSE', 'value' => '२०७२.६४', 'change' => '१३.४', 'up' => true],
    ['name' => 'USD/NPR', 'value' => '१३३.७८', 'change' => '०.०२', 'up' => false],
    ['name' => 'GOLD', 'value' => '१,४२,१००', 'change' => '५००', 'up' => true],
];
?>

<!-- Market Indices / Ticker Strip -->
<div class="flex gap-4 overflow-x-auto px-4 py-3 bg-white border-b border-border-subtle hide-scrollbar">
<?php foreach ($indices as $idx): ?>
<div class="min-w-[120px] flex flex-col<?= $idx !== $indices[0] ? ' border-l border-border-subtle pl-4' : '' ?>">
<span class="text-[11px] font-bold text-text-muted"><?= $idx['name'] ?></span>
<div class="flex items-center gap-1">
<span class="text-body-md font-bold"><?= $idx['value'] ?></span>
<span class="text-xs flex items-center <?= $idx['up'] ? 'text-green-600' : 'text-red-500' ?>">
<span class="material-symbols-outlined text-xs"><?= $idx['up'] ? 'trending_up' : 'trending_down' ?></span>
<?= $idx['change'] ?>
</span>
</div>
</div>
<?php endforeach; ?>
</div>

<!-- Main Ad Placement (Top) -->
<div class="px-4 py-4 bg-surface-alt flex justify-center border-b border-border-subtle">
<div class="w-full rounded shadow-sm bg-surface-container-low flex items-center justify-center min-h-[80px]">
<?= displayAdvertisement('mobile_top', 'banner') ?>
</div>
</div>

<!-- Breaking News Strip (simple) -->
<?php if (!empty($breaking)): ?>
<div class="bg-primary/5 border-y border-primary/10 py-2 px-4 flex items-center gap-3">
<span class="bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded leading-none uppercase">Breaking</span>
<div class="flex-1 overflow-hidden">
<p class="text-body-md font-bold text-primary truncate"><?= e($breaking[0]['text']) ?></p>
</div>
</div>
<?php endif; ?>

<?php if (!empty($featured)): ?>
<!-- Lead Story (Full Width Overlay) -->
<section class="mt-4 px-4">
<article class="relative group cursor-pointer overflow-hidden rounded-xl">
<a href="<?= url('article', $featured[0]['slug']) ?>" class="no-underline">
<div class="aspect-[16/9]">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="<?= getImageUrl($featured[0]['image'], null, 'large') ?>" alt="<?= e($featured[0]['title']) ?>" loading="lazy">
</div>
<div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-5">
<span class="bg-primary text-white text-[11px] font-bold px-2 py-0.5 rounded w-fit mb-2"><?= e($featured[0]['cat_name']) ?></span>
<h2 class="text-headline-lg-mobile font-headline-lg-mobile text-white leading-tight"><?= e($featured[0]['title']) ?></h2>
<div class="flex items-center gap-3 mt-3 text-white/80 text-[12px]">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> <?= timeAgo($featured[0]['published_at']) ?></span>
<button class="flex items-center gap-1 share-btn text-white/80" data-url="<?= url('article', $featured[0]['slug']) ?>"><span class="material-symbols-outlined text-sm">share</span></button>
</div>
</div>
</a>
</article>
</section>
<?php endif; ?>

<!-- High Density Stacked Feed -->
<section class="mt-6 px-4 space-y-6">

<?php if (isset($featured[1])): ?>
<!-- Story Card 1 (full width image) -->
<article class="flex flex-col gap-3 pb-6 border-b border-border-subtle">
<a href="<?= url('article', $featured[1]['slug']) ?>" class="no-underline">
<div class="aspect-video overflow-hidden rounded-lg">
<img class="w-full h-full object-cover" src="<?= getImageUrl($featured[1]['image'], null, 'large') ?>" alt="<?= e($featured[1]['title']) ?>" loading="lazy">
</div>
<div>
<span class="text-primary font-bold text-[13px] uppercase tracking-wide"><?= e($featured[1]['cat_name']) ?></span>
<h3 class="text-[20px] font-bold leading-tight mt-1 hover:text-primary transition-colors text-on-surface"><?= e($featured[1]['title']) ?></h3>
<p class="text-on-surface-variant text-body-md mt-2 line-clamp-3"><?= e($featured[1]['excerpt']) ?></p>
<div class="flex items-center gap-4 mt-3 text-text-muted text-[12px]">
<span class="font-medium"><?= timeAgo($featured[1]['published_at']) ?></span>
<button class="bookmark-btn" data-slug="<?= e($featured[1]['slug']) ?>" data-title="<?= e($featured[1]['title']) ?>" data-image="<?= getImageUrl($featured[1]['image'], null, 'small') ?>" data-cat="<?= e($featured[1]['cat_name']) ?>" data-time="<?= timeAgo($featured[1]['published_at']) ?>">
<span class="material-symbols-outlined bookmark-icon text-lg">bookmark</span>
</button>
</div>
</div>
</a>
</article>
<?php endif; ?>

<!-- Fresh Update Slider -->
<div class="bg-surface-container-low -mx-4 px-4 py-6 border-y border-border-subtle">
<div class="flex justify-between items-center mb-4">
<h3 class="text-xl font-bold border-l-4 border-primary pl-3">ताजा अपडेट</h3>
<a class="text-primary text-[13px] font-bold" href="<?= url('search') ?>">थप हेर्नुहोस्</a>
</div>
<div class="flex gap-3 overflow-x-auto hide-scrollbar">
<?php foreach ($latest as $upd): ?>
<div class="min-w-[240px] bg-white p-4 rounded-lg shadow-sm border border-border-subtle">
<span class="text-[11px] font-bold text-primary uppercase"><?= e($upd['cat_name']) ?></span>
<h4 class="font-bold text-body-lg mt-1 line-clamp-2 text-on-surface"><?= e($upd['title']) ?></h4>
<p class="text-[12px] text-text-muted mt-3"><?= timeAgo($upd['published_at']) ?></p>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- Inline Ad -->
<div class="py-4 flex justify-center border-b border-border-subtle">
<div class="w-full rounded border border-border-subtle bg-surface-container-low flex items-center justify-center min-h-[80px]">
<?= displayAdvertisement('mobile_mid', 'banner') ?>
</div>
</div>

<!-- Compact Side-by-Side Stories -->
<?php $side_arts = array_slice($latest, 0, 4); ?>
<?php foreach ($side_arts as $i => $art): ?>
<article class="flex gap-4 pb-6 border-b border-border-subtle">
<a href="<?= url('article', $art['slug']) ?>" class="flex gap-4 w-full no-underline group">
<div class="flex-1">
<span class="<?= $i % 2 === 0 ? 'text-secondary' : 'text-primary' ?> font-bold text-[12px] uppercase"><?= e($art['cat_name']) ?></span>
<h3 class="font-bold text-body-lg leading-tight mt-1 text-on-surface group-hover:text-primary transition-colors"><?= e($art['title']) ?></h3>
<p class="text-[12px] text-text-muted mt-2"><?= timeAgo($art['published_at']) ?></p>
</div>
<div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg">
<img class="w-full h-full object-cover" src="<?= getImageUrl($art['image'], null, 'small') ?>" alt="<?= e($art['title']) ?>" loading="lazy">
</div>
</a>
</article>
<?php endforeach; ?>

</section>

<!-- Reels Section (Dark Style) -->
<?php
$video_cat = null;
foreach ($cats as $cat) {
    if (stripos($cat['name'], 'भिडियो') !== false || stripos($cat['slug'], 'video') !== false) {
        $video_cat = $cat;
        break;
    }
}
if ($video_cat):
    $video_arts = getArticlesByCategory($video_cat['id'], 5);
    if (!empty($video_arts)):
?>
<section class="mt-8 bg-zinc-950 py-8">
<div class="px-4 flex items-center justify-between mb-5">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-white">play_circle</span>
<h3 class="text-xl font-bold text-white"><?= e($video_cat['name']) ?> स्टोरी</h3>
</div>
<a class="text-white/60 text-xs font-bold uppercase" href="<?= url('category', $video_cat['slug']) ?>">View All</a>
</div>
<div class="flex gap-3 overflow-x-auto px-4 hide-scrollbar">
<?php foreach ($video_arts as $va): ?>
<a href="<?= url('article', $va['slug']) ?>" class="relative min-w-[160px] aspect-[9/16] rounded-xl overflow-hidden shadow-2xl no-underline">
<img class="w-full h-full object-cover" src="<?= getImageUrl($va['image'], null, 'medium') ?>" alt="<?= e($va['title']) ?>" loading="lazy">
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-3 left-3 right-3">
<p class="text-white text-[13px] font-bold leading-snug line-clamp-2"><?= e($va['title']) ?></p>
</div>
</a>
<?php endforeach; ?>
</div>
</section>
<?php endif; endif; ?>

<!-- Category Sections: Sports & International -->
<?php
$sports_cat = null;
$intl_cat = null;
foreach ($cats as $cat) {
    if (stripos($cat['name'], 'खेलकुद') !== false || stripos($cat['slug'], 'sports') !== false) $sports_cat = $cat;
    if (stripos($cat['name'], 'विश्व') !== false || stripos($cat['slug'], 'world') !== false || stripos($cat['slug'], 'international') !== false) $intl_cat = $cat;
}
?>
<section class="mt-8 px-4 pb-12 space-y-10">

<?php if ($sports_cat): $sports_arts = getArticlesByCategory($sports_cat['id'], 4); if (!empty($sports_arts)): ?>
<div>
<div class="flex items-center justify-between border-b-2 border-secondary pb-1.5 mb-5">
<h3 class="text-xl font-bold text-secondary"><?= e($sports_cat['name']) ?></h3>
<span class="material-symbols-outlined text-secondary">sports_soccer</span>
</div>
<div class="space-y-5">
<?php foreach ($sports_arts as $sa): ?>
<a href="<?= url('article', $sa['slug']) ?>" class="flex gap-4 items-center no-underline group">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="<?= getImageUrl($sa['image'], null, 'small') ?>" alt="<?= e($sa['title']) ?>" loading="lazy">
</div>
<h4 class="font-bold text-[17px] leading-tight text-on-surface group-hover:text-primary transition-colors"><?= e($sa['title']) ?></h4>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endif; endif; ?>

<?php if ($intl_cat): $intl_arts = getArticlesByCategory($intl_cat['id'], 4); if (!empty($intl_arts)): ?>
<div>
<div class="flex items-center justify-between border-b-2 border-primary pb-1.5 mb-5">
<h3 class="text-xl font-bold text-primary"><?= e($intl_cat['name']) ?></h3>
<span class="material-symbols-outlined text-primary">public</span>
</div>
<a href="<?= url('article', $intl_arts[0]['slug']) ?>" class="bg-surface-alt rounded-xl overflow-hidden border border-border-subtle block no-underline group">
<img class="w-full h-48 object-cover" src="<?= getImageUrl($intl_arts[0]['image'], null, 'medium') ?>" alt="<?= e($intl_arts[0]['title']) ?>" loading="lazy">
<div class="p-4">
<h4 class="text-lg font-bold leading-tight text-on-surface group-hover:text-primary transition-colors"><?= e($intl_arts[0]['title']) ?></h4>
<p class="text-[14px] text-on-surface-variant mt-2 line-clamp-2"><?= e($intl_arts[0]['excerpt']) ?></p>
</div>
</a>
</div>
<?php endif; endif; ?>
</section>

</div><!-- /mobile layout -->

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
