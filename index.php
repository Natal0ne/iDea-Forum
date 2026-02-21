<?php require_once 'includes/init.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDea</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/welcome.css">
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <div class="bg-gradient"></div>

    
    <?php require_once 'includes/sign_up.php' ?>
    <?php require_once 'includes/sign_in.php' ?>
    <?php require_once 'includes/new_thread.php' ?>
    <?php require_once 'includes/navbar.php' ?>
    

    <div class="welcome <?php if ($is_logged) echo "hidden" ?>">
        <h1 id="animatedWelcome">Welcome to iDea</h1>
    </div>
    <div class="content hidden">
            <?php require_once 'includes/content.php' ?>
    </div>

    <?php require_once "includes/contact_us.php"?>
    <?php require_once "includes/footer.php" ?>
   

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/welcome.js"></script>
</body>

</html>
