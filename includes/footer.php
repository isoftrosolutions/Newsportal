<?php $categories_footer = getCategories(); ?>
</div><!-- .container -->
</main>

<footer class="site-footer">
  <div class="footer-top">
    <div class="container footer-grid">
      <div class="footer-col">
        <h3 class="footer-logo"><?= SITE_NAME ?></h3>
        <p><?= SITE_TAGLINE ?></p>
        <p class="footer-about">हामी सत्य, निष्पक्ष र भरपर्दो समाचार प्रदान गर्न प्रतिबद्ध छौं। नेपाल र विश्वका ताजा समाचार एकै ठाउँमा।</p>
        <div class="footer-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-x-twitter"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>विभाग</h4>
        <ul>
          <?php foreach ($categories_footer as $cat): ?>
          <li><a href="<?= url('category', $cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer-col">
        <h4>उपयोगी लिङ्क</h4>
        <ul>
          <li><a href="#">हाम्रोबारे</a></li>
          <li><a href="#">सम्पर्क</a></li>
          <li><a href="#">विज्ञापन</a></li>
          <li><a href="#">गोपनीयता नीति</a></li>
          <li><a href="#">सेवाका सर्तहरू</a></li>
          <li><a href="<?= SITE_URL ?>/admin/login.php">सम्पादक लगइन</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>सम्पर्क</h4>
        <p><i class="fa fa-map-marker-alt"></i> काठमाडौं, नेपाल</p>
        <p><i class="fa fa-envelope"></i> info@newsportal.com.np</p>
        <p><i class="fa fa-phone"></i> +९७७-०१-XXXXXXX</p>
        <p class="footer-note">Press Council Nepal दर्ता नम्बर: XXXX/XXXX</p>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. सर्वाधिकार सुरक्षित।</p>
    </div>
  </div>
</footer>

<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
