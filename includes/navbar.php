<nav id="navbar">
  <div class="navbar-wrapper" id="navbarWrapper">
    <a class="logo" href="index.php">iDea</a>
    <input class="search-bar" type="text" placeholder="Search discussion...">
    <ul class="nav-links">
      <?php if (!$is_logged): ?>
        <li class="login-btn">
          <a id='navSignInBtn' href="#">Sign in</a>
        </li>
      <?php else: ?>
        <li class="thread-btn">
          <a id='newThreadBtn' href="#">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
              <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
            </svg>
            <p>New thread</p>
          </a>
        </li>
        <li class="user-menu-wrapper">
          <div class="avatar-container" id="userMenuBtn">
            <?php $avatar_path = !empty($post['avatar_url']) ? $post['avatar_url'] : 'assets/img/default-avatar.png'; ?>
            <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Avatar" class="avatar">
          </div>
          <div class="user-dropdown-menu hidden" id="userDropdown">
            <div class="dropdown-header">
              <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
            </div>
            <ul>
              <li><a href="profile_settings.php">Profile Settings</a></li>
              <li><a href="site_settings.php">Site Settings</a></li>
              <li class="divider"></li>
              <li><a href="includes/logout.php" class="logout-link">Logout</a></li>
            </ul>
          </div>
    </div>
    </li>
  <?php endif; ?>
  </ul>
  </div>
</nav>