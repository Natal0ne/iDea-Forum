<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit;
    } // POSSIAMO ANCHE LEVARLO DATO CHE IL PULSANTE SI ATTIVA GIÀ DOPO AVER FATTO QUESTO CONTROLLO

    $conn = connect_db();
    $user_id = $_SESSION["user_id"];

    $category_id = $_POST['category_id']; // TODO da vedere come settare la categoria (probabilmente mettendo un selettore di categoria nel form)
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time(); // TODO da vedere come fare meglio

    pg_query($conn, "BEGIN");

    try {

        // Inserimento thread
        $query_thread = "INSERT INTO threads (category_id, user_id, title, slug, last_activity_at) VALUES ($1, $2, $3, $4, NOW()) RETURNING id";

        $res_thread = pg_prepare($conn, "insert_thread", $query_thread);
        $res_thread = pg_execute($conn, "insert_thread", array($category_id, $user_id, $title, $slug));

        if(!$res_thread || pg_num_rows($res_thread) == 0) {
            throw new Exception("Error creating thread");
        }

        $thread_row = pg_fetch_assoc($res_thread);
        $thread_id = $thread_row["id"];

        // Inserimento post (contenuto del thread)
        $query_post = "INSERT INTO posts (thread_id, user_id, content, created_at) VALUES ($1, $2, $3, NOW()) RETURNING id";

        $res_post = pg_prepare($conn, "insert_post", $query_post);
        $res_post = pg_execute($conn, "insert_post", array($thread_id, $user_id, $content));

        if(!$res_post || pg_num_rows($res_post) == 0) {
            throw new Exception("Error creating post");
        }

        $post_row = pg_fetch_assoc($res_post);
        $post_id = $post_row["id"];

        // Gestione allegati
        if(isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = "../uploads/attachments/";
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $query_attach = "INSERT INTO post_attachments (post_id, file_url, file_type, file_size) VALUES ($1, $2, $3, $4)";
            pg_prepare( $conn, "insert_attach", $query_attach);

            foreach($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {

                if($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $original_name = $_FILES['attachments']['name'][$key];
                    $file_size = $_FILES['attachments']['size'][$key];
                    $file_type = $_FILES['attachments']['type'][$key];

                    // genero nome univico per evitare sovrascritture
                    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                    $new_filename = uniqid('img_', true) . '.' . $ext;
                    $dest_path = $upload_dir . $new_filename;
                    $db_path = 'uploads/attachments/' . $new_filename;

                    if(move_uploaded_file($tmp_name, $dest_path)) {
                        $res_img = pg_execute($conn, "insert_attach", array($post_id, $db_path, $file_type, $file_size));
                        if(!$res_img) throw new Exception("Error saving attachment in database");
                    }
                }
            }
        }

        // Se va tutto bene confermo i dati nel db
        pg_query( $conn,"COMMIT");
        header("Location: ../view_thread.php?slug=" . $slug);
    } catch (Exception $e) {
        // se ho un errore annullo tutto
        pg_query( $conn,"ROLLBACK");
        $_SESSION['new_thread_error'] = $e->getMessage();
        //header("Location: ../index.php?error=1");
    }
    pg_close($conn);
    exit;
}