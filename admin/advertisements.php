<?php
$page_title = 'विज्ञापन व्यवस्थापन';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$filter_position = $_GET['position'] ?? '';
$filter_active = $_GET['active'] ?? '';
$search = trim($_GET['q'] ?? '');

$where = ['1=1'];
$params = [];
$types = '';

if ($filter_position) { $where[] = 'position = ?'; $params[] = $filter_position; $types .= 's'; }
if ($filter_active !== '') { $where[] = 'is_active = ?'; $params[] = (int)$filter_active; $types .= 'i'; }
if ($search) { $where[] = 'title LIKE ?'; $params[] = "%{$search}%"; $types .= 's'; }

$where_sql = implode(' AND ', $where);
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$count_stmt = $db->prepare("SELECT COUNT(*) FROM advertisements WHERE $where_sql");
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_row()[0];
$pages = ceil($total / $limit);

$params_p = $params;
$types_p  = $types . 'ii';
$params_p[] = $limit;
$params_p[] = $offset;

$stmt = $db->prepare("SELECT * FROM advertisements WHERE $where_sql ORDER BY sort_order ASC, created_at DESC LIMIT ? OFFSET ?");
if ($types_p) $stmt->bind_param($types_p, ...$params_p);
$stmt->execute();
$ads = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['deleted'])): ?>
<div class="alert alert-success"><i class="fa fa-check"></i> विज्ञापन सफलतापूर्वक हटाइयो।</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3>सबै विज्ञापन (<?= $total ?>)</h3>
    <a href="add-advertisement.php" class="btn btn-primary"><i class="fa fa-plus"></i> नयाँ विज्ञापन</a>
  </div>

  <form method="GET" class="filter-bar">
    <div class="filter-group">
      <input type="text" name="q" placeholder="विज्ञापन खोज्नुहोस्..." value="<?= e($search) ?>">
      <select name="position">
        <option value="">सबै स्थान</option>
        <option value="header" <?= $filter_position === 'header' ? 'selected' : '' ?>>हेडर</option>
        <option value="sidebar" <?= $filter_position === 'sidebar' ? 'selected' : '' ?>>साइडबार</option>
        <option value="footer" <?= $filter_position === 'footer' ? 'selected' : '' ?>>फुटर</option>
        <option value="inline" <?= $filter_position === 'inline' ? 'selected' : '' ?>>इनलाइन</option>
      </select>
      <select name="active">
        <option value="">सबै स्थिति</option>
        <option value="1" <?= $filter_active === '1' ? 'selected' : '' ?>>सक्रिय</option>
        <option value="0" <?= $filter_active === '0' ? 'selected' : '' ?>>निष्क्रिय</option>
      </select>
      <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i> खोज</button>
      <?php if ($search || $filter_position || $filter_active !== ''): ?>
      <a href="advertisements.php" class="btn btn-link">फिल्टर हटाउनुहोस्</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>आईडी</th>
          <th>छवि</th>
          <th>शीर्षक</th>
          <th>स्थान</th>
          <th>आकार</th>
          <th>स्थिति</th>
          <th>क्रम</th>
          <th>क्लिक</th>
          <th>कार्य</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ads as $ad): ?>
        <tr>
          <td><?= $ad['id'] ?></td>
          <td>
            <?php if ($ad['image']): ?>
            <img src="<?= getImageUrl($ad['image']) ?>" alt="<?= e($ad['title']) ?>" style="width: 60px; height: 40px; object-fit: cover;">
            <?php else: ?>
            <span class="text-muted">छवि छैन</span>
            <?php endif; ?>
          </td>
          <td><strong><?= e($ad['title']) ?></strong></td>
          <td>
            <span class="badge badge-<?= $ad['position'] === 'header' ? 'primary' : ($ad['position'] === 'sidebar' ? 'info' : ($ad['position'] === 'footer' ? 'secondary' : 'warning')) ?>">
              <?= $ad['position'] === 'header' ? 'हेडर' : ($ad['position'] === 'sidebar' ? 'साइडबार' : ($ad['position'] === 'footer' ? 'फुटर' : 'इनलाइन')) ?>
            </span>
          </td>
          <td>
            <span class="badge badge-light">
              <?= $ad['size'] === 'small' ? 'सानो' : ($ad['size'] === 'medium' ? 'मध्यम' : ($ad['size'] === 'large' ? 'ठूलो' : 'ब्यानर')) ?>
            </span>
          </td>
          <td>
            <span class="badge badge-<?= $ad['is_active'] ? 'success' : 'danger' ?>">
              <?= $ad['is_active'] ? 'सक्रिय' : 'निष्क्रिय' ?>
            </span>
          </td>
          <td><?= $ad['sort_order'] ?></td>
          <td><?= number_format($ad['click_count']) ?></td>
          <td>
            <a href="edit-advertisement.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> सम्पादन</a>
            <a href="delete-advertisement.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('के तपाईं यस विज्ञापनलाई हटाउन निश्चित हुनुहुन्छ?')"><i class="fa fa-trash"></i> हटाउनुहोस्</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pages > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
    <a href="?page=<?= $page-1 ?>&<?= http_build_query(array_filter(['q' => $search, 'position' => $filter_position, 'active' => $filter_active])) ?>" class="btn btn-secondary">&laquo; अघिल्लो</a>
    <?php endif; ?>

    <span class="page-info">पृष्ठ <?= $page ?> / <?= $pages ?></span>

    <?php if ($page < $pages): ?>
    <a href="?page=<?= $page+1 ?>&<?= http_build_query(array_filter(['q' => $search, 'position' => $filter_position, 'active' => $filter_active])) ?>" class="btn btn-secondary">अर्को &raquo;</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?></content>
<parameter name="filePath">admin/advertisements.php