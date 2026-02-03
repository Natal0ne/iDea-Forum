<?php
require_once 'config.php';

// Logica login modal in caso di errore
$modal_class = "hidden"; 
$error_message = "";

if (isset($_SESSION['login_error'])) {

    $error_message = $_SESSION['login_error'];
    $modal_class = ""; 
    unset($_SESSION['login_error']);

}
?>