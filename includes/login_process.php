<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST["username"]);
    $password = $_POST["password"];

    $conn = connect_db();

    $stmt_name = "login_query";
    $query = "SELECT * FROM users WHERE username = $1 OR email = $1";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($username_or_email));

    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../index.php");
            exit;
        }
    }
    $_SESSION['login_error'] = "Invalid username or password.";
    $_SESSION['open_modal'] = 'login';
    header("Location: ../index.php");
    exit;
}
?>
