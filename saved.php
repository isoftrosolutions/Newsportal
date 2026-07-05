<?php
require_once __DIR__ . '/config.php';

$page_title = 'सुरक्षित गरिएका समाचार - ' . SITE_NAME;
$page_desc  = 'तपाईंले सुरक्षित गरिएका समाचारहरू';

require_once __DIR__ . '/includes/header.php';
?>

<main class="max-w-7xl mx-auto px-4 py-stack-lg">
<section>
<div class="flex items-center justify-between mb-stack-md">
<h1 class="text-headline-md font-headline-md border-l-4 border-primary pl-3">सुरक्षित गरिएका समाचार</h1>
</div>
<div id="saved-articles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<div class="md:col-span-full text-center py-16 text-text-muted">
<span class="material-symbols-outlined text-[48px] block mb-4">bookmark</span>
<p class="text-body-lg">तपाईंले कुनै समाचार सुरक्षित गर्नुभएको छैन।</p>
<p class="text-caption mt-2">कुनै पनि समाचारमा रहेको बुकमार्क आइकनमा क्लिक गरेर समाचार सुरक्षित गर्न सक्नुहुन्छ।</p>
</div>
</div>
</section>
</main>

<script>
(function() {
var container = document.getElementById('saved-articles');
if (!container) return;

var saved = JSON.parse(localStorage.getItem('gtnews_saved') || '[]');

if (saved.length === 0) {
container.innerHTML = '<div class="md:col-span-full text-center py-16 text-text-muted">' +
'<span class="material-symbols-outlined text-[48px] block mb-4">bookmark</span>' +
'<p class="text-body-lg">तपाईंले कुनै समाचार सुरक्षित गर्नुभएको छैन।</p>' +
'<p class="text-caption mt-2">कुनै पनि समाचारमा रहेको बुकमार्क आइकनमा क्लिक गरेर समाचार सुरक्षित गर्न सक्नुहुन्छ।</p>' +
'</div>';
return;
}

var html = '';
saved.forEach(function(art) {
html += '' +
'<div class="group bg-background border border-border-subtle rounded-lg overflow-hidden">' +
'<a href="<?= SITE_URL ?>/article/' + art.slug + '" class="no-underline">' +
'<div class="aspect-video overflow-hidden">' +
'<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="' + (art.image || '<?= SITE_URL ?>/assets/images/gt-logo.png') + '" alt="' + art.title + '" loading="lazy">' +
'</div>' +
'<div class="p-4">' +
'<span class="text-label-caps font-label-caps text-primary">' + (art.cat_name || '') + '</span>' +
'<h3 class="text-body-md font-bold mt-1 group-hover:text-primary transition-colors text-on-surface">' + art.title + '</h3>' +
'<p class="text-caption text-text-muted mt-2">' + (art.time || '') + '</p>' +
'</div>' +
'</a>' +
'<div class="px-4 pb-4">' +
'<button class="bookmark-btn text-primary flex items-center gap-1 text-caption font-medium" data-slug="' + art.slug + '" data-title="' + art.title.replace(/"/g, '&quot;') + '" data-image="' + (art.image || '') + '" data-cat="' + (art.cat_name || '') + '" data-time="' + (art.time || '') + '">' +
'<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: \'FILL\' 1;">bookmark</span> हटाउनुहोस्' +
'</button>' +
'</div>' +
'</div>';
});

container.innerHTML = html;

// Handle remove buttons
container.querySelectorAll('.bookmark-btn').forEach(function(btn) {
btn.addEventListener('click', function() {
var slug = btn.dataset.slug;
var saved = JSON.parse(localStorage.getItem('gtnews_saved') || '[]');
saved = saved.filter(function(a) { return a.slug !== slug; });
localStorage.setItem('gtnews_saved', JSON.stringify(saved));
btn.closest('.group').remove();
if (saved.length === 0) {
location.reload();
}
});
});
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
