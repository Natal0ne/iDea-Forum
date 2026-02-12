
<?php

require_once 'db_connect.php';
session_start();

$name = $_SESSION["username"] ?? "";
$user = $_SESSION["user_id"] ?? null;
$avatar = $_SESSION["avatar_url"] ?? null;
$conn = connect_db(); // connessione

$current_bio = "";
$current_signature = "";
// recupero bio esistente
if ($user) {
    $result = pg_query_params(
        $conn,
         "SELECT bio, signature FROM users WHERE id = $1",
        array($user)
    );

    if ($row = pg_fetch_assoc($result)) {
        $current_bio = $row["bio"] ?? "";
        $current_signature = $row["signature"] ?? "";

    }
}

// salva nuova bio
if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {

    $bio = $_POST["bio"];
    $signature = $_POST["signature"];

    pg_query_params(
        $conn,
        "UPDATE users SET bio = $1, signature = $2 WHERE id = $3",
        array($bio, $signature, $user)
    );

    $current_bio = $bio; // aggiorna subito

    header("Location: ../index.php");
    pg_close($conn);
    exit;
}
?>
<html>
    <head>     
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    </head>
    <body>
    <h1 id="PfSettings">Profile Settings, welcome <?php echo htmlspecialchars($name); ?></h1>

<form id="PfSettingsForm" method="POST" action="profile_settings.php" enctype="multipart/form-data">
    <textarea name="bio" rows="5" placeholder="Write a bio..."><?php echo htmlspecialchars($current_bio); ?></textarea>
    <input type = "text"  name = "signature" id = "signature" placeholder="Enter a signature"        
    value="<?php echo htmlspecialchars($current_signature); ?>"/> 
    <button type="submit">Salva</button>
</form>
</body>
</html>

