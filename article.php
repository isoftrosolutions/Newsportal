<?php
// 301 redirect if accessed directly with old query-string URL
if (!defined('ROUTED')) {
    require_once __DIR__ . '/config.php';
    $slug = trim($_GET['slug'] ?? '');
    header('Location: ' . SITE_URL . '/article/' . $slug, true, 301);
    exit;
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
    http_response_code(404);
    $page_title = 'समाचार फेला परेन - ' . SITE_NAME;
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="no-results"><i class="fa fa-exclamation-circle"></i><h3>समाचार फेला परेन</h3><p><a href="' . SITE_URL . '/">गृहपृष्ठमा फर्कनुहोस्</a></p></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$article = getArticleBySlug($slug);
if (!$article) {
    http_response_code(404);
    $page_title = 'समाचार फेला परेन - ' . SITE_NAME;
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="no-results"><i class="fa fa-exclamation-circle"></i><h3>समाचार फेला परेन</h3><p><a href="' . SITE_URL . '/">गृहपृष्ठमा फर्कनुहोस्</a></p></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = e($article['title']) . ' - ' . SITE_NAME;
$page_desc  = e($article['excerpt']);
$related    = getRelatedArticles($article['id'], $article['category_id'], 4);
$body_class = 'is-article';

$_word_count    = count(preg_split('/\s+/u', trim(strip_tags($article['content'])), -1, PREG_SPLIT_NO_EMPTY));
$_reading_min   = max(1, (int) ceil($_word_count / 200));

require_once __DIR__ . '/includes/header.php';

// Article Schema Markup
$article_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article['title'],
    'description' => $article['excerpt'],
    'image' => [
        getImageUrl($article['image'])
    ],
    'datePublished' => $article['published_at'],
    'dateModified' => $article['updated_at'] ?? $article['published_at'],
    'author' => [
        '@type' => 'Person',
        'name' => $article['author'] ?? BRAND_NAME
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => BRAND_NAME . ' - ' . SITE_NAME,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => SITE_URL . '/assets/images/gt-logo.png'
        ]
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url('article', $article['slug'])
    ],
    'articleSection' => $article['cat_name'],
    'wordCount' => $_word_count,
    'timeRequired' => 'PT' . $_reading_min . 'M',
    'inLanguage' => 'ne',
    'isAccessibleForFree' => true
];
?>
<script type="application/ld+json">
<?= json_encode($article_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php
// Breadcrumb Schema Markup
$breadcrumb_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'गृहपृष्ठ',
            'item' => SITE_URL . '/'
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $article['cat_name'],
            'item' => url('category', $article['cat_slug'])
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $article['title'],
            'item' => url('article', $article['slug'])
        ]
    ]
];
?>
<script type="application/ld+json">
<?= json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>


<main class="max-w-7xl mx-auto px-4 py-stack-lg">
<nav class="breadcrumb">
  <a href="<?= SITE_URL ?>/">गृहपृष्ठ</a>
  <span class="sep">/</span>
  <a href="<?= url('category', $article['cat_slug']) ?>"><?= e($article['cat_name']) ?></a>
  <span class="sep">/</span>
  <span><?= mb_substr(e($article['title']), 0, 50) ?>...</span>
</nav>

<div class="content-sidebar">
  <div>
    <article class="article-full">
      <a class="cat-badge" href="<?= url('category', $article['cat_slug']) ?>">
        <?= e($article['cat_name']) ?>
      </a>

      <h1 class="article-full-title"><?= e($article['title']) ?></h1>

      <div class="article-full-meta">
        <span><i class="fa fa-user"></i> <?= e($article['author']) ?></span>
        <span><i class="fa fa-calendar"></i> <?= date('Y F j', strtotime($article['published_at'])) ?></span>
        <span><i class="fa fa-clock"></i> <?= timeAgo($article['published_at']) ?></span>
        <span><i class="fa fa-eye"></i> <?= number_format($article['views']) ?> पटक हेरियो</span>
        <span class="reading-time-badge"><i class="fa fa-book-open"></i> <?= $_reading_min ?> मिनेट पढाइ</span>
      </div>

      <?php if ($article['image']): ?>
      <img class="article-featured-img"
           src="<?= getImageUrl($article['image'], null, 'large') ?>"
           srcset="<?= getImageUrl($article['image'], null, 'small') ?> 400w,
                   <?= getImageUrl($article['image'], null, 'medium') ?> 600w,
                   <?= getImageUrl($article['image'], null, 'large') ?> 800w"
           sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 800px"
           alt="<?= e($article['title']) ?>"
           loading="lazy">
      <?php endif; ?>

      <div class="article-content">
        <?php
        $paragraphs = array_filter(array_map('trim', preg_split('/\n{2,}/', $article['content'])));
        foreach ($paragraphs as $p) {
            echo '<p>' . nl2br(e($p)) . '</p>';
        }
        ?>
      </div>

      <div class="article-share">
        <span class="share-label">सेयर गर्नुहोस्:</span>
        <button class="share-btn share-fb" data-share="fb"><i class="fab fa-facebook-f"></i> Facebook</button>
        <button class="share-btn share-tw" data-share="tw"><i class="fab fa-x-twitter"></i> Twitter</button>
        <button class="share-btn share-wa" data-share="wa"><i class="fab fa-whatsapp"></i> WhatsApp</button>
      </div>
    </article>

    <!-- Related Articles -->
    <?php if (!empty($related)): ?>
    <div class="related-section">
      <div class="section-header">
        <h2 class="section-title">सम्बन्धित समाचार</h2>
      </div>
      <div class="article-grid-2">
        <?php foreach ($related as $art): ?>
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
    </div>
    <?php endif; ?>
  </div>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-fire"></i> ट्रेन्डिङ</div>
      <div class="widget-body">
        <div class="popular-list">
          <?php foreach (getPopularArticles(5) as $i => $art): ?>
          <div class="popular-item">
            <div class="pop-num <?= $i < 3 ? 'top3' : '' ?>"><?= $i + 1 ?></div>
            <a href="<?= url('article', $art['slug']) ?>">
              <img class="pop-img"
                   src="<?= getImageUrl($art['image'], null, 'small') ?>"
                   alt="<?= e($art['title']) ?>"
                   loading="lazy">
            </a>
            <div class="pop-body">
              <a class="pop-title" href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
              <div class="pop-meta"><i class="fa fa-eye"></i> <?= number_format($art['views']) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-clock"></i> हालका समाचार</div>
      <div class="widget-body">
        <div class="recent-list">
          <?php foreach (getLatestArticles(5) as $art): ?>
          <div class="recent-item">
            <a href="<?= url('article', $art['slug']) ?>">
              <img class="recent-img"
                   src="<?= getImageUrl($art['image'], null, 'small') ?>"
                   alt="<?= e($art['title']) ?>"
                   loading="lazy">
            </a>
            <div class="recent-body">
              <a class="recent-title" href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
              <div class="recent-meta"><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="ad-widget">
      <div class="ad-placeholder">विज्ञापन</div>
    </div>
  </aside>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
