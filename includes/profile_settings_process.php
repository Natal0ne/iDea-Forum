<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

$conn = connect_db();

$user = $_SESSION["user_id"] ?? null;

$current_username = "";
$current_bio = "";
$current_signature = "";
$current_show_signatures = "No";

if ($user) {
    $result = pg_query_params(
        $conn,
        "SELECT u.username, u.bio, u.signature, us.show_signatures
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

if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {

    $username = trim($_POST["username"] ?? "");
    $bio = trim($_POST["bio"] ?? "");
    $signature = trim($_POST["signature"] ?? "");
    $show_signature = $_POST["showSignature"] ?? "No";

    // Controllo username duplicato
    $check = pg_query_params(
        $conn,
        "SELECT id FROM users WHERE username = $1 AND id <> $2",
        array($username, $user)
    );

    if (pg_num_rows($check) > 0) {
        $_SESSION['profile_settings_error'] = 'Username already used';
        header("Location: ../index.php");
        exit;
    }

    pg_query_params(
        $conn,
        "UPDATE users SET username = $1, bio = $2, signature = $3 WHERE id = $4",
        array($username, $bio, $signature, $user)
    );

    pg_query_params(
        $conn,
        "UPDATE user_settings SET show_signatures = $1 WHERE user_id = $2",
        array($show_signature, $user)
    );

    $_SESSION['profile_settings_success'] = 'Profile updated successfully';
    header("Location: ../index.php");
    exit;
}

pg_close($conn);
?>
