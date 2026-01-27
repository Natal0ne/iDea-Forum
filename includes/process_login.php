<?php
session_start();
require_once "db_connect.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Enter username and password";
        $_SESSION['open_modal'] = 'login';
        header("Location: ../index.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":username" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        
        // Clear error flags
        unset($_SESSION['login_error']);
        unset($_SESSION['open_modal']);

        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Wrong username or password";
        $_SESSION['open_modal'] = 'login';
        header("Location: ../index.php");
        exit;
    }
} else {
    // If accessed directly without POST, go home
    header("Location: ../index.php");
    exit;
}
?>
