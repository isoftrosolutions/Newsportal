document.addEventListener('DOMContentLoaded', function () {

  // Mobile nav toggle with enhanced accessibility
  var toggle = document.getElementById('navToggle');
  var navList = document.getElementById('navList');
  if (toggle && navList) {
    // Add ARIA attributes
    toggle.setAttribute('aria-label', 'Toggle navigation menu');
    toggle.setAttribute('aria-expanded', 'false');
    navList.setAttribute('aria-hidden', 'true');

    function toggleMenu() {
      var open = navList.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open);
      navList.setAttribute('aria-hidden', !open);

      // Focus management
      if (open) {
        var firstLink = navList.querySelector('a');
        if (firstLink) firstLink.focus();
      }
    }

    // Support both click and touch events
    toggle.addEventListener('click', toggleMenu);
    toggle.addEventListener('touchstart', function(e) {
      e.preventDefault();
      toggleMenu();
    }, { passive: false });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (navList && !navList.contains(e.target) && toggle && !toggle.contains(e.target)) {
        navList.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        navList.setAttribute('aria-hidden', 'true');
      }
    });

    // Close menu on escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && navList.classList.contains('open')) {
        navList.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        navList.setAttribute('aria-hidden', 'true');
        toggle.focus();
      }
    });
  }

  // Scroll-aware sticky nav
  var nav = document.querySelector('.main-nav');
  if (nav) {
    var onScroll = function () {
      nav.classList.toggle('scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // Reading progress bar
  var progressBar = document.getElementById('readingProgress');
  var articleContent = document.querySelector('.article-content');
  if (progressBar && articleContent) {
    var updateProgress = function () {
      var docH   = document.documentElement.scrollHeight - window.innerHeight;
      var scroll = window.scrollY;
      progressBar.style.width = (docH > 0 ? Math.min(100, (scroll / docH) * 100) : 0) + '%';
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = this.parentNode.querySelector('input');
      if (!input) return;
      var isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      var icon = this.querySelector('i');
      if (icon) {
        icon.className = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
      }
      this.setAttribute('aria-label', isPassword ? 'पासवर्ड लुकाउनुहोस्' : 'पासवर्ड देखाउनुहोस्');
    });
  });

  // Share buttons
  document.querySelectorAll('.share-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url   = encodeURIComponent(window.location.href);
      var title = encodeURIComponent(document.title);
      var type  = btn.dataset.share;
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

  // Advertisement click tracking
  window.trackAdClick = function(adId) {
    // Send tracking request asynchronously
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/news/track-ad-click.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('ad_id=' + adId);
  };

  // Enhanced mobile interactions for home page
  if (window.innerWidth <= 768) {
    // Web stories touch scrolling optimization
    var storiesScroll = document.querySelector('.stories-scroll');
    if (storiesScroll) {
      var isScrolling = false;
      var startX, scrollLeft;

      storiesScroll.addEventListener('touchstart', function(e) {
        isScrolling = true;
        startX = e.touches[0].pageX - storiesScroll.offsetLeft;
        scrollLeft = storiesScroll.scrollLeft;
      }, { passive: true });

      storiesScroll.addEventListener('touchmove', function(e) {
        if (!isScrolling) return;
        e.preventDefault();
        var x = e.touches[0].pageX - storiesScroll.offsetLeft;
        var walk = (x - startX) * 2;
        storiesScroll.scrollLeft = scrollLeft - walk;
      }, { passive: false });

      storiesScroll.addEventListener('touchend', function() {
        isScrolling = false;
      });
    }

    // Taja strip mobile scrolling indicator
    var tajaItems = document.querySelector('.tajaa-items');
    if (tajaItems) {
      var scrollIndicator = document.createElement('div');
      scrollIndicator.className = 'scroll-indicator';
      scrollIndicator.innerHTML = '<i class="fa fa-chevron-right"></i>';

      function updateScrollIndicator() {
        var scrollLeft = tajaItems.scrollLeft;
        var scrollWidth = tajaItems.scrollWidth;
        var clientWidth = tajaItems.clientWidth;
        var canScrollRight = scrollLeft < scrollWidth - clientWidth - 10;

        if (canScrollRight && !tajaItems.contains(scrollIndicator)) {
          tajaItems.appendChild(scrollIndicator);
        } else if (!canScrollRight && tajaItems.contains(scrollIndicator)) {
          tajaItems.removeChild(scrollIndicator);
        }
      }

      tajaItems.addEventListener('scroll', updateScrollIndicator, { passive: true });
      updateScrollIndicator();
    }
  }

  // Swipe gesture support for mobile navigation
  var touchStartX = 0;
  var touchStartY = 0;
  var touchEndX = 0;
  var touchEndY = 0;
  var navList = document.getElementById('navList');

  if (navList && window.innerWidth <= 768) {
    navList.addEventListener('touchstart', function(e) {
      touchStartX = e.changedTouches[0].screenX;
      touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    navList.addEventListener('touchend', function(e) {
      touchEndX = e.changedTouches[0].screenX;
      touchEndY = e.changedTouches[0].screenY;
      handleSwipe();
    }, { passive: true });
  }

  function handleSwipe() {
    var deltaX = touchEndX - touchStartX;
    var deltaY = touchEndY - touchStartY;
    var minSwipeDistance = 50;

    // Only handle horizontal swipes
    if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
      if (deltaX < 0) {
        // Swipe left - close menu
        navList.classList.remove('open');
        var toggle = document.getElementById('navToggle');
        if (toggle) {
          toggle.setAttribute('aria-expanded', 'false');
          navList.setAttribute('aria-hidden', 'true');
        }
      }
    }
  }

});
