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

} elseif (isset($_SESSION['sign_up_error'])) {

    $sign_up_error_message = $_SESSION['sign_up_error'];
    $sign_up_modal_class = ""; 
    unset($_SESSION['sign_up_error']);
    
}
//Logica sign_in e sign_up a buon fine 
$is_logged = isset($_SESSION['user_id']);
?>