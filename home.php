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

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
