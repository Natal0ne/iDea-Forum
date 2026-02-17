<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Controllo login (non si sa mai)
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit;
    }

    $conn = connect_db();
    $user_id = $_SESSION["user_id"];

    // Recupero dati input
    $category_id = $_POST['category_id']; // TODO da vedere come settare la categoria (probabilmente mettendo un selettore di categoria nel form)
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Genero slug del thread
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time(); // TODO da vedere come fare meglio

    // Inizio trasmissione query
    pg_query($conn, "BEGIN");

    // Variabile per tracciare errori (inizialmente false)
    $error_msg = null;

    // Inserimento thread
    $query_thread = "INSERT INTO threads (category_id, user_id, title, slug, last_activity_at) VALUES ($1, $2, $3, $4, NOW()) RETURNING id";

    $res_thread = pg_prepare($conn, "insert_thread", $query_thread);

    if($res_thread) {
        $res_thread = pg_execute($conn, "insert_thread", array($category_id, $user_id, $title, $slug));
    }

    // Controllo risultato query
    if(!$res_thread || pg_num_rows($res_thread) == 0) {
        $error_msg = "Error creating thread.";
    } else {
        // se query va a buon fine prendo l'ID
        $thread_row = pg_fetch_assoc($res_thread);
        $thread_id = $thread_row["id"];
    }

    if($error_msg === null) {
        // Inserimento post (contenuto del thread)
        $query_post = "INSERT INTO posts (thread_id, user_id, content, created_at) VALUES ($1, $2, $3, NOW()) RETURNING id";
    }

    $res_post = pg_prepare($conn, "insert_post", $query_post);

    if($res_post) {
        $res_post = pg_execute($conn, "insert_post", array($thread_id, $user_id, $content));
    }

    if(!$res_post || pg_num_rows($res_post) == 0) {
        $error_msg = "Error creating post.";
    } else {
        // se query va a buon fine prendo l'ID
        $post_row = pg_fetch_assoc($res_post);
        $post_id = $post_row["id"];
    }


    // Gestione allegati (solo se fin qui non ho avuto errori)
    if($error_msg === null && isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {

        $upload_dir = "../uploads/attachments/";

        if(!is_dir($upload_dir)) {
            // Errore nel caso in cui non riesce a creare la cartella uploads
            if(!mkdir($upload_dir, 0777, true)) {
                $error_msg = "Impossible creating upload folder.";
            }
        }

        if($error_msg === null) {
            $query_attach = "INSERT INTO post_attachments (post_id, file_url, file_type, file_size) VALUES ($1, $2, $3, $4)";

            $prep_attach = pg_prepare( $conn, "insert_attach", $query_attach);

            if(!$prep_attach) {
                $error_msg = "Error attachments preparation query";
            } else {
                // Ciclo sui file
                foreach($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {

                    // se abbiamo già trovato un errore nel ciclo, interrompo
                    if($error_msg !== null) break; 

                    if($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                        $original_name = $_FILES['attachments']['name'][$key];
                        $file_size = $_FILES['attachments']['size'][$key];
                        $file_type = $_FILES['attachments']['type'][$key];

                        // genero nome univico per evitare sovrascritture
                        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                        $new_filename = uniqid('img_', true) . '.' . $ext;
                        $dest_path = $upload_dir . $new_filename;
                        $db_path = 'uploads/attachments/' . $new_filename;
                        
                        // Provo a spostare il file
                        if(move_uploaded_file($tmp_name, $dest_path)) {
                            $res_img = pg_execute($conn, "insert_attach", array($post_id, $db_path, $file_type, $file_size));
                            if(!$res_img) {
                                $error_msg = "Error saving attachment in DB.";
                                // in questo caso il file è caricato fisicamente ma il db ha fallito
                                // bisognerebbe anche cancellare il file con unlink($dest_path) ma da vedere meglio
                            }
                        } else {
                            $error_msg = "Error uploading attachment.";
                        }
                    }
                }
            }
        }
    }

    if($error_msg === null) {
        // Tutto ok, confermo le modifiche
        pg_query($conn, "COMMIT");
        pg_close($conn);
        header("Location: ../view_thread.php?slug=" . $slug);
        exit;
    } else {
        // Errore da qualche parte, annullo la query
        pg_query($conn, "ROLLBACK");
        pg_close($conn);
        $_SESSION['new_thread_error'] = $error_msg;
        header("Location: ../index.php");
        exit;
    }
}
?>