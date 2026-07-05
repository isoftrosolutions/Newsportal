<?php $categories_footer = getCategories(); ?>

<!-- Back to top FAB -->
<button class="fixed bottom-8 right-8 w-12 h-12 bg-primary text-white rounded-full shadow-lg flex items-center justify-center translate-y-24 opacity-0 transition-all duration-300 z-50" id="back-to-top">
<span class="material-symbols-outlined">arrow_upward</span>
</button>

<!-- Footer -->
<footer class="bg-inverse-surface text-on-secondary pt-stack-lg pb-stack-md mt-stack-lg">
<div class="max-w-7xl mx-auto px-4">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter mb-stack-lg">
<div class="md:col-span-1">
<h2 class="text-headline-md font-headline-md font-bold text-white mb-4"><?= e(BRAND_NAME) ?></h2>
<p class="text-caption opacity-70 mb-6"><?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?><br>सत्य, निष्पक्ष र भरपर्दो समाचार प्रदान गर्न प्रतिबद्ध छौं। नेपाल र विश्वका ताजा समाचार एकै ठाउँमा।</p>
<div class="flex space-x-4">
<a class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors" href="https://www.facebook.com/share/1M6PDqLoPw/" target="_blank">
<span class="material-symbols-outlined text-[20px]">facebook</span>
</a>
<a class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors" href="https://youtube.com/@gtnewsupdate?si=c_kkZsMWlpfSn7o7" target="_blank">
<span class="material-symbols-outlined text-[20px]">play_circle</span>
</a>
<a class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors" href="https://instagram.com/gtnewsnepal" target="_blank">
<span class="material-symbols-outlined text-[20px]">camera</span>
</a>
<a class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors" href="https://www.tiktok.com/@gtnewsnepalupdate" target="_blank">
<span class="material-symbols-outlined text-[20px]">music_note</span>
</a>
</div>
</div>
<div>
<h4 class="font-bold mb-4 uppercase text-label-caps tracking-widest border-b border-white/10 pb-2">विभाग</h4>
<ul class="space-y-2 text-caption opacity-80">
<?php foreach ($categories_footer as $cat): ?>
<li><a class="hover:text-primary transition-colors" href="<?= url('category', $cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<div>
<h4 class="font-bold mb-4 uppercase text-label-caps tracking-widest border-b border-white/10 pb-2">उपयोगी लिङ्क</h4>
<ul class="space-y-2 text-caption opacity-80">
<li><a class="hover:text-primary transition-colors" href="#">हाम्रो बारेमा</a></li>
<li><a class="hover:text-primary transition-colors" href="#">सम्पर्क</a></li>
<li><a class="hover:text-primary transition-colors" href="#">विज्ञापन</a></li>
<li><a class="hover:text-primary transition-colors" href="#">गोपनीयता नीति</a></li>
<li><a class="hover:text-primary transition-colors" href="#">नियम र शर्तहरू</a></li>
<li><a class="hover:text-primary transition-colors" href="<?= SITE_URL ?>/admin/login.php">सम्पादक लगइन</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4 uppercase text-label-caps tracking-widest border-b border-white/10 pb-2">सम्पर्क</h4>
<ul class="space-y-3 text-caption opacity-80">
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-[16px] mt-0.5">location_on</span>
<span>पर्सा (Parsa), Nepal</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-[16px] mt-0.5">call</span>
<span>+977 981-1805681</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-[16px] mt-0.5">person</span>
<span>श्री गौरव यादव - सञ्चालक</span>
</li>
</ul>
<p class="text-caption opacity-50 mt-4">दर्ता नम्बर: 304268/079/080</p>
<p class="text-caption opacity-50">PAN No: 610419238</p>
</div>
</div>
<div class="border-t border-white/10 pt-stack-md flex flex-col md:flex-row justify-between items-center text-caption opacity-60">
<p>&copy; <?= date('Y') ?> <?= e(BRAND_NAME) ?>। सर्वाधिकार सुरक्षित।</p>
<p>Designed for Excellence</p>
</div>
</div>
</footer>

<script>
// Back to top button
(function() {
const fab = document.getElementById('back-to-top');
if (!fab) return;
window.addEventListener('scroll', () => {
if (window.scrollY > 500) {
fab.classList.remove('translate-y-24', 'opacity-0');
fab.classList.add('translate-y-0', 'opacity-100');
} else {
fab.classList.add('translate-y-24', 'opacity-0');
fab.classList.remove('translate-y-0', 'opacity-100');
}
}, { passive: true });
fab.addEventListener('click', () => {
window.scrollTo({ top: 0, behavior: 'smooth' });
});
})();

// Ticker animation pause on hover
const ticker = document.querySelector('.scrolling-ticker');
if (ticker) {
ticker.addEventListener('mouseenter', () => ticker.style.animationPlayState = 'paused');
ticker.addEventListener('mouseleave', () => ticker.style.animationPlayState = 'running');
}
</script>

<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
