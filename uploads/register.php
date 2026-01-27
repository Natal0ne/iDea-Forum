<?php
require_once "../includes/db_connect.php"; // Include connessione

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Assegnamo alle variabili il contenuto di username, email e password (trim serve a non contare gli evenuali spazi)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Controllo eventuali campi vuoti
    if (empty($username) || empty($email) || empty($password)) {
        die("Tutti i campi sono obbligatori");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Cripta la password
    //Effettua la query 
    $sql = "INSERT INTO users (username, email, password_hash)
            VALUES (:username, :email, :password)";

    try {
        //Prepara la query SQL per l'esecuzione
        $stmt = $conn->prepare($sql);
         // Esegue la query sostituendo i placeholder
         // (:username, :email, :password) con i valori reali
        $stmt->execute([
            ":username" => $username,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);

        // Redirect alla pagina index dopo registrazione
        header("Location: index.php");
        exit;
    //Gestione eccezioni
    } catch (PDOException $e) {
        die("Errore: username o email già esistenti");
    }
} 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="Accesso.css">
</head>
<body>
    <h1>Registration</h1>

    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <input type="submit" value="Register">
        <input type="button" class="cancel" value="Annulla">


        <p>Hai già un account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
