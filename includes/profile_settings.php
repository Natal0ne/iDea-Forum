
<?php

require_once 'config.php';

//Futuro init?

$change_username_error_message = "";

if(isset($_SESSION['change_username_error'])){
    $change_username_error_message = $_SESSION['change_username_error'];
    unset($_SESSION['change_username_error']);
}

$name = $_SESSION["username"] ?? "";
$user = $_SESSION["user_id"] ?? null;
$conn = connect_db(); 

$current_username = "";
$current_bio = "";
$current_signature = "";
$current_show_signatures = "";

// recupero bio esistente
if ($user) {
    $result = pg_query_params(
        $conn,
        "SELECT  u.username, u.bio, u.signature, us.show_signatures
         FROM users u
         JOIN user_settings us ON u.id = us.user_id
         WHERE u.id = $1",
        array($user)
    );


    if ($row = pg_fetch_assoc($result)) {
        $current_username = $row["username"] ?? "";
        $current_bio = $row["bio"] ?? "";
        $current_signature = $row["signature"] ?? "";
        $current_show_signatures = $row["show_signatures"] ?? "No";
    }
}

// salva nuova bio
if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {
    $username = $_POST["username"];
    $bio = $_POST["bio"];
    $signature = $_POST["signature"];
    $show_signature = $_POST["showSignature"];

    // Controllo se username già usato da un altro utente
    $check = pg_query_params(
    $conn,
    "SELECT id FROM users WHERE username = $1 AND id <> $2",
    array($username, $user)
    );

    if (pg_num_rows($check) > 0) {
         $_SESSION['change_username_error'] = 'Username already used';
        header("Location: profile_settings.php");
        exit;
    }

    pg_query_params(
        $conn,
        "UPDATE users SET username = $1, bio = $2, signature = $3 WHERE id = $4",
        array($username, $bio, $signature, $user)
    );

    $current_bio = $bio; // aggiorna bio
    $current_username = $username; //aggiorna username 
    //Salva show_signature
    pg_query_params(
        $conn,
        "UPDATE user_settings SET show_signatures = $1 WHERE user_id = $2", 
        array($show_signature, $user)
    );
    /*pg_query($conn, "BEGIN");*/

    header("Location: ../index.php");
    pg_close($conn);
    exit;
}
?>
<html>
    <head>     
    <link rel="stylesheet" href="../assets/css/profile_settings.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    </head>
    <body>
    <h1 id="PfSettings">Profile Settings, welcome <?php echo htmlspecialchars($name); ?></h1>

   
    <div id='ChangeUsernameErrorMsg'>
        <?php if (!empty($change_username_error_message)): ?>
            <div class="error-box">
                <p class="error"><?php echo htmlspecialchars($change_username_error_message); ?></p>
            </div>
         <?php endif; ?>
    </div>


<form id="PfSettingsForm" method="POST" action="profile_settings.php" enctype="multipart/form-data">

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
    <button type="submit">Salva</button>
</form>
</body>
</html>

