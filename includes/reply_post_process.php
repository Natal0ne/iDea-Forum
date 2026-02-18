<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = connect_db();

    $user_id = $_SESSION["user_id"];

    $thread_id = $_POST["thread_id"];
    $parent_id = $_POST["parent_id"];
    $content = $_POST["reply-content"];
    $thread_slug = $_POST["thread_slug"];

    $stmt_name = "reply_query";

    $reply_query = "INSERT INTO posts (thread_id, user_id, parent_id, content, created_at) VALUES ($1, $2, $3, $4, NOW()) RETURNING id";

    if (!pg_prepare($conn, $stmt_name, $reply_query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($thread_id, $user_id, $parent_id, $content));

    if ($result && pg_num_rows($result) > 0) {

        $reply_count_query = "UPDATE threads SET reply_count = reply_count + 1 WHERE id = $thread_id";

        $result = pg_query($conn, $reply_count_query);

        header("Location: ../view_thread.php?slug=" . $thread_slug);
        pg_close($conn);
        exit;  
    }
    // TODO: DEVE MOSTRARE UN POPUP DI ERRORE CHIEDI RUBEN COME HA GESTITO TRY CATCH
    header("Location: ../index.php");
    pg_close($conn);
    exit;
}

?>