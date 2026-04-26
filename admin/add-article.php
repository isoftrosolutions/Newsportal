<?php
$page_title = 'नयाँ समाचार';
require_once __DIR__ . '/includes/admin-header.php';

$cats = getCategories();
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
        $db = getDB();
        $base_slug = slug($title);
        $slug = $base_slug;
        $i = 1;
        while ($db->query("SELECT id FROM articles WHERE slug='".  $db->real_escape_string($slug)."'")->num_rows > 0) {
            $slug = $base_slug . '-' . $i++;
        }

        if (!$excerpt) {
            $excerpt = mb_substr(strip_tags($content), 0, 200);
        }

        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $filename = uniqid('img_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $filename)) {
                    $image = $filename;
                }
            }
        }

        $stmt = $db->prepare("INSERT INTO articles (title, slug, content, excerpt, category_id, image, author, status, is_featured, is_breaking, published_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->bind_param("ssssisssii", $title, $slug, $content, $excerpt, $category, $image, $author, $status, $is_featured, $is_breaking);

        if ($stmt->execute()) {
            if ($is_breaking) {
                $break_text = mb_substr($title, 0, 150);
                $db->prepare("INSERT INTO breaking_news (text) VALUES (?)")->bind_param("s", $break_text);
                $db->prepare("INSERT INTO breaking_news (text) VALUES (?)")->execute();
            }
            $success = true;
        } else {
            $errors[] = 'Database error: ' . $db->error;
        }
    }
}
?>

<?php if ($success): ?>
<div class="alert alert-success"><i class="fa fa-check"></i> समाचार सफलतापूर्वक थपियो! <a href="articles.php">सबै समाचार हेर्नुहोस्</a> | <a href="add-article.php">अर्को थप्नुहोस्</a></div>
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
          <input type="text" name="title" required placeholder="शीर्षक यहाँ लेख्नुहोस्..." value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>सामग्री *</label>
          <textarea name="content" rows="16" required placeholder="समाचारको सामग्री यहाँ लेख्नुहोस्..."><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label>संक्षिप्त विवरण (खाली छोड्नुभयो भने स्वचालित बनाइन्छ)</label>
          <textarea name="excerpt" rows="3" placeholder="संक्षिप्त विवरण..."><?= htmlspecialchars($_POST['excerpt'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="form-col-side">
        <div class="form-side-card">
          <h4>प्रकाशन सेटिङ</h4>
          <div class="form-group">
            <label>विभाग *</label>
            <select name="category_id" required>
              <option value="">— विभाग छान्नुहोस् —</option>
              <?php foreach ($cats as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>लेखक</label>
            <input type="text" name="author" value="<?= htmlspecialchars($_POST['author'] ?? 'सम्पादक') ?>">
          </div>
          <div class="form-group">
            <label>अवस्था</label>
            <select name="status">
              <option value="published" <?= ($_POST['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>प्रकाशित</option>
              <option value="draft" <?= ($_POST['status'] ?? '') === 'draft' ? 'selected' : '' ?>>ड्राफ्ट</option>
            </select>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_featured" value="1" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
              मुख्य पृष्ठमा देखाउनुहोस् (Featured)
            </label>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_breaking" value="1" <?= isset($_POST['is_breaking']) ? 'checked' : '' ?>>
              ताजा समाचार (Breaking News)
            </label>
          </div>
          <div class="form-group">
            <label>तस्बिर</label>
            <input type="file" name="image" id="imageFile" accept="image/*">
            <img id="imagePreview" src="" alt="" style="display:none;width:100%;margin-top:8px;border-radius:4px;">
          </div>
          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-paper-plane"></i> प्रकाशित गर्नुहोस्</button>
          <a href="articles.php" class="btn btn-outline btn-block mt-8"><i class="fa fa-xmark"></i> रद्द गर्नुहोस्</a>
        </div>
      </div>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
