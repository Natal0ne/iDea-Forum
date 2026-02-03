<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $conn = connect_db();

    $query = "
        INSERT INTO users (username, email, password_hash)
        VALUES ($1, $2, $3)
    ";

    $result = pg_query_params($conn, $query, [
        $username,
        $email,
        $hash
    ]);

    pg_close($conn);

    if ($result) {
        header("Location: /index.php");
        exit;
    } else {
        header("Location: /register.php?error=1");
        exit;
    }
}