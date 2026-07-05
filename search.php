<?php
// 301 redirect if accessed directly with old URL
if (!defined('ROUTED')) {
    require_once __DIR__ . '/config.php';
    $q = trim($_GET['q'] ?? '');
    header('Location: ' . SITE_URL . '/search' . ($q ? '?q=' . urlencode($q) : ''), true, 301);
    exit;
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$q       = trim($_GET['q'] ?? '');
$results = $q ? searchArticles($q, 30) : [];
$page_title = $q ? '"' . e($q) . '" को खोजी परिणाम - ' . SITE_NAME : 'समाचार खोज्नुहोस् - ' . SITE_NAME;

require_once __DIR__ . '/includes/header.php';
?>

<main class="max-w-7xl mx-auto px-4 py-stack-lg">
<div class="search-header">
  <?php if ($q): ?>
  <h1>"<?= e($q) ?>" को खोजी परिणाम</h1>
  <p><?= count($results) ?> समाचार फेला पर्‍यो</p>
  <?php else: ?>
  <h1>समाचार खोज्नुहोस्</h1>
  <p>माथिको खोजी बक्समा कुनै शब्द टाइप गर्नुहोस्</p>
  <?php endif; ?>
</div>

<?php if ($q && empty($results)): ?>
<div class="no-results">
  <i class="fa fa-search"></i>
  <h3>"<?= e($q) ?>" को लागि कुनै समाचार फेला परेन</h3>
  <p>अर्को शब्द खोज्नुहोस् वा <a href="<?= SITE_URL ?>/">गृहपृष्ठमा</a> फर्कनुहोस्</p>
</div>
<?php elseif (!empty($results)): ?>
<div class="content-sidebar">
  <div>
    <div class="search-grid">
      <?php foreach ($results as $art): ?>
      <div class="search-card">
        <a href="<?= url('article', $art['slug']) ?>">
          <img src="<?= getImageUrl($art['image']) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
        </a>
        <div class="search-card-body">
          <a class="cat-badge" href="<?= url('category', $art['cat_slug']) ?>">
            <?= e($art['cat_name']) ?>
          </a>
          <h2 class="search-card-title">
            <a href="<?= url('article', $art['slug']) ?>"><?= e($art['title']) ?></a>
          </h2>
          <p class="search-card-excerpt"><?= e($art['excerpt']) ?></p>
          <div class="article-meta">
            <span><i class="fa fa-user"></i> <?= e($art['author']) ?></span>
            <span><i class="fa fa-clock"></i> <?= timeAgo($art['published_at']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <aside class="sidebar">
    <div class="sidebar-widget">
      <div class="widget-title"><i class="fa fa-list"></i> विभागहरू</div>
      <div class="cat-list">
        <?php foreach (getCategories() as $cat): ?>
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
  </aside>
</div>
<?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
