<!-- Register Modal -->
<div id="registerModal" class="modal <?php echo $register_modal_class; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-btn" data-target="registerModal" onclick="this.closest('.modal').classList.remove('active')">&times;</span>
        <h2>Sign up</h2>
        
        <?php if (!empty($register_error_message)): ?>
            <div class="alert-box error">
                <p class="error-msg"><?php echo htmlspecialchars($register_error_message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="includes/register_process.php"> 
            
            <div class="form-group">
                <label for="reg_username">Username</label>
                <input type="text" id="reg_username" name="username" required placeholder="Insert username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="reg_email">Email</label>
                <input type="email" id="reg_email" name="email" required placeholder="Insert email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="reg_password">Password</label>
                <input type="password" id="reg_password" name="password" required placeholder="Insert password ">
            </div>

            <div class="form-group">
                <label for="reg_confirm_password">Confirm password</label>
                <input type="password" id="reg_confirm_password" name="confirm_password" required placeholder="Repeat password">
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>

        <p class="switch-text">
            Already on iDea? 
            <a href="#" id="switchToLogin">Sign in</a>
        </p>
    </div>
</div>
<?php 
// Clean up

?>
