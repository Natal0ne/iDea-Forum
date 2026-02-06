/* Navbar scrolling animation */
window.addEventListener('scroll', function () {
  const navbar = document.getElementById('navbarWrapper');
  if (window.scrollY > 30) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const userMenuBtn = document.getElementById('userMenuBtn');
  const dropdown = document.getElementById('userDropdown');

  if(userMenuBtn) {
    userMenuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.remove('hidden');
    });

    document.addEventListener('click', function(e) {
        if(!dropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
    });
  }
});