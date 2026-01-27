<!-- Login Modal -->
<div id="loginModal" class="modal <?php echo (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'login') ? 'active' : ''; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-btn" data-target="loginModal">&times;</span>
        <h2>Sign in</h2>
        
        <?php if (isset($_SESSION['login_error'])): ?>
            <p class="error-msg"><?php echo $_SESSION['login_error']; ?></p>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form action="includes/process_login.php" method="post">
            <div class="form-group">
                <label for="login_username">Username</label>
<<<<<<< Updated upstream
                <!-- Changed ID to avoid conflict with register -->
                <input type="text" id="login_username" name="username" required placeholder="Insert username">
=======
                <input type="text" id="login_username" name="username" required placeholder="Inserisci il tuo username">
>>>>>>> Stashed changes
            </div>

            <div class="form-group">
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" required placeholder="Insert password">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="switch-text">
            First time on iDea? 
            <a href="#" id="switchToRegister">Sign up</a>
        </p>
    </div>
</div>
<?php 
// Clean up open_modal if it was login
if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'login') {
    unset($_SESSION['open_modal']);
}
?>