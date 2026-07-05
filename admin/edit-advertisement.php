<?php
$page_title = 'विज्ञापन सम्पादन गर्नुहोस्';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)($_GET['id'] ?? 0);
$ad = getAdvertisementById($id);

if (!$ad) {
    header('Location: advertisements.php');
    exit;
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'image' => trim($_POST['image'] ?? ''),
        'link' => trim($_POST['link'] ?? ''),
        'position' => $_POST['position'] ?? 'sidebar',
        'size' => $_POST['size'] ?? 'medium',
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
    ];

    if (empty($data['title'])) {
        $message = 'विज्ञापनको शीर्षक आवश्यक छ।';
        $message_type = 'error';
    } else {
        if (updateAdvertisement($id, $data)) {
            logActivity('update_advertisement', 'advertisement', $id, 'विज्ञापन सम्पादन: ' . $data['title']);
            $message = 'विज्ञापन सफलतापूर्वक अपडेट गरियो।';
            $message_type = 'success';
            $ad = getAdvertisementById($id); // Refresh data
        } else {
            $message = 'विज्ञापन अपडेट गर्न असफल भयो।';
            $message_type = 'error';
        }
    }
}
?>

<?php if ($message): ?>
<div class="alert alert-<?= $message_type === 'error' ? 'danger' : 'success' ?>">
    <i class="fa fa-<?= $message_type === 'error' ? 'exclamation-triangle' : 'check' ?>"></i>
    <?= e($message) ?>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3>विज्ञापन सम्पादन गर्नुहोस्</h3>
    <a href="advertisements.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> फर्कनुहोस्</a>
  </div>

  <form method="POST" class="admin-form">
    <div class="form-group">
      <label for="title">शीर्षक *</label>
      <input type="text" id="title" name="title" required value="<?= e($_POST['title'] ?? $ad['title']) ?>">
    </div>

    <div class="form-group">
      <label for="image">छवि URL</label>
      <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg" value="<?= e($_POST['image'] ?? $ad['image']) ?>">
      <small class="form-help">विज्ञापनको छविको URL हाल्नुहोस्</small>
      <?php if ($ad['image']): ?>
      <div style="margin-top: 10px;">
        <img src="<?= getImageUrl($ad['image']) ?>" alt="Current image" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd;">
      </div>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label for="link">लिङ्क URL</label>
      <input type="url" id="link" name="link" placeholder="https://example.com" value="<?= e($_POST['link'] ?? $ad['link']) ?>">
      <small class="form-help">विज्ञापन क्लिक गर्दा जाने URL</small>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="position">स्थान</label>
        <select id="position" name="position">
          <option value="sidebar" <?= ($_POST['position'] ?? $ad['position']) === 'sidebar' ? 'selected' : '' ?>>साइडबार</option>
          <option value="header" <?= ($_POST['position'] ?? $ad['position']) === 'header' ? 'selected' : '' ?>>हेडर</option>
          <option value="footer" <?= ($_POST['position'] ?? $ad['position']) === 'footer' ? 'selected' : '' ?>>फुटर</option>
          <option value="inline" <?= ($_POST['position'] ?? $ad['position']) === 'inline' ? 'selected' : '' ?>>इनलाइन</option>
        </select>
      </div>

      <div class="form-group">
        <label for="size">आकार</label>
        <select id="size" name="size">
          <option value="small" <?= ($_POST['size'] ?? $ad['size']) === 'small' ? 'selected' : '' ?>>सानो (200x100)</option>
          <option value="medium" <?= ($_POST['size'] ?? $ad['size']) === 'medium' ? 'selected' : '' ?>>मध्यम (300x250)</option>
          <option value="large" <?= ($_POST['size'] ?? $ad['size']) === 'large' ? 'selected' : '' ?>>ठूलो (300x400)</option>
          <option value="banner" <?= ($_POST['size'] ?? $ad['size']) === 'banner' ? 'selected' : '' ?>>ब्यानर (728x90)</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="sort_order">क्रम संख्या</label>
        <input type="number" id="sort_order" name="sort_order" value="<?= e($_POST['sort_order'] ?? $ad['sort_order']) ?>" min="0">
        <small class="form-help">कम संख्या = उच्च प्राथमिकता</small>
      </div>

      <div class="form-group">
        <label for="is_active">स्थिति</label>
        <div class="checkbox-group">
          <input type="checkbox" id="is_active" name="is_active" <?= isset($_POST['is_active']) || (!isset($_POST) && $ad['is_active']) ? 'checked' : '' ?>>
          <label for="is_active">सक्रिय</label>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="start_date">सुरु मिति</label>
        <input type="date" id="start_date" name="start_date" value="<?= e($_POST['start_date'] ?? $ad['start_date']) ?>">
      </div>

      <div class="form-group">
        <label for="end_date">अन्त्य मिति</label>
        <input type="date" id="end_date" name="end_date" value="<?= e($_POST['end_date'] ?? $ad['end_date']) ?>">
      </div>
    </div>

    <div class="form-group">
      <label>तथ्याङ्क</label>
      <div class="stats-info">
        <span><strong>क्लिक संख्या:</strong> <?= number_format($ad['click_count']) ?></span>
        <span><strong>सिर्जना मिति:</strong> <?= date('Y-m-d H:i', strtotime($ad['created_at'])) ?></span>
        <span><strong>अपडेट मिति:</strong> <?= date('Y-m-d H:i', strtotime($ad['updated_at'])) ?></span>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> अपडेट गर्नुहोस्</button>
      <a href="advertisements.php" class="btn btn-secondary">रद्द गर्नुहोस्</a>
    </div>
  </form>
</div>

<style>
.stats-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.stats-info span {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
}
</style>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?></content>
<parameter name="filePath">admin/edit-advertisement.php