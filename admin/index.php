<?php
$page_title = 'ड्यासबोर्ड';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$total_articles  = $db->query("SELECT COUNT(*) FROM articles WHERE status='published'")->fetch_row()[0];
$draft_articles  = $db->query("SELECT COUNT(*) FROM articles WHERE status='draft'")->fetch_row()[0];
$total_cats      = $db->query("SELECT COUNT(*) FROM categories")->fetch_row()[0];
$total_views     = $db->query("SELECT SUM(views) FROM articles")->fetch_row()[0] ?? 0;
$recent_articles = $db->query("SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Stat Cards -->
<div class="stats-grid">
  <div class="stat-card" style="--card-color:#e03535;">
    <div class="stat-card-row">
      <div class="stat-icon" style="background:rgba(224,53,53,0.1);color:#e03535;">
        <i class="fa fa-newspaper"></i>
      </div>
    </div>
    <div class="stat-num"><?= number_format($total_articles) ?></div>
    <div class="stat-label">प्रकाशित समाचार</div>
  </div>

  <div class="stat-card" style="--card-color:#f59e0b;">
    <div class="stat-card-row">
      <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
        <i class="fa fa-pen-to-square"></i>
      </div>
    </div>
    <div class="stat-num"><?= number_format($draft_articles) ?></div>
    <div class="stat-label">ड्राफ्ट समाचार</div>
  </div>

  <div class="stat-card" style="--card-color:#22c55e;">
    <div class="stat-card-row">
      <div class="stat-icon" style="background:rgba(34,197,94,0.1);color:#22c55e;">
        <i class="fa fa-tags"></i>
      </div>
    </div>
    <div class="stat-num"><?= number_format($total_cats) ?></div>
    <div class="stat-label">विभागहरू</div>
  </div>

  <div class="stat-card" style="--card-color:#3b82f6;">
    <div class="stat-card-row">
      <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:#3b82f6;">
        <i class="fa fa-eye"></i>
      </div>
    </div>
    <div class="stat-num"><?= number_format($total_views) ?></div>
    <div class="stat-label">कुल हेराइ</div>
  </div>
</div>

<!-- Recent Articles Table -->
<div class="admin-card mt-20">
  <div class="admin-card-header">
    <h3>हालका समाचारहरू</h3>
    <a href="add-article.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> नयाँ समाचार</a>
  </div>
  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th>
        <th>शीर्षक</th>
        <th>विभाग</th>
        <th>अवस्था</th>
        <th>हेराइ</th>
        <th>मिति</th>
        <th>कार्य</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($recent_articles as $i => $art): ?>
      <tr>
        <td style="color:var(--text-dim);font-family:'IBM Plex Mono',monospace;font-size:12px;"><?= $i + 1 ?></td>
        <td>
          <div class="table-title">
            <a href="<?= SITE_URL ?>/article/<?= htmlspecialchars($art['slug']) ?>" target="_blank">
              <?= htmlspecialchars($art['title']) ?>
            </a>
          </div>
        </td>
        <td>
          <span style="font-size:12px;color:var(--text-mid);"><?= htmlspecialchars($art['cat_name']) ?></span>
        </td>
        <td>
          <span class="status-badge <?= $art['status'] === 'published' ? 'status-pub' : 'status-draft' ?>">
            <?= $art['status'] === 'published' ? 'प्रकाशित' : 'ड्राफ्ट' ?>
          </span>
        </td>
        <td style="font-family:'IBM Plex Mono',monospace;font-size:12.5px;color:var(--text-mid);">
          <?= number_format($art['views']) ?>
        </td>
        <td style="font-size:12px;color:var(--text-dim);white-space:nowrap;">
          <?= date('Y-m-d', strtotime($art['created_at'])) ?>
        </td>
        <td style="white-space:nowrap;">
          <a href="edit-article.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-edit"><i class="fa fa-pen"></i></a>
          <a href="delete-article.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-danger btn-delete" style="margin-left:4px;"><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div style="padding:12px 22px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
    <a href="articles.php" class="btn btn-outline btn-sm">सबै समाचार हेर्नुहोस् <i class="fa fa-arrow-right"></i></a>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
