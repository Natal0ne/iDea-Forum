<?php

$conn = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_profile_settings'])) {    // Serve perchè la variabile POST da sola non basta da che potrebbe prendere il valore di un altro form presente nell'index
    
    $profile_settings_modal_class = ""; //Fa aprire il modale dopo il salvataggio dati

    $form_username = trim($_POST['username'] ?? '');
    $form_bio = trim($_POST['bio'] ?? '');
    $form_signature = trim($_POST['signature'] ?? '');
    $form_show_signatures = $_POST['showSignature'] ?? "No";
    $form_location = trim($_POST['location'] ?? '');
    $form_website = trim($_POST['website'] ?? '');
    $form_language = trim($_POST['language'] ?? '');

    $stmt_name = "update_user";

    $query = "UPDATE users SET username = $1, bio = $2, location = $3, website = $4, signature = $5 WHERE id = $6";

    if(!pg_prepare($conn, $stmt_name, $query)){
        die("Prepare failed: ".pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($form_username, $form_bio, $form_location, $form_website, $form_signature, $_SESSION['user_id']));

    if(!$result){
        $_SESSION['profile_settings_error'] = "Username already used";
        header("Location: ../index.php");

        //die("Execute failed: " . pg_last_error());

    }

    //Inserisci dati nella tabella user_settings se non ci sono
    //Nel caso in cui ci siano allora fai l'update

    $stmt_name = "upsert_user_settings";

    $query = "INSERT INTO user_settings (user_id, language, show_signatures) VALUES ($1, $2, $3)
    ON CONFLICT (user_id) DO UPDATE SET language = EXCLUDED.language, show_signatures = EXCLUDED.show_signatures";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($_SESSION['user_id'], $form_language, $form_show_signatures));

    if (!$result) {
        die("Execute failed: " . pg_last_error());
    }

    //GESTIONE CAMBIO AVATAR

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {

        $targetDir = "../assets/img/";

        if(!is_dir($targetDir)) {
            // Errore nel caso in cui non riesce a creare la cartella uploads
            if(!mkdir($targetDir, 0777, true)) {
                $error_msg = "Impossible creating avatar folder.";
            }
        }

        $fileExtension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid("avatar_", true) . "." . $fileExtension;
        $targetFile = $targetDir . $fileName;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowed)) {

            $check = getimagesize($_FILES["avatar"]["tmp_name"]);

            if ($check !== false) {

                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {

                    $avatarPath = "assets/img/" . $fileName;

                    pg_prepare($conn, "change_img", "UPDATE users SET avatar_url = $1 WHERE id = $2");
                    pg_execute($conn, "change_img", array($avatarPath, $_SESSION['user_id']));

                    $_SESSION['user_avatar_url'] = $avatarPath;
                } else {
                    die("Errore nel salvataggio file");
                }
            }
        }
    }

} else {
        // PRIMO CARICAMENTO: Il form NON è stato inviato, peschiamo i dati dal DB

        $query = "SELECT u.username, u.bio, u.signature, u.location, u.website, us.show_signatures, us.language
                        FROM users u
                        LEFT JOIN user_settings us ON u.id = us.user_id
                        WHERE u.id = {$_SESSION['user_id']}";

        $result = pg_query($conn, $query);

        if ($result && pg_num_rows($result) > 0) {

            $form_data = pg_fetch_assoc($result);

            $form_username = $form_data["username"] ?? "";
            $form_bio = $form_data["bio"] ?? "";
            $form_signature = $form_data["signature"] ?? "";
            $form_show_signatures = $form_data["show_signatures"] ?? "No";
            $form_location = $form_data["location"] ?? "";
            $form_website = $form_data["website"] ?? "";
            $form_language = $form_data["language"] ?? "";
        }

    }

    pg_close($conn);

?>


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
                    <p class="error"><?php echo htmlspecialchars($profile_settings_error_message); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data"> <!-- $_SERVER['PHP_SELF'] non posso usarlo perchè sennò non funzionarebbe per le pagine con url variabile -->
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
                <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display: none;">

            </div>

            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="PFInput" placeholder="Username" value="<?php echo htmlspecialchars($form_username); ?>"/>

            <label for="bio" id="label-bio" class = "form-label">Bio</label>
            <textarea name="bio" id="bio" rows="5" placeholder="Write a bio..."><?php echo htmlspecialchars($form_bio); ?></textarea>

            <label for="signature" id="label-signature" class = "form-label">Signature</label>
            <input type="text" name="signature" id="signature" class="PFInput" placeholder="Enter a signature" value="<?php echo htmlspecialchars($form_signature); ?>"/>

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
            <input type = "text" name="location" id="location" placeholder="Insert your Country" value="<?php echo htmlspecialchars($form_location); ?>">

            <label for="website">Your site</label>
            <input type = "text" name="website" id="website" placeholder="Insert your website" value="<?php echo htmlspecialchars($form_website); ?>">

            <label for="language">Main Language</label>
            <input type="text" name="language" id="language" placeholder="Insert main language" value="<?php echo htmlspecialchars($form_language); ?>">

            <!-- input nascosto -->
            <input type="hidden" name="submit_profile_settings" value="1">

            <button type="submit" id = "savePfChanges" class = "btn-submit">Salva</button>
        </form>
    </div>
</div>
