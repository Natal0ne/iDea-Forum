<div id="signInModal" class="modal hidden">
    
    <div class="modal-overlay"></div>
    
    <div class="modal-content">
        
        <div class="close-btn-div">
            <span id='signInCloseBtn' class="close-btn" data-target="signInModal" onclick="this.closest('.modal').classList.add('hidden')">&times;</span>
        </div>
        
        <h2>Sign in</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert-box error">
                <p class="error-msg"><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <form action="includes/signIn_process.php" method="post">
            <label for="signIn_username">Username/Email</label>
            <input type="text" id="signIn_username" name="username" required placeholder="Username or Email">
            <label for="signIn_password">Password</label>
            <input type="password" id="signIn_password" name="password" required placeholder="Password">
            <button type="submit" class="btn-submit">Sign in</button>
        </form>

        <p class="switch-text">
            No account? 
            <a href="#" id="signUpBtn">Sign up</a>
        </p>
    </div>
</div>
