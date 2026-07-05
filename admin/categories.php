<?php
$page_title = 'विभाग व्यवस्थापन';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$msg = '';

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name  = trim($_POST['name'] ?? '');
        $color = trim($_POST['color'] ?? '#c0392b');
        $order = (int)($_POST['sort_order'] ?? 0);
        if ($name) {
            $sl = slug($name);
            $i = 1;
            $orig = $sl;
            while ($db->query("SELECT id FROM categories WHERE slug='".$db->real_escape_string($sl)."'")->num_rows > 0) {
                $sl = $orig . '-' . $i++;
            }
            $stmt = $db->prepare("INSERT INTO categories (name, slug, color, sort_order) VALUES (?,?,?,?)");
            $stmt->bind_param("sssi", $name, $sl, $color, $order);
            $stmt->execute();
            logActivity('create_category', 'category', $db->insert_id, 'विभाग थपियो: ' . $name);
            $msg = 'success:विभाग सफलतापूर्वक थपियो।';
        }
    } elseif ($_POST['action'] === 'edit') {
        $id    = (int)$_POST['id'];
        $name  = trim($_POST['name'] ?? '');
        $color = trim($_POST['color'] ?? '#c0392b');
        $order = (int)($_POST['sort_order'] ?? 0);
        if ($name && $id) {
            $stmt = $db->prepare("UPDATE categories SET name=?,color=?,sort_order=? WHERE id=?");
            $stmt->bind_param("ssii", $name, $color, $order, $id);
            $stmt->execute();
            logActivity('update_category', 'category', $id, 'विभाग सम्पादन: ' . $name);
            $msg = 'success:विभाग सफलतापूर्वक अपडेट भयो।';
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        if ($id) {
            $cat = $db->query("SELECT name FROM categories WHERE id = $id")->fetch_assoc();
            logActivity('delete_category', 'category', $id, 'विभाग हटाइयो: ' . ($cat['name'] ?? ''));
            $db->query("DELETE FROM categories WHERE id = $id");
            $msg = 'success:विभाग हटाइयो।';
        }
    }
}

$cats = $db->query("SELECT c.*, (SELECT COUNT(*) FROM articles a WHERE a.category_id = c.id) as article_count FROM categories c ORDER BY sort_order ASC")->fetch_all(MYSQLI_ASSOC);
?>

<?php if ($msg): ?>
<?php list($type, $text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type === 'success' ? 'success' : 'error' ?>"><i class="fa fa-check"></i> <?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:20px;">
  <!-- Add Category -->
  <div class="admin-card">
    <div class="admin-card-header"><h3>नयाँ विभाग थप्नुहोस्</h3></div>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label>विभागको नाम *</label>
        <input type="text" name="name" required placeholder="जस्तै: राजनीति">
      </div>
      <div class="form-group">
        <label>रङ</label>
        <input type="color" name="color" value="#c0392b" style="width:60px;height:36px;padding:2px;border:1px solid #ddd;">
      </div>
      <div class="form-group">
        <label>क्रम</label>
        <input type="number" name="sort_order" value="0" min="0">
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> थप्नुहोस्</button>
    </form>
  </div>

  <!-- Category List -->
  <div class="admin-card">
    <div class="admin-card-header"><h3>सबै विभागहरू (<?= count($cats) ?>)</h3></div>
    <table class="admin-table">
      <thead><tr><th>#</th><th>नाम</th><th>Slug</th><th>रङ</th><th>समाचार</th><th>कार्य</th></tr></thead>
      <tbody>
        <?php foreach ($cats as $i => $cat): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <form method="POST" style="display:flex;gap:8px;align-items:center;">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" style="width:120px;padding:4px 8px;border:1px solid #ddd;border-radius:3px;font-family:inherit;">
              <input type="color" name="color" value="<?= htmlspecialchars($cat['color']) ?>" style="width:32px;height:28px;padding:1px;border:1px solid #ddd;">
              <button type="submit" class="btn btn-sm btn-edit" title="सम्पादन"><i class="fa fa-save"></i></button>
            </form>
          </td>
          <td style="font-size:12px;color:#888;"><?= htmlspecialchars($cat['slug']) ?></td>
          <td><span style="background:<?= htmlspecialchars($cat['color']) ?>;color:white;padding:2px 10px;border-radius:20px;font-size:11px;">&nbsp;</span></td>
          <td><?= $cat['article_count'] ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger btn-delete" <?= $cat['article_count'] > 0 ? 'title="समाचार छ, हटाउन सकिँदैन"' : '' ?>><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
