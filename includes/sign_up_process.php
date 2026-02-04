<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);
    

    $conn = connect_db();
    $stmt_name = "signup_query";

    $query = "
        INSERT INTO users (username, email, password_hash)
        VALUES ($1, $2, $3)
    ";

     if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($username, $email, $hash));

    pg_close($conn);

    if ($result) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];       
        header("Location: /index.php");
        exit;
    }
    $_SESSION['sign_up_error'] = "Invalid username or password.";
    header(header: "Location: ../index.php");
    exit;
}