<?php
$page_title = 'नयाँ विज्ञापन थप्नुहोस्';
require_once __DIR__ . '/includes/admin-header.php';

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
        if (createAdvertisement($data)) {
            logActivity('create_advertisement', 'advertisement', 0, 'विज्ञापन थपियो: ' . $data['title']);
            header('Location: advertisements.php?created=1');
            exit;
        } else {
            $message = 'विज्ञापन थप्न असफल भयो।';
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
    <h3>नयाँ विज्ञापन थप्नुहोस्</h3>
    <a href="advertisements.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> फर्कनुहोस्</a>
  </div>

  <form method="POST" class="admin-form">
    <div class="form-group">
      <label for="title">शीर्षक *</label>
      <input type="text" id="title" name="title" required value="<?= e($_POST['title'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="image">छवि URL</label>
      <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg" value="<?= e($_POST['image'] ?? '') ?>">
      <small class="form-help">विज्ञापनको छविको URL हाल्नुहोस्</small>
    </div>

    <div class="form-group">
      <label for="link">लिङ्क URL</label>
      <input type="url" id="link" name="link" placeholder="https://example.com" value="<?= e($_POST['link'] ?? '') ?>">
      <small class="form-help">विज्ञापन क्लिक गर्दा जाने URL</small>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="position">स्थान</label>
        <select id="position" name="position">
          <option value="sidebar" <?= ($_POST['position'] ?? 'sidebar') === 'sidebar' ? 'selected' : '' ?>>साइडबार</option>
          <option value="header" <?= ($_POST['position'] ?? '') === 'header' ? 'selected' : '' ?>>हेडर</option>
          <option value="footer" <?= ($_POST['position'] ?? '') === 'footer' ? 'selected' : '' ?>>फुटर</option>
          <option value="inline" <?= ($_POST['position'] ?? '') === 'inline' ? 'selected' : '' ?>>इनलाइन</option>
        </select>
      </div>

      <div class="form-group">
        <label for="size">आकार</label>
        <select id="size" name="size">
          <option value="small" <?= ($_POST['size'] ?? 'medium') === 'small' ? 'selected' : '' ?>>सानो (200x100)</option>
          <option value="medium" <?= ($_POST['size'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>मध्यम (300x250)</option>
          <option value="large" <?= ($_POST['size'] ?? '') === 'large' ? 'selected' : '' ?>>ठूलो (300x400)</option>
          <option value="banner" <?= ($_POST['size'] ?? '') === 'banner' ? 'selected' : '' ?>>ब्यानर (728x90)</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="sort_order">क्रम संख्या</label>
        <input type="number" id="sort_order" name="sort_order" value="<?= e($_POST['sort_order'] ?? '0') ?>" min="0">
        <small class="form-help">कम संख्या = उच्च प्राथमिकता</small>
      </div>

      <div class="form-group">
        <label for="is_active">स्थिति</label>
        <div class="checkbox-group">
          <input type="checkbox" id="is_active" name="is_active" <?= isset($_POST['is_active']) || !isset($_POST) ? 'checked' : '' ?>>
          <label for="is_active">सक्रिय</label>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="start_date">सुरु मिति</label>
        <input type="date" id="start_date" name="start_date" value="<?= e($_POST['start_date'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="end_date">अन्त्य मिति</label>
        <input type="date" id="end_date" name="end_date" value="<?= e($_POST['end_date'] ?? '') ?>">
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> विज्ञापन थप्नुहोस्</button>
      <a href="advertisements.php" class="btn btn-secondary">रद्द गर्नुहोस्</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?></content>
<parameter name="filePath">admin/add-advertisement.php