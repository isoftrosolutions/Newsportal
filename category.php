<?php
// 301 redirect if accessed directly with old query-string URL
if (!defined('ROUTED')) {
    require_once __DIR__ . '/config.php';
    $slug = trim($_GET['slug'] ?? '');
    header('Location: ' . SITE_URL . '/category/' . $slug, true, 301);
    exit;
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: ' . SITE_URL . '/'); exit; }

$category = getCategoryBySlug($slug);
if (!$category) { header('Location: ' . SITE_URL . '/'); exit; }

$page     = max(1, (int)($_GET['page'] ?? 1));
$limit    = 12;
$offset   = ($page - 1) * $limit;
$total    = totalArticlesByCategory($category['id']);
$pages    = ceil($total / $limit);
$articles = getArticlesByCategory($category['id'], $limit, $offset);

$page_title = e($category['name']) . ' - ' . SITE_NAME;
$page_desc  = e($category['name']) . ' विभागका समाचारहरू';

require_once __DIR__ . '/includes/header.php';
?>

<nav class="breadcrumb">
  <a href="<?= SITE_URL ?>/">गृहपृष्ठ</a>
  <span class="sep">/</span>
  <span><?= e($category['name']) ?></span>
</nav>

<div class="content-sidebar">
  <div>
    <div class="section-header">
      <h1 class="section-title"><?= e($category['name']) ?></h1>
      <span style="font-size:11.5px;color:rgba(255,255,255,0.60);font-weight:400;"><?= $total ?> समाचार</span>
    </div>

    <?php if (empty($articles)): ?>
    <div class="no-results">
      <i class="fa fa-newspaper"></i>
      <h3>यस विभागमा समाचार छैन</h3>
    </div>
    <?php else: ?>
    <div class="article-grid">
      <?php foreach ($articles as $art): ?>
      <div class="article-card">
        <div class="card-img-wrap">
          <a href="<?= url('article', $art['slug']) ?>">
            <img class="card-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
          </a>
        </div>
        <div class="card-body">
          <h3 class="card-title">
            <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
          </h3>
          <p class="card-excerpt"><?= e($art['excerpt']) ?></p>
          <div class="article-meta">
            <span><i class="fa fa-user"></i> <?= e($art['author']) ?></span>
            <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
            <span><i class="fa fa-eye"></i> <?= number_format($art['views']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="<?= url('category', $slug, $page - 1) ?>">&laquo;</a>
      <?php endif; ?>
      <?php for ($p = max(1, $page - 2); $p <= min($pages, $page + 2); $p++): ?>
        <?php if ($p == $page): ?>
          <span class="current"><?= $p ?></span>
        <?php else: ?>
          <a href="<?= url('category', $slug, $p) ?>"><?= $p ?></a>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if ($page < $pages): ?>
        <a href="<?= url('category', $slug, $page + 1) ?>">&raquo;</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>

  <aside class="sidebar">
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-fire"></i> लोकप्रिय समाचार</div>
      <div class="widget-body">
        <div class="popular-list">
          <?php foreach (getPopularArticles(5) as $i => $art): ?>
          <div class="popular-item">
            <div class="pop-num <?= $i < 3 ? 'top3' : '' ?>"><?= $i + 1 ?></div>
            <a href="<?= url('article', $art['slug']) ?>">
              <img class="pop-img" src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
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
      <div class="widget-title"><i class="fa fa-list"></i> विभागहरू</div>
      <div class="cat-list">
        <?php foreach (getCategories() as $cat): ?>
        <a href="<?= url('category', $cat['slug']) ?>" class="cat-list-item <?= $cat['slug'] === $slug ? 'active' : '' ?>">
          <span class="cat-name">
            <span class="cat-dot" style="background:<?= e($cat['color']) ?>"></span>
            <?= e($cat['name']) ?>
          </span>
          <span class="cat-count"><?= totalArticlesByCategory($cat['id']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
