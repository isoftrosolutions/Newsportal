<?php
if (!defined('ROUTED')) {
    require_once __DIR__ . '/config.php';
    http_response_code(404);
}
$page_title = 'पृष्ठ फेला परेन - ' . SITE_NAME;
require_once __DIR__ . '/includes/header.php';
?>
<div class="no-results" style="padding:80px 20px;">
  <i class="fa fa-exclamation-triangle"></i>
  <h3>४०४ - पृष्ठ फेला परेन</h3>
  <p style="margin-top:8px;">तपाईंले खोज्नुभएको पृष्ठ अवस्थित छैन।</p>
  <a href="<?= SITE_URL ?>/" class="btn-home">
    <i class="fa fa-home"></i> गृहपृष्ठमा फर्कनुहोस्
  </a>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
