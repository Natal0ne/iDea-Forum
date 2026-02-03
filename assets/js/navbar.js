/* Navbar scrolling animation */

window.addEventListener('scroll', function () {
  const navbar = document.getElementById('navbarWrapper');
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});