<?php
$page_title = 'ताजा समाचार व्यवस्थापन';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $text = trim($_POST['text'] ?? '');
        if ($text) {
            $stmt = $db->prepare("INSERT INTO breaking_news (text) VALUES (?)");
            $stmt->bind_param("s", $text);
            $stmt->execute();
            $msg = 'success:ताजा समाचार थपियो।';
        }
    } elseif ($action === 'toggle') {
        $id = (int)$_POST['id'];
        $db->query("UPDATE breaking_news SET is_active = 1 - is_active WHERE id = $id");
        $msg = 'success:अवस्था परिवर्तन भयो।';
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $db->query("DELETE FROM breaking_news WHERE id = $id");
        $msg = 'success:हटाइयो।';
    }
}

$items = $db->query("SELECT * FROM breaking_news ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<?php if ($msg): ?>
<?php list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-success"><i class="fa fa-check"></i> <?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
  <div class="admin-card">
    <div class="admin-card-header"><h3>नयाँ ताजा समाचार थप्नुहोस्</h3></div>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label>समाचार पाठ *</label>
        <textarea name="text" rows="4" required placeholder="ताजा समाचार यहाँ लेख्नुहोस्..."></textarea>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-bolt"></i> थप्नुहोस्</button>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header"><h3>ताजा समाचार सूची</h3></div>
    <table class="admin-table">
      <thead><tr><th>#</th><th>पाठ</th><th>अवस्था</th><th>मिति</th><th>कार्य</th></tr></thead>
      <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($item['text']) ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">
              <button type="submit" class="btn btn-sm <?= $item['is_active'] ? 'btn-success' : 'btn-outline' ?>">
                <?= $item['is_active'] ? 'सक्रिय' : 'निष्क्रिय' ?>
              </button>
            </form>
          </td>
          <td><?= date('Y-m-d', strtotime($item['created_at'])) ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
