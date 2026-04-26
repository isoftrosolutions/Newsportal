<?php
$page_title = 'समाचार सम्पादन';
require_once __DIR__ . '/includes/admin-header.php';

$db   = getDB();
$id   = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: articles.php'); exit; }

$article = $db->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
if (!$article) { header('Location: articles.php'); exit; }

$cats   = getCategories();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $excerpt    = trim($_POST['excerpt'] ?? '');
    $category   = (int)($_POST['category_id'] ?? 0);
    $author     = trim($_POST['author'] ?? 'सम्पादक');
    $status     = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'published';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;

    if (!$title)    $errors[] = 'शीर्षक आवश्यक छ।';
    if (!$content)  $errors[] = 'सामग्री आवश्यक छ।';
    if (!$category) $errors[] = 'विभाग छान्नुहोस्।';

    if (empty($errors)) {
        $image = $article['image'];
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $filename = uniqid('img_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $filename)) {
                    if ($image && file_exists(UPLOAD_PATH . $image)) unlink(UPLOAD_PATH . $image);
                    $image = $filename;
                }
            }
        }

        if (!$excerpt) $excerpt = mb_substr(strip_tags($content), 0, 200);

        $stmt = $db->prepare("UPDATE articles SET title=?,content=?,excerpt=?,category_id=?,image=?,author=?,status=?,is_featured=?,is_breaking=? WHERE id=?");
        $stmt->bind_param("ssssissiii", $title, $content, $excerpt, $category, $image, $author, $status, $is_featured, $is_breaking, $id);

        if ($stmt->execute()) {
            $success = true;
            $article = $db->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
        } else {
            $errors[] = 'Database error: ' . $db->error;
        }
    }
}
?>

<?php if ($success): ?>
<div class="alert alert-success"><i class="fa fa-check"></i> समाचार सफलतापूर्वक अपडेट भयो!</div>
<?php endif; ?>
<?php if ($errors): ?>
<div class="alert alert-error">
  <?php foreach ($errors as $e): ?><div><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="admin-card">
  <form method="POST" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-col-main">
        <div class="form-group">
          <label>समाचारको शीर्षक *</label>
          <input type="text" name="title" required value="<?= htmlspecialchars($article['title']) ?>">
        </div>
        <div class="form-group">
          <label>सामग्री *</label>
          <textarea name="content" rows="16" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>
        <div class="form-group">
          <label>संक्षिप्त विवरण</label>
          <textarea name="excerpt" rows="3"><?= htmlspecialchars($article['excerpt']) ?></textarea>
        </div>
      </div>
      <div class="form-col-side">
        <div class="form-side-card">
          <h4>प्रकाशन सेटिङ</h4>
          <div class="form-group">
            <label>विभाग *</label>
            <select name="category_id" required>
              <?php foreach ($cats as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $article['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>लेखक</label>
            <input type="text" name="author" value="<?= htmlspecialchars($article['author']) ?>">
          </div>
          <div class="form-group">
            <label>अवस्था</label>
            <select name="status">
              <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>प्रकाशित</option>
              <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>ड्राफ्ट</option>
            </select>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_featured" value="1" <?= $article['is_featured'] ? 'checked' : '' ?>>
              Featured (मुख्य पृष्ठ)
            </label>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_breaking" value="1" <?= $article['is_breaking'] ? 'checked' : '' ?>>
              Breaking News
            </label>
          </div>
          <div class="form-group">
            <label>तस्बिर</label>
            <?php if ($article['image']): ?>
            <img src="<?= getImageUrl($article['image']) ?>" id="imagePreview" style="width:100%;border-radius:4px;margin-bottom:8px;">
            <?php else: ?>
            <img id="imagePreview" src="" alt="" style="display:none;width:100%;border-radius:4px;margin-bottom:8px;">
            <?php endif; ?>
            <input type="file" name="image" id="imageFile" accept="image/*">
          </div>
          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-floppy-disk"></i> अपडेट गर्नुहोस्</button>
          <a href="articles.php" class="btn btn-outline btn-block mt-8"><i class="fa fa-arrow-left"></i> फिर्ता जानुहोस्</a>
        </div>
      </div>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
