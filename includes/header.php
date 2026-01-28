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
        <?php if ($is_logged_in): ?>
            <!-- Utente Loggato -->
            <li>
                <a href="includes/create_thread.php" id="createThreadBtn" class="btn-create-thread">
                    <svg width="18" height="18" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <path d="M19 9.375h-8.375V1h-1.25v8.375H1v1.25h8.375V19h1.25v-8.375H19v-1.25Z"></path>
                    </svg>
                    <span class="btn-text">Create</span>
                </a>
            </li>
            <li class="user-profile">
                <div class="avatar" id="avatarBtn">
                    <?php echo $user_initial; ?>
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <strong><?php echo $username_display; ?></strong>
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

    // 4. Create Thread Modal Trigger
    const createBtn = document.getElementById('createThreadBtn');
    if (createBtn) {
        createBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.getElementById('createThreadModal');
            if (modal) {
                modal.classList.add('active');
            }
        });
    }
    
    // Generic Modal Close
    document.querySelectorAll('.close-btn, .modal-overlay').forEach(el => {
        el.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            if (target) {
                document.getElementById(target).classList.remove('active');
            } else {
                this.closest('.modal').classList.remove('active');
            }
        });
    });
</script>
<!-- Fine Navbar Component -->