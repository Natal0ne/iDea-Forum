<div id="loginModal" class="modal <?php echo $modal_class; ?>">
    
    <div class="modal-overlay"></div>
    
    <div class="modal-content">
        <span id='closeBtn' class="close-btn" data-target="loginModal" onclick="this.closest('.modal').classList.remove('active')">&times;</span>
        
        <h2>Sign in</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert-box error">
                <p class="error-msg"><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <form action="includes/login_process.php" method="post">
            <div class="form-group">
                <label for="login_username">Username/Email</label>
                <input type="text" id="login_username" name="username" required placeholder="Username or Email">
            </div>

            <div class="form-group">
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" required placeholder="Password">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="switch-text">
            No account? 
            <a href="#" id="switchToRegister">Sign up</a>
        </p>
    </div>
</div>
