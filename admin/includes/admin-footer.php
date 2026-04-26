  </div><!-- .admin-content -->
</div><!-- .admin-main -->

<script>
// Delete confirmation
document.querySelectorAll('.btn-delete').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    if (!confirm('के तपाईं पक्का हटाउन चाहनुहुन्छ?')) e.preventDefault();
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
</script>
</body>
</html>
