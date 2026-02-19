<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';


$conn = connect_db();


$thread_id = $_POST['thread_id'];

$query = "SELECT * FROM threads WHERE id = $thread_id";

pg_prepare($conn, 'SelectFromDb', $query);

$res = pg_execute($conn, 'SelectFromDb', array());

$thread = pg_fetch_assoc($res);


$title = $thread['title'];

//$title = $_SESSION['title'];
$query = "UPDATE threads SET title = $1, is_locked = $2 WHERE id = $3";

pg_prepare($conn, 'lockThread', $query);

$res = pg_execute($conn, 'lockThread', array( $title . ' [Deleted By Admin]', true, $thread_id));


if(!$res)
    echo "Impossible to make the thread locked";
if($res)
    echo "Db updated with success";

    header("Location: " . $_SERVER['HTTP_REFERER']);
pg_close($conn);
exit;
?>