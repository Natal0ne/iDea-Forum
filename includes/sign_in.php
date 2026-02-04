<div id="signInModal" class="modal <?php echo $sign_in_modal_class?>">
    
    <div class="modal-overlay"></div>
    
    <div class="modal-content">
        
        <div class="close-btn-div">
            <span id='signInCloseBtn' class="close-btn">&times;</span>
        </div>
        
        <h2>Sign in</h2>

        <div id='signInErrorMsg'>
            <?php if (!empty($sign_in_error_message)): ?>
                <div class="error-box">
                    <p class="error"><?php echo htmlspecialchars($sign_in_error_message); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <form action="includes/sign_in_process.php" method="post">
            <label for="username">Username/Email</label>
            <input type="text" id="username" name="username" required placeholder="Username or Email">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Password">
            <button type="submit" class="btn-submit">Sign in</button>
        </form>

        <p class="switch-text">
            No account? 
            <a href="#" id="signUpBtn">Sign up</a>
        </p>
    </div>
</div>
