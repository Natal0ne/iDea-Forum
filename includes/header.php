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
        <li><a href="login.php" class="btn-login">Login</a></li>
    </ul>
</nav>

<style>
    /* Reset base per la navbar */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Stile della Navbar */
    .navbar {
        position: fixed; /* Fissa in alto */
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 50px;
        transition: background-color 0.3s ease, padding 0.3s ease;
        z-index: 1000; /* Assicura che sia sopra tutto */
        background-color: transparent; /* Inizialmente trasparente */
        color: white;
    }

    /* Classe aggiunta via JS quando si scorre */
    .navbar.scrolled {
        background-color: #222; /* Colore solido scuro */
        padding: 10px 50px; /* Leggermente più compatta */
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* Logo */
    .navbar .logo {
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
        text-decoration: none;
    }

    /* Link e Bottoni */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 20px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links li a {
        text-decoration: none;
        color: white;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .nav-links li a:hover {
        color: #ddd;
    }

    /* Stile specifico per il bottone Login */
    .btn-login {
        border: 2px solid white;
        padding: 8px 20px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background-color: white;
        color: #222 !important;
    }

    /* Menu Hamburger (nascosto su Desktop) */
    .hamburger {
        display: none;
        cursor: pointer;
        flex-direction: column;
        gap: 5px;
    }

    .hamburger span {
        display: block;
        width: 25px;
        height: 3px;
        background-color: white;
        transition: 0.3s;
    }

    /* --- RESPONSIVE (Mobile) --- */
    @media (max-width: 768px) {
        .navbar {
            padding: 15px 20px;
        }
        
        .hamburger {
            display: flex;
        }

        .nav-links {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: #222; /* Sfondo solido per il menu mobile */
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 20px;
            display: none; /* Nascosto di default */
        }

        /* Classe per mostrare il menu */
        .nav-links.active {
            display: flex;
        }
        
        /* Modifiche hamburger quando attivo */
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
    }
</style>

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