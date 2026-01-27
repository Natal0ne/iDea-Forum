<?php session_start(); ?>
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
    <?php include "includes/register.php"; ?>

    <div class="hero-header">
        <h1>Welcome on iDea</h1>
    </div>

    <div class="page-content">
        <?php include "includes/feed.php"; ?>
    </div>
    
    <?php include "includes/create_thread.php"; ?>

    <?php include "includes/footer.php"; ?>

    <script src="assets/js/script.js"></script>
</body>
</html>
