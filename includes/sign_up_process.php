<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //$id = $_POST['user_id'];
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $conn = connect_db();

    /* Controllo le Unique del db */

    $stmt_name = "check_query";

    $query = "SELECT username, email FROM users WHERE username = $1 OR email = $2";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($username, $email));

    if ($result && pg_num_rows($result) > 0) {

        $row = pg_fetch_assoc($result);

        if ($row['username'] === $username && $row['email'] === $email) {

            $_SESSION['sign_up_errors'] = 'Username and email already used';

        } elseif ($row['username'] === $username) {

            $_SESSION['sign_up_errors'] = 'Username already used';

        } elseif ($row['email'] === $email) {

            $_SESSION['sign_up_errors'] = 'Email alredy used';
            
        }

        header(header: "Location: ../index.php");
        pg_close($conn);
        exit;
    }

    /* Registrazione vera e propria */

    $stmt_name = "sign_up_query";

    $query = "INSERT INTO users (username, email, password_hash) VALUES ($1, $2, $3) RETURNING *";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($username, $email, $password_hash));


    /*Per salvare id in user_settings -> user_id*/
    $user = pg_fetch_assoc($result);
    $user_id = $user['id'];
    $settings_query = "
    INSERT INTO user_settings (user_id)
    VALUES ($1)
";
    $settings_result = pg_query_params(
    $conn,
    $settings_query,
    array($user_id) 
    );
    if (!$settings_result) {
    pg_query($conn, "ROLLBACK");
    die("User settings insert failed: " . pg_last_error($conn));
}

pg_query($conn, "COMMIT");

    if ($result) {
        $user = pg_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_avatar_url'] = $user['avatar_url'];
        $_SESSION['role'] = $user['role'];  
        header("Location: ../index.php");
        pg_close($conn);
        exit;      
    }
   
          
}