<!-- Inizio Navbar Component -->
<nav class="navbar" id="mainNavbar">
    <a href="index.php" class="logo">iDea</a>

    <!-- Unified Flexible Search -->
    <div class="search-center-wrapper">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search discussions..." autocomplete="off">
            <div class="suggestions-dropdown" id="search-suggestions"></div>
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

<script src="assets/js/header.js"></script>
