<!-- Inizio Navbar Component -->

<nav class="navbar" id="mainNavbar">
    <a href="index.php" class="logo">iDea</a>

    <div class="hamburger" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-links" id="navLinks">
        <li><a href="index.php">Home</a></li>
        <li><a href="#" class="btn-login">Login</a></li>
    </ul>
</nav>



<script>
    // 1. Gestione Scroll (Trasparenza -> Solido)
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNavbar');
        if (window.scrollY > 50) { // Se scendi di più di 50px
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 2. Gestione Menu Mobile (Hamburger)
    const hamburger = document.getElementById('hamburgerBtn');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active'); // Mostra/Nascondi menu
        hamburger.classList.toggle('active'); // Animazione icona X
        
        // Se apri il menu mobile, la navbar diventa solida per leggibilità
        const navbar = document.getElementById('mainNavbar');
        if (navLinks.classList.contains('active')) {
             navbar.classList.add('scrolled');
        } else if (window.scrollY <= 50) {
             navbar.classList.remove('scrolled');
        }
    });
</script>
<!-- Fine Navbar Component -->