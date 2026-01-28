<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['register_error'] = "All fields are required";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    } elseif ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    }

    $conn = connect_db();

    // Check if username already exists
    $check_query = "SELECT username FROM users WHERE username = $1 OR email = $2";
    $result = pg_query_params($conn, $check_query, array($username, $email));

    if (pg_num_rows($result) > 0) {
        $_SESSION['register_error'] = "Username or email already exists";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    }

    // Insert new user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt_name = "insert_user";
    $query = "INSERT INTO users (username, email, password_hash) VALUES ($1, $2, $3) RETURNING id";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        // Log error securely
        error_log("Prepare failed: " . pg_last_error());
        $_SESSION['register_error'] = "Registration failed. Please try again.";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    }

    $result = pg_execute($conn, $stmt_name, array($username, $email, $password_hash));

    if ($result) {
        $row = pg_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        // $_SESSION['role'] = 'user'; // Default role if applicable
        
        header("Location: ../index.php");
        exit;
    } else {
         // Log error securely
        error_log("Execute failed: " . pg_last_error());
        $_SESSION['register_error'] = "Registration failed. Please try again.";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
