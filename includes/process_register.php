<?php
session_start();
require_once "db_connect.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":username" => $username,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);

        // Login automatico
        $_SESSION["user_id"] = $conn->lastInsertId();
        $_SESSION["username"] = $username;
        
        // Clear errors
        unset($_SESSION['register_error']);
        unset($_SESSION['open_modal']);

        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['register_error'] = "Username or email already exists";
        $_SESSION['open_modal'] = 'register';
        header("Location: ../index.php");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
