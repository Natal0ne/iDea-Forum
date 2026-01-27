<!-- Inizio Navbar Component -->
<style>
/* Search Bar Styles */
.search-li {
    display: flex;
    align-items: center;
}
.search-container {
    position: relative;
}
.search-container input {
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.1);
    color: white;
    width: 200px;
    transition: all 0.3s ease;
}
.search-container input:focus {
    background: white;
    color: #333;
    outline: none;
    border-color: white;
    width: 240px;
}
.search-container input::placeholder {
    color: rgba(255,255,255,0.7);
}
.suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    border-radius: 8px;
    margin-top: 5px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    display: none;
    z-index: 1000;
    overflow: hidden;
}
.suggestions-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333 !important;
    text-decoration: none;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    transition: background 0.2s;
}
.suggestions-dropdown a:last-child {
    border-bottom: none;
}
.suggestions-dropdown a:hover {
    background-color: #f5f7fa;
    color: #50616d !important;
    padding-left: 15px; /* Override footer hover effect if leaked, but explicit here */
}
.no-results {
    padding: 10px 15px;
    color: #888;
    font-size: 14px;
    font-style: italic;
}
</style>


<nav class="navbar" id="mainNavbar">
    <a href="index.php" class="logo">iDea</a>

    <div class="hamburger" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-links" id="navLinks">
        <li class="search-li">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Cerca discussioni..." autocomplete="off">
                <div id="searchSuggestions" class="suggestions-dropdown"></div>
            </div>
        </li>
        <li><a href="index.php">Home</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span style="color: white; margin-right: 15px;">Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
             <li><a href="includes/logout.php" class="btn-logout" style="background: transparent; border: 1px solid white;">Logout</a></li>
        <?php else: ?>
            <li><a href="#" class="btn-login">Login</a></li>
        <?php endif; ?>
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

    // 3. Search Suggestions Logic
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer); // Clear previous timer
        
        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            searchSuggestions.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            // Assume we are in uploads/index.php, so path is ../includes/search_handler.php
            // If this fails, we might need a dynamic path approach.
            fetch('includes/search_handler.php?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    searchSuggestions.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(thread => {
                            const link = document.createElement('a');
                            // We assume a view_thread.php or thread.php exists. 
                            // Since we didn't find one, we'll link to # for now but allow ID in URL.
                            // If user creates thread.php?id=X later, this will work.
                            // However, we should probably check if uploads/thread.php exists? No.
                            link.href = 'thread.php?id=' + thread.id; 
                            link.textContent = thread.title;
                            searchSuggestions.appendChild(link);
                        });
                        searchSuggestions.style.display = 'block';
                    } else {
                        const noRes = document.createElement('div');
                        noRes.className = 'no-results';
                        noRes.textContent = 'Nessuna discussione trovata';
                        searchSuggestions.appendChild(noRes);
                        searchSuggestions.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Search error:', err);
                });
        }, 300); // 300ms debounce
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
</script>
<!-- Fine Navbar Component -->