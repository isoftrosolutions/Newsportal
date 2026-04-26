<?php
$page_title = 'समाचार व्यवस्थापन';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$filter_cat = (int)($_GET['cat'] ?? 0);
$filter_status = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');

$where = ['1=1'];
$params = [];
$types = '';

if ($filter_cat) { $where[] = 'a.category_id = ?'; $params[] = $filter_cat; $types .= 'i'; }
if ($filter_status) { $where[] = 'a.status = ?'; $params[] = $filter_status; $types .= 's'; }
if ($search) { $where[] = 'a.title LIKE ?'; $params[] = "%{$search}%"; $types .= 's'; }

$where_sql = implode(' AND ', $where);
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$count_stmt = $db->prepare("SELECT COUNT(*) FROM articles a WHERE $where_sql");
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_row()[0];
$pages = ceil($total / $limit);

$params_p = $params;
$types_p  = $types . 'ii';
$params_p[] = $limit;
$params_p[] = $offset;

$stmt = $db->prepare("SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE $where_sql ORDER BY a.created_at DESC LIMIT ? OFFSET ?");
if ($types_p) $stmt->bind_param($types_p, ...$params_p);
$stmt->execute();
$articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$cats = getCategories();

if (isset($_GET['deleted'])): ?>
<div class="alert alert-success"><i class="fa fa-check"></i> समाचार सफलतापूर्वक हटाइयो।</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3>सबै समाचार (<?= $total ?>)</h3>
    <a href="add-article.php" class="btn btn-primary"><i class="fa fa-plus"></i> नयाँ समाचार</a>
  </div>

  <form method="GET" class="filter-bar">
    <input type="text" name="q" placeholder="शीर्षक खोज्नुहोस्..." value="<?= htmlspecialchars($search) ?>">
    <select name="cat">
      <option value="">सबै विभाग</option>
      <?php foreach ($cats as $cat): ?>
      <option value="<?= $cat['id'] ?>" <?= $filter_cat == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="status">
      <option value="">सबै अवस्था</option>
      <option value="published" <?= $filter_status === 'published' ? 'selected' : '' ?>>प्रकाशित</option>
      <option value="draft" <?= $filter_status === 'draft' ? 'selected' : '' ?>>ड्राफ्ट</option>
    </select>
    <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i> खोज्नुहोस्</button>
    <a href="articles.php" class="btn btn-outline">रिसेट</a>
  </form>

  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th><th>शीर्षक</th><th>विभाग</th><th>फिचर्ड</th><th>अवस्था</th><th>हेराइ</th><th>मिति</th><th>कार्य</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($articles)): ?>
      <tr><td colspan="8" style="text-align:center;color:#999;padding:30px;">कुनै समाचार फेला परेन</td></tr>
      <?php endif; ?>
      <?php foreach ($articles as $i => $art): ?>
      <tr>
        <td><?= $offset + $i + 1 ?></td>
        <td><a href="<?= SITE_URL ?>/article.php?slug=<?= htmlspecialchars($art['slug']) ?>" target="_blank"><?= mb_substr(htmlspecialchars($art['title']), 0, 55) ?>…</a></td>
        <td><?= htmlspecialchars($art['cat_name']) ?></td>
        <td><?= $art['is_featured'] ? '<i class="fa fa-star" style="color:#f39c12"></i>' : '-' ?></td>
        <td><span class="status-badge <?= $art['status'] === 'published' ? 'status-pub' : 'status-draft' ?>"><?= $art['status'] === 'published' ? 'प्रकाशित' : 'ड्राफ्ट' ?></span></td>
        <td><?= number_format($art['views']) ?></td>
        <td><?= date('Y-m-d', strtotime($art['created_at'])) ?></td>
        <td style="white-space:nowrap;">
          <a href="edit-article.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-edit"><i class="fa fa-edit"></i></a>
          <a href="delete-article.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if ($pages > 1): ?>
  <div class="admin-pagination">
    <?php for ($p = 1; $p <= $pages; $p++): ?>
    <a href="?page=<?= $p ?>&q=<?= urlencode($search) ?>&cat=<?= $filter_cat ?>&status=<?= $filter_status ?>"
       class="<?= $p == $page ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
