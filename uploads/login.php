<?php
session_start(); // Serve per memorizzare l'utente loggato
require_once "../includes/db_connect.php"; // Include la connessione al DB

//Se la pagina è stata chiamata tramite una richiesta POST 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Assegna il contenuto di username e password alle due variabili
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    //Se almeno una delle due variabili è vuota
    if (empty($username) || empty($password)) {
        die("Inserisci username e password");
    }

    //Recupera utente dal DB
    $sql = "SELECT * FROM users WHERE username = :username";
    //Prepara la query
    $stmt = $conn->prepare($sql);
    //Esegui la query --> sostituisce :username con il contenuto della variabile $username
    $stmt->execute([":username" => $username]);
    //Prende una sola riga del risultato e la restituisce come array associativo
    //$user["username"], $user["email"] ... quando non trova niente $user == false 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        //Verifica password
        if (password_verify($password, $user['password_hash'])) {
            // Login riuscito
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Redirect all'index
            header("Location: index.php");
            exit;
        } else {
            // Password sbagliata
            $error = "Username o password errati";
        }
    } else {
        // Username non trovato
        $error = "Username o password errati";
    }
}
?>

<html>
<head>
    <title>Login</title>
        <link rel="stylesheet" href="Accesso.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
        <input type="button" class="cancel" value="Annulla">
        <p>Non hai un account? <a href="register.php">Registrati</a></p>
    </form>
</body>
</html>