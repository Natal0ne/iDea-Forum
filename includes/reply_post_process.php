<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Controllo che è sempre meglio mettere (nel caso in cui ci sia un Bypassing del frontend)
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit;
    }

    $conn = connect_db();
    $user_id = $_SESSION["user_id"];

    $thread_id = $_POST["thread_id"];
    $parent_id = !empty($_POST["parent_id"]) ? $_POST["parent_id"] : null; // se per qualche motivo il parent id fosse vuoto, lo setto a null (per evitare errori nelle query)
    $content = $_POST["reply-content"];
    $thread_slug = $_POST["thread_slug"];

    pg_query($conn, "BEGIN");
    $error_msg = null;
    $post_id = null;

    $reply_query = "INSERT INTO posts (thread_id, user_id, parent_id, content, created_at) VALUES ($1, $2, $3, $4, NOW()) RETURNING id";

    $stmt_name = "reply_query";

    if (!pg_prepare($conn, $stmt_name, $reply_query)) {
        $error_msg = "Prepare failed: " . pg_last_error($conn);
    } else {
        $res_post = pg_execute($conn, $stmt_name, array($thread_id, $user_id, $parent_id, $content));

        if($res_post && pg_num_rows($res_post) > 0) {
            $post_row = pg_fetch_assoc($res_post);
            $post_id = $post_row["id"];
        } else {
            $error_msg = "Error inserting reply post.";
        }
    }

    if($error_msg === null && isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {

        $upload_dir = "../uploads/attachments/";

        // Creo la cartella se non esiste
        if(!is_dir($upload_dir)) {
            if(!mkdir($upload_dir, 0777, true)) {
                $error_msg = "Impossible creating upload folder.";
            }
        }

        if($error_msg === null) {
            $query_attach = "INSERT INTO post_attachments (post_id, file_url, file_type, file_size) VALUES ($1, $2, $3, $4)";

            // Preparo la query per gli allegati
            $prep_attach = pg_prepare($conn, "insert_attach_reply", $query_attach);

            if(!$prep_attach) {
                $error_msg = "Error attachments preparation query.";
            } else {
                // Ciclo sugli attachments
                foreach($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {

                    if($error_msg !== null) break; // Interrompo se ho avuto un errore precedente

                    if($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                        $original_name = $_FILES['attachments']['name'][$key];
                        $file_size = $_FILES['attachments']['size'][$key];
                        $file_type = $_FILES['attachments']['type'][$key];

                        // Genero un nome univoco per il file
                        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                        $new_filename = uniqid('img_', true) . '.' . $ext;
                        $dest_path = $upload_dir . $new_filename;
                        $db_path = 'uploads/attachments/' . $new_filename;

                        // Sposto il file e lo salvo nel DB
                        if(move_uploaded_file($tmp_name, $dest_path)) {
                            $res_img = pg_execute($conn, "insert_attach_reply", array($post_id, $db_path, $file_type, $file_size));

                            if(!$res_img) {
                                $error_msg = "Error saving attachment in DB.";
                                // probabilmente da aggiungere la rimozione del file appena caricato con unlink($dest_path)
                            }
                        } else {
                            $error_msg = "Error uploading attachment file.";
                        }
                    }
                }
            }
        }
    }

    if($error_msg === null) {
        pg_query($conn, "COMMIT");
        pg_close($conn);
        header("Location: ../view_thread.php?slug=" . $thread_slug);
        exit;
    } else {
        pg_query($conn, "ROLLBACK");
        pg_close($conn);

        $_SESSION['reply_error'] = $error_msg;

        header("Location: ../index.php");
        exit;
    }
}
?>