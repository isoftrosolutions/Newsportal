<?php
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)($_GET['id'] ?? 0);
$ad = getAdvertisementById($id);

if (!$ad) {
    header('Location: advertisements.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (deleteAdvertisement($id)) {
        header('Location: advertisements.php?deleted=1');
        exit;
    } else {
        $error = 'विज्ञापन हटाउन असफल भयो।';
    }
}
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3>विज्ञापन हटाउनुहोस्</h3>
    <a href="advertisements.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> फर्कनुहोस्</a>
  </div>

  <?php if (isset($error)): ?>
  <div class="alert alert-danger">
    <i class="fa fa-exclamation-triangle"></i> <?= e($error) ?>
  </div>
  <?php endif; ?>

  <div class="delete-confirmation">
    <div class="delete-icon">
      <i class="fa fa-exclamation-triangle"></i>
    </div>
    <h4>के तपाईं यस विज्ञापनलाई हटाउन निश्चित हुनुहुन्छ?</h4>
    <div class="delete-details">
      <p><strong>शीर्षक:</strong> <?= e($ad['title']) ?></p>
      <p><strong>स्थान:</strong> <?= $ad['position'] === 'header' ? 'हेडर' : ($ad['position'] === 'sidebar' ? 'साइडबार' : ($ad['position'] === 'footer' ? 'फुटर' : 'इनलाइन')) ?></p>
      <p><strong>क्लिक संख्या:</strong> <?= number_format($ad['click_count']) ?></p>
    </div>
    <p class="delete-warning">यो कार्य पूर्ववत गर्न मिल्ने छैन। विज्ञापन र यसका सबै तथ्याङ्क स्थायी रूपमा हटाइनेछन्।</p>

    <form method="POST" style="display: inline;">
      <button type="submit" class="btn btn-danger" onclick="return confirm('के तपाईं पक्का यस विज्ञापनलाई हटाउन चाहनुहुन्छ?')">
        <i class="fa fa-trash"></i> हटाउनुहोस्
      </button>
      <a href="advertisements.php" class="btn btn-secondary">रद्द गर्नुहोस्</a>
    </form>
  </div>
</div>

<style>
.delete-confirmation {
    text-align: center;
    padding: 40px 20px;
}
.delete-icon {
    font-size: 64px;
    color: #dc3545;
    margin-bottom: 20px;
}
.delete-confirmation h4 {
    color: #dc3545;
    margin-bottom: 20px;
}
.delete-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    text-align: left;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}
.delete-details p {
    margin: 8px 0;
}
.delete-warning {
    color: #856404;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    padding: 15px;
    border-radius: 4px;
    margin: 20px 0;
}
</style>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?></content>
<parameter name="filePath">admin/delete-advertisement.php