<?php
require_once __DIR__ . '/config.php';

$page_title = SITE_NAME . ' - ' . SITE_TAGLINE;
$page_desc  = SITE_TAGLINE;

require_once __DIR__ . '/includes/header.php';

$featured  = getFeaturedArticles(3);
$latest    = getLatestArticles(9);
$popular   = getPopularArticles(5);
$cats      = getCategories();
?>

<!-- Hero Section -->
<?php if (!empty($featured)): ?>
<section class="hero-section mb-24">
  <div class="hero-main">
    <img class="hero-img" src="<?= getImageUrl($featured[0]['image']) ?>" alt="<?= e($featured[0]['title']) ?>">
    <div class="hero-overlay">
      <a class="cat-badge" style="background:<?= e($featured[0]['cat_color']) ?>"
         href="<?= url('category', $featured[0]['cat_slug']) ?>">
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
    <?php foreach (array_slice($featured, 1, 2) as $art): ?>
    <div class="hero-side-card">
      <div class="card-img-wrap">
        <img class="card-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>">
      </div>
      <div class="card-body">
        <a class="cat-badge" style="background:<?= e($art['cat_color']) ?>"
           href="<?= url('category', $art['cat_slug']) ?>">
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
<div class="content-sidebar">
  <div>
    <div class="section-header">
      <h2 class="section-title">ताजा समाचार</h2>
      <a href="<?= url('search') ?>" class="section-more">सबै हेर्नुहोस् &rarr;</a>
    </div>
    <div class="article-grid">
      <?php foreach ($latest as $art): ?>
      <div class="article-card">
        <div class="card-img-wrap">
          <a href="<?= url('article', $art['slug']) ?>">
            <img class="card-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>">
          </a>
        </div>
        <div class="card-body">
          <a class="cat-badge" style="background:<?= e($art['cat_color']) ?>"
             href="<?= url('category', $art['cat_slug']) ?>">
            <?= e($art['cat_name']) ?>
          </a>
          <h3 class="card-title">
            <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
          </h3>
          <p class="card-excerpt"><?= e($art['excerpt']) ?></p>
          <div class="article-meta">
            <span><i class="fa fa-user"></i> <?= e($art['author']) ?></span>
            <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-fire"></i> लोकप्रिय समाचार</div>
      <div class="popular-list">
        <?php foreach ($popular as $i => $art): ?>
        <div class="popular-item">
          <div class="pop-num <?= $i < 3 ? 'top3' : '' ?>"><?= $i + 1 ?></div>
          <a href="<?= url('article', $art['slug']) ?>">
            <img class="pop-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>">
          </a>
          <div class="pop-body">
            <a class="pop-title" href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
            <div class="pop-meta"><i class="fa fa-eye"></i> <?= number_format($art['views']) ?> पटक हेरियो</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-list"></i> विभागहरू</div>
      <div class="cat-list">
        <?php foreach ($cats as $cat): ?>
        <a href="<?= url('category', $cat['slug']) ?>" class="cat-list-item">
          <span style="color:white;background:<?= e($cat['color']) ?>"><?= e($cat['name']) ?></span>
          <span><?= totalArticlesByCategory($cat['id']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="sidebar-widget" style="text-align:center;background:#f9f9f9;">
      <div class="ad-placeholder" style="width:100%;padding:40px 0;">विज्ञापन</div>
    </div>
  </aside>
</div>

<!-- Category Sections -->
<?php foreach (array_slice($cats, 0, 6) as $cat): ?>
<?php $cat_arts = getArticlesByCategory($cat['id'], 4); ?>
<?php if (empty($cat_arts)) continue; ?>
<section class="category-section mt-24">
  <div class="section-header">
    <h2 class="section-title">
      <span style="background:<?= e($cat['color']) ?>" class="cat-badge"><?= e($cat['name']) ?></span>
    </h2>
    <a href="<?= url('category', $cat['slug']) ?>" class="section-more">थप हेर्नुहोस् &rarr;</a>
  </div>
  <div class="article-grid-4">
    <?php foreach ($cat_arts as $art): ?>
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
</section>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
