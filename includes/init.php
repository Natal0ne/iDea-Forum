<?php
require_once 'config.php';

// Logica sign_in e sign_up modal in caso di errore
$sign_in_modal_class = "hidden"; 
$sign_in_error_message = "";

$sign_up_modal_class = "hidden"; 
$sign_up_error_message = "";

if (isset($_SESSION['sign_in_error'])) {

    $sign_in_error_message = $_SESSION['sign_in_error'];
    $sign_in_modal_class = ""; 
    unset($_SESSION['sign_in_error']);

} elseif (isset($_SESSION['sign_up_errors'])) {

    $sign_up_error_message = $_SESSION['sign_up_errors'];
    $sign_up_modal_class = ""; 
    unset($_SESSION['sign_up_errors']);
    
}

//Logica sign_in e sign_up a buon fine 
$is_logged = isset($_SESSION['user_id']);

if ($is_logged) {

    $conn = connect_db();

    $query = "UPDATE users SET last_active_at = NOW() WHERE id = $_SESSION[user_id];";

    $result = pg_query($conn, $query);

    pg_close($conn);
}

// Aggiunto non ancora usato errore dalla view threads
if (isset($_SESSION['view_thread_error'])) {

    $view_thread_message = $_SESSION['view_thread_error'];
    unset($_SESSION['view_thread_error']);
}

?>