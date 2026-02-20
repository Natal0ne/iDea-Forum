<?php

$current_username = "";
$current_bio = "";
$current_signature = "";
$current_show_signatures = "No";

if (isset($_SESSION["user_id"])) {

    $conn = connect_db();

    $result = pg_query_params(
        $conn,
        "SELECT u.username, u.bio, u.signature, us.show_signatures
         FROM users u
         JOIN user_settings us ON u.id = us.user_id
         WHERE u.id = $1",
        array($_SESSION["user_id"])
    );

    if ($row = pg_fetch_assoc($result)) {
        $current_username = $row["username"] ?? "";
        $current_bio = $row["bio"] ?? "";
        $current_signature = $row["signature"] ?? "";
        $current_show_signatures = $row["show_signatures"] ?? "No";
    }

    pg_close($conn);
}

?>