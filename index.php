<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDea</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
</head>
<body>
    <?php include "includes/navbar.php"; ?>
    <?php include "includes/login.php"; ?>
    <!-- <?php include "includes/register.php"; ?> -->

    <div class="header">
        <h1 class="bitcount-single">Welcome to iDea</h1>
    </div>

    <div class="">
        <p>Bella a tutti ragazzi </p>
    </div>

    <?php include "includes/footer.php"; ?>
</body>
</html>
