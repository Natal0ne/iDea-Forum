<?php

require_once 'config.php';
require_once 'init.php';

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

    $_SESSION['profile_settings_error'] = 'Invalid username, already in use';

    header("Location: ../index.php");
    pg_close($conn);
    exit;
}
?>