<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$error_message = "";

if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'login') {
    $modal_class = "active"; 
    
    if (isset($_SESSION['login_error'])) {
        $error_message = $_SESSION['login_error'];
        unset($_SESSION['login_error']); 
    }
    
    unset($_SESSION['open_modal']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDea</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/login.php"; ?>

    <div class="hero-header">
        <h1>Welcome to iDea</h1>
    </div>

    <div class="page-content">
        <p>Bella a tutti ragazzi </p>
    </div>
    
    <?php include "includes/create_thread.php"; ?>

    <?php include "includes/footer.php"; ?>

    <script src="assets/js/script.js"></script>
</body>
</html>
