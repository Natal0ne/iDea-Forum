<!-- Inizio Navbar Component -->
<nav class="navbar" id="mainNavbar">
    <a href="index.php" class="logo">iDea</a>

    <!-- Desktop Search (Centered) -->
    <div class="search-desktop desktop-only">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search discussions..." autocomplete="off">
            <div class="suggestions-dropdown search-suggestions"></div>
        </div>
    </div>

    <div class="hamburger" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-links" id="navLinks">
        <!-- Mobile Search (Inside Menu) -->
        <li class="search-li mobile-only">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search discussions..." autocomplete="off">
                <div class="suggestions-dropdown search-suggestions"></div>
            </div>
        </li>
        <li><a href="index.php">Home</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span style="color: white; margin-right: 15px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
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

    // 3. Search Suggestions Logic (Updated for Multiple Inputs)
    const searchInputs = document.querySelectorAll('.search-input');

    searchInputs.forEach(searchInput => {
        // Find the suggestions dropdown RELATIVE to the input
        const searchContainer = searchInput.closest('.search-container');
        const searchSuggestions = searchContainer.querySelector('.suggestions-dropdown');
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
                fetch('includes/search_handler.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        searchSuggestions.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(thread => {
                                const link = document.createElement('a');
                                link.href = 'thread.php?id=' + thread.id; 
                                link.textContent = thread.title;
                                searchSuggestions.appendChild(link);
                            });
                            searchSuggestions.style.display = 'block';
                        } else {
                            const noRes = document.createElement('div');
                            noRes.className = 'no-results';
                            noRes.textContent = 'No discussion found';
                            searchSuggestions.appendChild(noRes);
                            searchSuggestions.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                    });
            }, 300); // 300ms debounce
        });
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container')) {
             document.querySelectorAll('.suggestions-dropdown').forEach(el => {
                 el.style.display = 'none';
             });
        }
    });
</script>
<!-- Fine Navbar Component -->