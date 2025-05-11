<script>
document.addEventListener('DOMContentLoaded', function () {
  const userName = document.querySelector('.user-name');
  const dropdown = document.querySelector('.dropdown-menu');

  if (userName) {
    userName.addEventListener('click', () => {
      dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
      if (!userName.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  }
});
</script>
