<?php
$page_title = 'गतिविधि लग';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 30;
$offset = ($page - 1) * $per_page;

$action_filter = $_GET['action'] ?? '';
$search = trim($_GET['search'] ?? '');

$where = 'WHERE 1=1';
$params = [];
$types = '';

if ($action_filter) {
    $where .= " AND action = ?";
    $params[] = $action_filter;
    $types .= 's';
}
if ($search) {
    $where .= " AND (admin_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

// Total count
$count_stmt = $db->prepare("SELECT COUNT(*) FROM activity_logs $where");
if ($params) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_row()[0];
$total_pages = max(1, ceil($total / $per_page));

// Fetch logs
$sql = "SELECT * FROM activity_logs $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $db->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get distinct actions for filter
$actions = $db->query("SELECT DISTINCT action FROM activity_logs ORDER BY action")->fetch_all(MYSQLI_ASSOC);

// Action labels in Nepali
$action_labels = [
    'login' => 'लगइन',
    'login_failed' => 'गलत लगइन',
    'logout' => 'लगआउट',
    'create_article' => 'समाचार थप',
    'update_article' => 'समाचार सम्पादन',
    'delete_article' => 'समाचार हटाउने',
    'create_category' => 'विभाग थप',
    'update_category' => 'विभाग सम्पादन',
    'delete_category' => 'विभाग हटाउने',
    'create_breaking' => 'ताजा समाचार थप',
    'toggle_breaking' => 'ताजा अवस्था परिवर्तन',
    'delete_breaking' => 'ताजा समाचार हटाउने',
    'create_advertisement' => 'विज्ञापन थप',
    'update_advertisement' => 'विज्ञापन सम्पादन',
    'delete_advertisement' => 'विज्ञापन हटाउने',
];
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3><i class="fa fa-clock-rotate"></i> गतिविधि लग</h3>
    <div class="header-actions">
      <form method="GET" class="filter-form">
        <select name="action" onchange="this.form.submit()">
          <option value="">सबै कार्यहरू</option>
          <?php foreach ($actions as $a): ?>
          <option value="<?= htmlspecialchars($a['action']) ?>" <?= $action_filter === $a['action'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($action_labels[$a['action']] ?? $a['action']) ?>
          </option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="search" placeholder="खोज्नुहोस्..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
        <?php if ($action_filter || $search): ?>
        <a href="activity-logs.php" class="btn btn-sm btn-outline">सफा गर्नुहोस्</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <?php if (empty($logs)): ?>
  <div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
    <i class="fa fa-clock" style="font-size:48px;margin-bottom:16px;opacity:0.3;"></i>
    <p>कुनै गतिविधि लग फेला परेन।</p>
  </div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>प्रशासक</th>
          <th>कार्य</th>
          <th>विवरण</th>
          <th>आईपी</th>
          <th>मिति</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $i => $log): ?>
        <tr>
          <td style="font-family:'IBM Plex Mono',monospace;font-size:12px;color:var(--text-dim);"><?= $offset + $i + 1 ?></td>
          <td><strong><?= htmlspecialchars($log['admin_name']) ?></strong></td>
          <td>
            <span class="status-badge <?= match($log['action']) {
                'login', 'logout' => 'status-pub',
                'login_failed' => 'status-draft',
                'create_article', 'create_category', 'create_breaking', 'create_advertisement' => 'status-pub',
                'delete_article', 'delete_category', 'delete_breaking', 'delete_advertisement' => 'status-draft',
                default => 'status-pub'
            } ?>">
              <?= htmlspecialchars($action_labels[$log['action']] ?? $log['action']) ?>
            </span>
          </td>
          <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            <?= htmlspecialchars($log['description']) ?>
          </td>
          <td style="font-family:'IBM Plex Mono',monospace;font-size:12px;color:var(--text-dim);"><?= htmlspecialchars($log['ip_address']) ?></td>
          <td style="font-size:12px;color:var(--text-dim);white-space:nowrap;">
            <?= date('Y-m-d H:i', strtotime($log['created_at'])) ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total_pages > 1): ?>
  <div style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <div style="font-size:13px;color:var(--text-muted);">जम्मा <?= number_format($total) ?> लग</div>
    <div style="display:flex;gap:6px;">
      <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>&action=<?= urlencode($action_filter) ?>&search=<?= urlencode($search) ?>" class="btn btn-sm btn-outline"><i class="fa fa-chevron-left"></i></a>
      <?php endif; ?>
      <?php for ($p = max(1, $page - 2); $p <= min($total_pages, $page + 2); $p++): ?>
      <a href="?page=<?= $p ?>&action=<?= urlencode($action_filter) ?>&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $p === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
      <a href="?page=<?= $page + 1 ?>&action=<?= urlencode($action_filter) ?>&search=<?= urlencode($search) ?>" class="btn btn-sm btn-outline"><i class="fa fa-chevron-right"></i></a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</div>

<style>
.filter-form {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-wrap: wrap;
}
.filter-form select,
.filter-form input[type="text"] {
  padding: 6px 10px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 13px;
  background: var(--bg-secondary);
  color: var(--text-main);
}
.filter-form input[type="text"] {
  min-width: 180px;
}
.table-responsive {
  overflow-x: auto;
}
</style>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>