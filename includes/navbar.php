<nav id="navbar">
  <div class="navbar-wrapper" id="navbarWrapper">
    <a class="logo" href="index.php">iDea</a>
    <ul class="nav-links">
      <?php
          if(!$is_logged): 
      ?>
      <li class="login-btn">
        <a id='navSignInBtn' href="#">Sign in</a>
      </li>
      <?php
        else:
        ?>
      <li class="thread-btn">
        <a id = 'newThreadBtn' href="#">Add New Thread</a>
      </li>
      <li class='avatar'>
        <?php
          if (isset($_SESSION['username'])) {
            echo ($_SESSION['username'][0]);
          }
        ?>
      </li>
      <?php
        endif;
      ?>
    </ul>
  </div>
</nav>