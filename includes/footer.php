<?php $categories_footer = getCategories(); ?>
</div><!-- .container -->
</div><!-- .content-main -->
<aside class="content-ad-sidebar right">
  <?= displayAdvertisement('footer', 'medium') ?>
</aside>
</div><!-- .content-with-ads -->
</main>

<footer class="site-footer">
  <div class="footer-top">
    <div class="container footer-grid">
      <div class="footer-col">
        <h3 class="footer-logo"><?= BRAND_NAME ?></h3>
        <p><?= SITE_NAME ?> — <?= SITE_TAGLINE ?></p>
        <p class="footer-about">हामी सत्य, निष्पक्ष र भरपर्दो समाचार प्रदान गर्न प्रतिबद्ध छौं। नेपाल र विश्वका ताजा समाचार एकै ठाउँमा।</p>
        <div class="footer-social">
          <a href="https://www.facebook.com/share/1M6PDqLoPw/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.tiktok.com/@gtnewsnepalupdate" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
          <a href="https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
          <a href="https://instagram.com/gtnewsnepal" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
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
        <p><i class="fa fa-map-marker-alt"></i> पर्सा (Parsa), Nepal</p>
        <p><i class="fa fa-phone"></i> +977 981-1805681</p>
        <p><i class="fa fa-user"></i> श्री गौरव यादव - सञ्चालक</p>
        <p class="footer-note">Press Council Nepal दर्ता नम्बर: 304268/079/080</p>
        <p class="footer-note">PAN No: 610419238</p>
        <div class="footer-social">
          <a href="https://www.facebook.com/share/1M6PDqLoPw/" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.tiktok.com/@gtnewsnepalupdate" target="_blank"><i class="fab fa-tiktok"></i></a>
          <a href="https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7" target="_blank"><i class="fab fa-youtube"></i></a>
          <a href="https://instagram.com/gtnewsnepal" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p>&copy; <?= date('Y') ?> <?= BRAND_NAME ?>. सर्वाधिकार सुरक्षित।</p>
    </div>
  </div>
</footer>

<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
