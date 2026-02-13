<nav id="navbar">
  <div class="navbar-wrapper" id="navbarWrapper">
    <a class="logo" href="index.php">iDea</a>
    
    <div class="search-wrapper">
      <form method="GET" id="searchForm">
        <input class="search-bar" id="searchInput" name="q" type="text" placeholder="Search discussion..." autocomplete="off">
      </form>
      <div id="searchSuggestions" class="search-dropdown hidden"></div>
    </div>

    <ul class="nav-links">
      <?php if (!$is_logged): ?>
        <li class="login-btn"><a id='navSignInBtn' href="#">Sign in</a></li>
      <?php else: ?>
        <li class="thread-btn">
          <a id='newThreadBtn' href="#">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" /></svg>
            <p>New thread</p>
          </a>
        </li>
        <li class="user-menu-wrapper">
          <div class="avatar-container" id="userMenuBtn">
            <img src="<?php echo htmlspecialchars($_SESSION['user_avatar_url']); ?>" alt="Avatar" class="avatar">
          </div>
          <div class="user-dropdown-menu hidden" id="userDropdown">
            <div class="dropdown-header">
              <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
            </div>
            <ul>
              <li><a href="includes/profile_settings.php">Profile Settings</a></li>
              <li><a href="site_settings.php">Site Settings</a></li>
              <li class="divider"></li>
              <li><a href="includes/logout.php" class="logout-link">Logout</a></li>
            </ul>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>