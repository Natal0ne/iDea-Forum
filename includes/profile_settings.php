<?php require_once "profile_settings_data.php"; ?>
<!-- Link alla CDN di font awesome (messo qua tanto serve solo per modificare avatar) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div id="profileSettingsModal" class="modal <?php echo $profile_settings_modal_class; ?>">

 <div class="modal-overlay"></div>

    <div class="modal-content">

      <div class="close-btn-div">
            <span id='profileSettingsCloseBtn' class="close-btn">&times;</span>
      </div>

        <h2>Profile settings</h2>
        <div id='profileSettingsErrorMsg'>
            <?php if (!empty($profile_settings_error_message)): ?>
                <div class="error-box">
                    <p class="error"><?php echo htmlspecialchars(
                        $profile_settings_error_message,
                    ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <form action="includes/profile_settings_process.php" method="post" enctype="multipart/form-data">
            <div class="pf-avatar-container">
                <label>Avatar</label>
                <div class="avatar-wrapper" onclick="document.getElementById('avatarInput').click();">
                    <img src="<?php echo htmlspecialchars(
                        $_SESSION["user_avatar_url"],
                    ); ?>"
                    alt="Avatar"
                    id="avatarPreview">

                    <div class="avatar-edit-overlay">
                        <span class="edit-icon"><i class="fa-solid fa-pencil"></i></span>
                    </div>

                </div>

            <!-- input nascosto -->
                <input type="file"
                name="avatar"
                id="avatarInput"
                accept="image/*"
                style="display: none;">
            </div></br>
            <label for = "username" class="form-label">Username</label>
            <input type = "text"  name = "username"  id = "username" class = "PFInput"
            value = "<?php echo htmlspecialchars(
                $_SESSION["username"],
            ); ?>" placeholder="Username"/>

            <label for="bio" id="label-bio" class = "form-label">Bio</label>
            <textarea name="bio" id = "bio" rows="5" placeholder="Write a bio..."><?php echo htmlspecialchars(
                $_SESSION["user_bio"] ?? "",
            ); ?></textarea>

            <label for="signature" id="label-signature" class = "form-label">Signature</label>
            <input type = "text"  name = "signature" id = "signature" class = "PFInput" placeholder="Enter a signature"
            value="<?php echo htmlspecialchars(
                $_SESSION["user_signature"] ?? "",
            ); ?>"/>

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
            <label for="location">Where are you from?</label>
            <input type = "text" name="location" id="location" placeholder="Insert your Country">

            <label for="website">Your site</label>
            <input type = "text" name="website" id="website" placeholder="Insert your website">

            <label for="language">Main Language</label>
            <input type="text" name="language" id="language" placeholder="Insert main language">

            <button type="submit" id = "savePfChanges" class = "btn-submit">Salva</button>
        </form>
    </div>
</div>
