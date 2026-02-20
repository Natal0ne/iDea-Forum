<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

$conn = connect_db();

$user = $_SESSION["user_id"] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    $showSignature = $_POST['showSignature'] ?? "No";
    $location = $_POST['location'];
    $website = $_POST['website'];
    $signature = $_POST['signature'];
    $language = $_POST['language'];

    $stmt_name = "updateUsers";

    //Aggiorna dati nella tabella users
    $query = "UPDATE users SET username = $1, bio = $2, location = $3, website = $4, signature = $5 WHERE id = $6";

    if(!pg_prepare($conn, $stmt_name, $query)){
        die("Prepare failed: ".pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($username, $bio, $location, $website,  $signature, $user));

    if(!$result){
        $_SESSION['profile_settings_error'] = "Username already used";
        header("Location: ../index.php");

        //die("Execute failed: " . pg_last_error());

    }

    $stmt_name = "insertIntoUserSettings";

    //Inserisci dati nella tabella user_settings se non ci sono
    //Nel caso in cui ci siano allora fai l'update
    $query = "INSERT INTO user_settings (user_id, language, show_signatures) VALUES ($1, $2, $3)
    ON CONFLICT (user_id) DO UPDATE SET language = EXCLUDED.language, show_signatures = EXCLUDED.show_signatures";

    
    $stmt_name = "upsert_user_settings";

    if (!pg_prepare($conn, $stmt_name, $query)) {
        die("Prepare failed: " . pg_last_error());
    }

    $result = pg_execute($conn, $stmt_name, array($user, $language, $showSignature));

    if (!$result) {
        die("Execute failed: " . pg_last_error());
    }

}

//GESTIONE CAMBIO AVATAR

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {

    $targetDir = "../assets/img/";

    $fileExtension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
    $fileName = uniqid("avatar_", true) . "." . $fileExtension;
    $targetFile = $targetDir . $fileName;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowed)) {

        $check = getimagesize($_FILES["avatar"]["tmp_name"]);

        if ($check !== false) {

            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {

                $avatarPath = "assets/img/" . $fileName;

                pg_prepare($conn, "change_img", "UPDATE users SET avatar_url = $1 WHERE id = $2");
                pg_execute($conn, "change_img", array($avatarPath, $_SESSION['user_id']));

                $_SESSION['user_avatar_url'] = $avatarPath;
            } else {
                die("Errore nel salvataggio file");
            }
        }
    }
}



header("Location: ../index.php");
pg_close($conn);
exit;
?>
