
<?php require_once 'profile_settings_data.php'; ?>

<div id="profileSettingsModal" class="modal <?php echo $profile_settings_modal_class?>">

 <div class="modal-overlay"></div>
    
    <div class="modal-content">
        
      <div class="close-btn-div">
            <span id='profileSettingsCloseBtn' class="close-btn">&times;</span>
      </div>
    
   
    <h2>Profile settings</h2>
    <div id='profileSettingsErrorMsg'>
        <?php if (!empty($profile_settings_error_message)): ?>
            <div class="error-box">
                <p class="error"><?php echo htmlspecialchars($profile_settings_error_message); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <form action="includes/profile_settings_process.php" method="post">

    <label for = "userid" class="form-label">Username</label>
    <input type = "text"  name = "username"  id = "username" class = "PFInput"
    value = "<?php echo htmlspecialchars($current_username); ?>"/> 

    <label for="bio" id="label-bio" class = "form-label">Bio</label>
    <textarea name="bio" id = "bio" rows="5" placeholder="Write a bio..."><?php echo htmlspecialchars($current_bio); ?></textarea>
    
    <label for="signature" id="label-signature" class = "form-label">Signature</label>
    <input type = "text"  name = "signature" id = "signature" class = "PFInput" placeholder="Enter a signature"        
    value="<?php echo htmlspecialchars($current_signature); ?>"/> 
    
    <label class="form-label">Show Signature</label>
        <span id="showSignature">
            <label for="showSignature-yes" class="radio-option">
                <input type="radio" name="showSignature" id="showSignature-yes" value="Yes">
            <span>Yes</span>
    </label>

    <label for="showSignature-no" class="radio-option">
        <input type="radio" name="showSignature" id="showSignature-no" value="No">
        <span>No</span>
    </label>
    </span>
    <button type="submit" id = "savePfChanges" class = "btn-submit">Salva</button>
    </form>
</div>

