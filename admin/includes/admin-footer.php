  </div><!-- .page-container -->
</div><!-- .main-content -->


</div><!-- .admin-wrapper -->

<script>
// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {

  // Elements
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');
  const sidebarOverlay = document.querySelector('.sidebar-overlay');
  const mainContent = document.querySelector('.main-content');
  const adminHeader = document.querySelector('.admin-header');

  function toggleSidebar() {
    sidebar.classList.toggle('show');
    sidebarOverlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', toggleSidebar);
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', toggleSidebar);
  }

  // Close sidebar on mobile when clicking a nav link
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      // Only close sidebar on mobile if this is not a submenu toggle
      if (window.innerWidth < 992 && !this.closest('.nav-link-wrapper')) {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });

  // Handle nav link wrapper clicks (for submenu parents)
  /* Removed - parent links should be navigable */

  // Handle submenu clicks
  document.querySelectorAll('.submenu-link').forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth < 992) {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });

  // Handle collapsed sidebar submenu hover
  function handleSubmenuHover() {
    if (window.innerWidth >= 992) {
      document.querySelectorAll('.nav-item').forEach(item => {
        const submenu = item.querySelector('.submenu');
        const navLink = item.querySelector('.nav-link');

        if (submenu && navLink) {
          item.addEventListener('mouseenter', function() {
            if (sidebar.classList.contains('collapsed')) {
              submenu.style.display = 'block';
            }
          });

          item.addEventListener('mouseleave', function() {
            if (sidebar.classList.contains('collapsed')) {
              submenu.style.display = 'none';
            }
          });
        }
      });
    }
  }

  // Initialize submenu hover behavior
  handleSubmenuHover();

  // Update on resize
  window.addEventListener('resize', function() {
    handleSubmenuHover();
  });

  // Handle submenu toggle icon rotation
  document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(toggle => {
    const icon = toggle.querySelector('.dropdown-toggle-icon');

    if (icon) {
      toggle.addEventListener('click', function() {
        // Bootstrap will handle the collapse, we just need to update the icon
        setTimeout(() => {
          const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
          if (isExpanded) {
            icon.style.transform = 'rotate(180deg)';
          } else {
            icon.style.transform = 'rotate(0deg)';
          }
        }, 10);
      });
    }
  });

  // Dropdown Menus
  const dropdowns = [
    { btn: 'quickActionsBtn', menu: 'quickActionsMenu' },
    { btn: 'notificationsBtn', menu: 'notificationMenu' },
    { btn: 'userMenuBtn', menu: 'userMenu' }
  ];

  dropdowns.forEach(({ btn, menu }) => {
    const button = document.getElementById(btn);
    const menuEl = document.getElementById(menu);

    if (button && menuEl) {
      button.addEventListener('click', function(e) {
        e.stopPropagation();

        // Close other dropdowns
        document.querySelectorAll('.dropdown-menu.show').forEach(el => {
          if (el !== menuEl) {
            el.classList.remove('show');
            el.setAttribute('aria-hidden', 'true');
          }
        });

        // Toggle current dropdown
        const isOpen = menuEl.classList.toggle('show');
        menuEl.setAttribute('aria-hidden', !isOpen);

        // Update button aria-expanded
        button.setAttribute('aria-expanded', isOpen);
      });
    }
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-menu.show').forEach(el => {
      el.classList.remove('show');
      el.setAttribute('aria-hidden', 'true');
    });

    // Reset aria-expanded on buttons
    document.querySelectorAll('[aria-expanded="true"]').forEach(btn => {
      btn.setAttribute('aria-expanded', 'false');
    });
  });

  // Theme Toggle
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;

  function getStoredTheme() {
    return localStorage.getItem('admin-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }

  function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('admin-theme', theme);

    // Update toggle button text
    if (themeToggle) {
      const icon = themeToggle.querySelector('i');
      const text = themeToggle.querySelector('span');
      if (theme === 'dark') {
        icon.className = 'fa fa-sun';
        text.textContent = 'Light Mode';
      } else {
        icon.className = 'fa fa-moon';
        text.textContent = 'Dark Mode';
      }
    }
  }

  // Initialize theme
  setTheme(getStoredTheme());

  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      const currentTheme = html.getAttribute('data-theme');
      setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
  }

  // Mobile Search
  const mobileSearchBar = document.getElementById('mobileSearchBar');
  const mobileSearchClose = document.getElementById('mobileSearchClose');
  const mobileSearchToggle = document.getElementById('mobileSearchToggle');

  if (mobileSearchToggle) {
    mobileSearchToggle.addEventListener('click', function() {
      mobileSearchBar.classList.add('show');
      // Focus the search input when opened
      setTimeout(() => {
        const searchInput = mobileSearchBar.querySelector('.mobile-search-input');
        if (searchInput) searchInput.focus();
      }, 300);
    });
  }

  if (mobileSearchClose) {
    mobileSearchClose.addEventListener('click', function() {
      mobileSearchBar.classList.remove('show');
    });
  }

  // Close mobile search when clicking outside
  if (mobileSearchBar) {
    mobileSearchBar.addEventListener('click', function(e) {
      if (e.target === mobileSearchBar) {
        mobileSearchBar.classList.remove('show');
      }
    });
  }

  // Delete confirmation
  document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      if (!confirm('के तपाईं पक्का हटाउन चाहनुहुन्छ?')) {
        e.preventDefault();
      }
    });
  });

  // Image preview on file select
  var imgInput = document.getElementById('imageFile');
  if (imgInput) {
    imgInput.addEventListener('change', function() {
      var reader = new FileReader();
      reader.onload = function(ev) {
        var prev = document.getElementById('imagePreview');
        if (prev) {
          prev.src = ev.target.result;
          prev.classList.add('show');
          prev.style.display = 'block';
        }
      };
      if (this.files[0]) reader.readAsDataURL(this.files[0]);
    });
  }

  // Stagger-animate table rows
  document.querySelectorAll('.admin-table tbody tr').forEach(function(row, i) {
    row.style.opacity = '0';
    row.style.transform = 'translateY(6px)';
    row.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
    row.style.transitionDelay = (i * 30) + 'ms';
    requestAnimationFrame(function() {
      row.style.opacity = '1';
      row.style.transform = 'translateY(0)';
    });
  });

  // Sidebar Collapse Toggle (Desktop)
  const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');

  if (sidebarCollapseBtn) {
    sidebarCollapseBtn.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('sidebar-collapsed');

      // Update button icon
      const icon = this.querySelector('i');
      if (sidebar.classList.contains('collapsed')) {
        icon.className = 'fa fa-chevron-right';
        this.setAttribute('aria-label', 'Expand sidebar');
      } else {
        icon.className = 'fa fa-chevron-left';
        this.setAttribute('aria-label', 'Collapse sidebar');
      }

      // Store preference
      localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    });
  }

  // Restore sidebar state
  const collapsedState = localStorage.getItem('sidebar-collapsed') === 'true';
  if (collapsedState && window.innerWidth >= 992) {
    sidebar.classList.add('collapsed');
    mainContent.classList.add('sidebar-collapsed');
    sidebarCollapseBtn.querySelector('i').className = 'fa fa-chevron-right';
    sidebarCollapseBtn.title = 'Expand sidebar';
  }

  // Responsive adjustments
  function handleResize() {
    if (window.innerWidth >= 992) {
      sidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
      document.body.style.overflow = '';

      // Show collapse button on desktop (CSS handles this now)
      if (sidebarCollapseBtn) {
        sidebarCollapseBtn.style.display = 'flex';
      }
    } else {
      // Hide collapse button on mobile (CSS handles this now)
      if (sidebarCollapseBtn) {
        sidebarCollapseBtn.style.display = 'none';
      }

      // Remove collapsed state on mobile
      sidebar.classList.remove('collapsed');
      mainContent.classList.remove('sidebar-collapsed');
    }
  }

  window.addEventListener('resize', handleResize);
  handleResize();

});
</script>
</body>
</html>
