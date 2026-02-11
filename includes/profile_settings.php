<?php
require_once 'db_connect.php';
session_start();

$name = $_SESSION["username"] ?? "";
$user = $_SESSION["user_id"] ?? null;
//$avatar = $_SESSION["avatar_url"] ?? null;
$conn = connect_db(); // connessione

$current_bio = "";

// recupero bio esistente
if ($user) {
    $result = pg_query_params(
        $conn,
         "SELECT bio FROM users WHERE id = $1",
        array($user)
    );

    if ($row = pg_fetch_assoc($result)) {
        $current_bio = $row["bio"] ?? "";
    }
}

// salva nuova bio
if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {

    $bio = $_POST["bio"];

    pg_query_params(
        $conn,
        "UPDATE users SET bio = $1 WHERE id = $2",
        array($bio, $user)
    );

    $current_bio = $bio; // aggiorna subito

    header("Location: ../index.php");
    pg_close($conn);
    exit;
}
?>
<html>
<h1 id="PfSettings">Profile Settings, welcome <?php echo htmlspecialchars($name); ?></h1>

<form id="PfSettingsForm" method="POST" action="profile_settings.php">
    <textarea 
        name="bio" 
        id="bio" 
        maxlength="500"
        placeholder="Insert Bio"
    ><?php echo htmlspecialchars($current_bio); ?></textarea><br><br>

    <button id="submitBtn" type="submit"> Save </button>
</form>
</html>

