document.addEventListener('DOMContentLoaded', function () {
  // Mobile nav toggle
  var toggle = document.getElementById('navToggle');
  var navList = document.getElementById('navList');
  if (toggle && navList) {
    toggle.addEventListener('click', function () {
      navList.classList.toggle('open');
    });
  }

  // Close nav on outside click
  document.addEventListener('click', function (e) {
    if (navList && !navList.contains(e.target) && toggle && !toggle.contains(e.target)) {
      navList.classList.remove('open');
    }
  });

  // Share buttons
  document.querySelectorAll('.share-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = encodeURIComponent(window.location.href);
      var title = encodeURIComponent(document.title);
      var type = btn.dataset.share;
      var shareUrl = '';
      if (type === 'fb') shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
      else if (type === 'tw') shareUrl = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title;
      else if (type === 'wa') shareUrl = 'https://wa.me/?text=' + title + '%20' + url;
      if (shareUrl) window.open(shareUrl, '_blank', 'width=600,height=400');
    });
  });

  // Lazy load images
  if ('IntersectionObserver' in window) {
    var lazyImages = document.querySelectorAll('img[data-src]');
    var imgObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          imgObserver.unobserve(img);
        }
      });
    });
    lazyImages.forEach(function (img) { imgObserver.observe(img); });
  }

  // Sticky nav shadow
  var nav = document.querySelector('.main-nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 60) nav.style.boxShadow = '0 4px 16px rgba(0,0,0,0.35)';
      else nav.style.boxShadow = '0 2px 8px rgba(0,0,0,0.3)';
    });
  }
});
