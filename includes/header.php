<!-- Inizio Navbar Component -->
<nav class="navbar" id="mainNavbar">
    <a href="index.php" class="logo">iDea</a>

    <!-- Unified Flexible Search -->
    <div class="search-center-wrapper">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search discussions..." autocomplete="off">
            <div class="suggestions-dropdown search-suggestions"></div>
        </div>
    </div>

    <ul class="nav-links" id="navLinks">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="create_thread.php" class="btn-create-thread">Create Thread</a></li>
            <li class="user-profile">
                <div class="avatar" id="avatarBtn">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </div>
                    <hr>
                    <a href="account_settings.php">Account Settings</a>
                    <a href="site_settings.php">Site Settings</a>
                    <a href="includes/logout.php" class="logout-link">Logout</a>
                </div>
            </li>
        <?php else: ?>
            <li><a href="#" class="btn-login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    // 1. Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 2. Search Logic
    const searchInput = document.querySelector('.search-input');
    const searchSuggestions = document.querySelector('.search-suggestions');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);
            
            if (query.length < 2) {
                searchSuggestions.style.display = 'none';
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
                    });
            }, 300);
        });
    }

    // 3. User Dropdown & Click Outside
    document.addEventListener('click', function(e) {
        // Search suggestions close
        if (!e.target.closest('.search-container')) {
            if (searchSuggestions) searchSuggestions.style.display = 'none';
        }

        // Avatar dropdown
        const avatarBtn = document.getElementById('avatarBtn');
        const userDropdown = document.getElementById('userDropdown');
        if (avatarBtn && userDropdown) {
            if (avatarBtn.contains(e.target)) {
                userDropdown.classList.toggle('active');
            } else if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        }
    });
</script>
<!-- Fine Navbar Component -->
<!-- Fine Navbar Component -->