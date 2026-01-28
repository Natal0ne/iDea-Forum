<?php
require_once 'config.php';

// Logica HEADER
$is_logged_in = false;
$username_display = "";
$user_initial = "";

// Se l'utente è loggato, sovrascriviamo le variabili con i dati reali
if (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    // Sanitizziamo subito l'username per sicurezza
    $username_display = htmlspecialchars($_SESSION['username']);
    // Calcoliamo l'iniziale
    $user_initial = strtoupper(substr($_SESSION['username'], 0, 1));
}

// Logica MODAL
$modal_class = ""; 
$register_modal_class = "";
$error_message = "";
$register_error_message = "";

if (isset($_SESSION['open_modal'])) {
    if ($_SESSION['open_modal'] === 'login') {
        $modal_class = "active"; 
        
        if (isset($_SESSION['login_error'])) {
            $error_message = $_SESSION['login_error'];
            unset($_SESSION['login_error']); 
        }
    } elseif ($_SESSION['open_modal'] === 'register') {
        $register_modal_class = "active"; // New variable for register modal

        if (isset($_SESSION['register_error'])) {
            $register_error_message = $_SESSION['register_error'];
            unset($_SESSION['register_error']);
        }
    }
    unset($_SESSION['open_modal']);
}
?>