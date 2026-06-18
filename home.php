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
                'addressCountry' => 'NP',
                'addressCountry' => 'Nepal'
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
$tajaa     = getLatestArticles(6);
?>

<!-- ताजा अपडेट Strip -->
<?php if (!empty($tajaa)): ?>
<div class="tajaa-strip">
  <div class="container">
    <div class="tajaa-inner">
      <span class="tajaa-label"><i class="fa fa-bolt"></i> ताजा अपडेट</span>
      <div class="tajaa-items">
        <?php foreach ($tajaa as $art): ?>
        <a href="<?= url('article', $art['slug']) ?>" class="tajaa-item">
          <span class="tajaa-time"><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
          <span class="tajaa-title"><?= e($art['title']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <a href="<?= url('search') ?>" class="tajaa-more">थप <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Web Stories -->
<?php if (!empty($featured)): ?>
<section class="web-stories-section">
  <div class="container">
    <div class="stories-section-header">
      <span class="stories-section-label">
        <i class="fa fa-layer-group"></i> वेबस्टोरिज
      </span>
      <a href="<?= url('search') ?>" class="stories-section-more">थप हेर्नुहोस् &rarr;</a>
    </div>
    <div class="stories-scroll">
      <?php foreach ($featured as $i => $story): ?>
      <a href="<?= url('article', $story['slug']) ?>" class="story-card">
        <div class="story-img-wrap">
          <img src="<?= getImageUrl($story['image']) ?>" alt="<?= e($story['title']) ?>">
          <div class="story-overlay">
          </div>
        </div>
        <span class="story-title"><?= e($story['title']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Hero Section -->
<?php if (!empty($featured)): ?>
<section class="hero-section mt-20">
  <div class="hero-main">
    <img class="hero-img"
         src="<?= getImageUrl($featured[0]['image'], null, 'large') ?>"
         srcset="<?= getImageUrl($featured[0]['image'], null, 'medium') ?> 600w,
                 <?= getImageUrl($featured[0]['image'], null, 'large') ?> 800w"
         sizes="(max-width: 768px) 100vw, 800px"
         alt="<?= e($featured[0]['title']) ?>">
    <div class="hero-overlay">
      <a class="cat-badge" href="<?= url('category', $featured[0]['cat_slug']) ?>">
        <?= e($featured[0]['cat_name']) ?>
      </a>
      <h2 class="hero-title">
        <a href="<?= url('article', $featured[0]['slug']) ?>">
          <?= e($featured[0]['title']) ?>
        </a>
      </h2>
      <div class="article-meta">
        <span><i class="fa fa-user"></i> <?= e($featured[0]['author']) ?></span>
        <span><i class="fa fa-clock"></i> <?= timeAgo($featured[0]['published_at']) ?></span>
        <span><i class="fa fa-eye"></i> <?= number_format($featured[0]['views']) ?></span>
      </div>
    </div>
  </div>

  <div class="hero-side">
    <?php foreach (array_slice($featured, 1, 4) as $art): ?>
    <div class="hero-side-card">
      <div class="card-img-wrap">
        <img class="card-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
      </div>
      <div class="card-body">
        <a class="cat-badge" href="<?= url('category', $art['cat_slug']) ?>">
          <?= e($art['cat_name']) ?>
        </a>
        <h3 class="card-title">
          <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
        </h3>
        <div class="article-meta">
          <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- Latest News + Sidebar -->
<?php if (!empty($latest)): ?>
<div class="content-sidebar">
  <div>
    <div class="section-header">
      <h2 class="section-title"><i class="fa fa-newspaper"></i> ताजा समाचार</h2>
      <a href="<?= url('search') ?>" class="section-more">सबै हेर्नुहोस् &rarr;</a>
    </div>
    <div class="article-grid">
      <?php foreach (array_slice($latest, 0, 9) as $art): ?>
      <div class="article-card">
        <div class="card-img-wrap">
          <a href="<?= url('article', $art['slug']) ?>">
            <img class="card-img"
                 src="<?= getImageUrl($art['image'], null, 'medium') ?>"
                 srcset="<?= getImageUrl($art['image'], null, 'small') ?> 300w,
                         <?= getImageUrl($art['image'], null, 'medium') ?> 400w"
                 sizes="(max-width: 768px) 100vw, 400px"
                 alt="<?= e($art['title']) ?>"
                 loading="lazy">
          </a>
        </div>
        <div class="card-body">
          <a class="cat-badge" href="<?= url('category', $art['cat_slug']) ?>">
            <?= e($art['cat_name']) ?>
          </a>
          <h3 class="card-title">
            <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
          </h3>
          <p class="card-excerpt"><?= e($art['excerpt']) ?></p>
          <div class="article-meta">
            <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
            <span class="comment-badge"><i class="fa fa-comment"></i> 0</span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Sidebar -->
  <?php if (!empty($popular)): ?>
  <aside class="sidebar">
    <?php if (!empty($popular)): ?>
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-fire"></i> ट्रेन्डिङ</div>
      <div class="widget-body">
        <div class="popular-list">
          <?php foreach ($popular as $i => $art): ?>
          <div class="popular-item">
            <div class="pop-num <?= $i < 3 ? 'top3' : '' ?>"><?= $i + 1 ?></div>
            <a href="<?= url('article', $art['slug']) ?>">
              <img class="pop-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
            </a>
            <div class="pop-body">
              <a class="pop-title" href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
              <div class="pop-meta"><i class="fa fa-eye"></i> <?= number_format($art['views']) ?> पटक हेरियो</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($cats)): ?>
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-th-list"></i> विभागहरू</div>
      <div class="cat-list">
        <?php foreach ($cats as $cat): ?>
        <a href="<?= url('category', $cat['slug']) ?>" class="cat-list-item">
          <span class="cat-name">
            <span class="cat-dot" style="background:<?= e($cat['color']) ?>"></span>
            <?= e($cat['name']) ?>
          </span>
          <span class="cat-count"><?= totalArticlesByCategory($cat['id']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="ad-widget">
      <?= displayAdvertisement('sidebar', 'medium') ?>
    </div>
  </aside>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Category Sections -->
<?php foreach (array_slice($cats, 0, 8) as $cat): ?>
<?php $cat_arts = getArticlesByCategory($cat['id'], 4); ?>
<?php if (empty($cat_arts)) continue; ?>
<section class="category-section mt-20">
  <div class="section-header">
    <h2 class="section-title"><?= e($cat['name']) ?></h2>
    <a href="<?= url('category', $cat['slug']) ?>" class="section-more">थप हेर्नुहोस् &rarr;</a>
  </div>
  <div class="article-grid-4">
    <?php foreach ($cat_arts as $art): ?>
    <div class="article-card">
      <div class="card-img-wrap">
        <a href="<?= url('article', $art['slug']) ?>">
            <img class="card-img"
                 src="<?= getImageUrl($art['image'], null, 'medium') ?>"
                 srcset="<?= getImageUrl($art['image'], null, 'small') ?> 300w,
                         <?= getImageUrl($art['image'], null, 'medium') ?> 400w"
                 sizes="(max-width: 768px) 100vw, 400px"
                 alt="<?= e($art['title']) ?>"
                 loading="lazy">
        </a>
      </div>
      <div class="card-body">
        <h3 class="card-title">
          <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
        </h3>
        <div class="article-meta">
          <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
