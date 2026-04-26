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
if (!$slug) { header('Location: ' . SITE_URL . '/'); exit; }

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

require_once __DIR__ . '/includes/header.php';
?>

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
      <a class="cat-badge" style="background:<?= e($article['cat_color']) ?>"
         href="<?= url('category', $article['cat_slug']) ?>">
        <?= e($article['cat_name']) ?>
      </a>

      <h1 class="article-full-title"><?= e($article['title']) ?></h1>

      <div class="article-full-meta">
        <span><i class="fa fa-user"></i> <?= e($article['author']) ?></span>
        <span><i class="fa fa-calendar"></i> <?= date('Y-m-d', strtotime($article['published_at'])) ?></span>
        <span><i class="fa fa-clock"></i> <?= timeAgo($article['published_at']) ?></span>
        <span><i class="fa fa-eye"></i> <?= number_format($article['views']) ?> पटक हेरियो</span>
      </div>

      <?php if ($article['image']): ?>
      <img class="article-featured-img" src="<?= getImageUrl($article['image']) ?>" alt="<?= e($article['title']) ?>">
      <?php endif; ?>

      <div class="article-content">
        <?= nl2br(e($article['content'])) ?>
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
              <img class="card-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" style="height:160px;">
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
      <div class="widget-title"><i class="fa fa-fire"></i> लोकप्रिय समाचार</div>
      <div class="popular-list">
        <?php foreach (getPopularArticles(5) as $i => $art): ?>
        <div class="popular-item">
          <div class="pop-num <?= $i < 3 ? 'top3' : '' ?>"><?= $i + 1 ?></div>
          <a href="<?= url('article', $art['slug']) ?>">
            <img class="pop-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>">
          </a>
          <div class="pop-body">
            <a class="pop-title" href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
            <div class="pop-meta"><i class="fa fa-eye"></i> <?= number_format($art['views']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="sidebar-widget" style="text-align:center;background:#f9f9f9;">
      <div class="ad-placeholder" style="width:100%;padding:40px 0;">विज्ञापन</div>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
