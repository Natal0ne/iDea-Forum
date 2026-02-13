<?php require_once 'includes/init.php';

if (!isset($_GET['slug'])) {  
    header("Location: index.php");
    exit;
}

$slug = $_GET['slug'];

$conn = connect_db();

/* Recupero il thread */
$stmt_name = "view_thread_query";
$view_thread_query = "SELECT t.*, c.name as category_name, u.username as author_name 
                        FROM threads t
                        JOIN categories c ON t.category_id = c.id
                        LEFT JOIN users u ON t.user_id = u.id
                        WHERE t.slug = $1";

if (!pg_prepare($conn, $stmt_name, $view_thread_query)) {
    die("Prepare failed: " . pg_last_error());
}

$result = pg_execute($conn, $stmt_name, array($slug));

if (!($result && pg_num_rows($result) > 0)) {
    $_SESSION['view_thread_error'] = "No thread found";
    header("Location: ../index.php");
    pg_close($conn);
    exit;
}

$thread = pg_fetch_assoc($result);

/* Recupero i post associati al thread */   //DA CAPIRE BENE COME FUNZIONA MA SEMPLIFICA LA VITA DI UN BOTTO
$posts_CTE_query = "WITH RECURSIVE post_tree AS (
                        SELECT 
                            *,  
                            0 as depth, 
                            ARRAY[id] as path
                        FROM posts 
                        WHERE thread_id = {$thread['id']} AND parent_id IS NULL

                        UNION ALL

                        SELECT 
                            p.*,
                            pt.depth + 1, 
                            pt.path || p.id
                        FROM posts p
                        JOIN post_tree pt ON p.parent_id = pt.id
                        WHERE p.thread_id = {$thread['id']}
                    )
                    SELECT pt.*, u.*
                    FROM post_tree pt
                    LEFT JOIN users u ON pt.user_id = u.id
                    ORDER BY pt.path";

$result = pg_query($conn, $posts_CTE_query);

if (!($result && pg_num_rows($result) > 0)) {
    $_SESSION['view_thread_error'] = "No posts found";
    header("Location: ../index.php");
    pg_close($conn);
    exit;
}

$posts = pg_fetch_all($result);


/* Aggiorno le visualizzazione del thread */
$update_views_query = "UPDATE threads SET view_count = view_count + 1 WHERE id = {$thread['id']}";

pg_query($conn, $update_views_query);








// recupero allegati per tutti i post del thread
$query_attach = "SELECT pa.*
                FROM post_attachments pa
                JOIN posts p ON pa.post_id = p.id
                WHERE p.thread_id = $1";

$res_attach = pg_query_params($conn, $query_attach, array($thread['id']));
$all_attachments = pg_fetch_all($res_attach) ?: [];

// organizzo allegati in un array associativo [post_id => [file1, file2]]

$attachments_by_post = [];

foreach ($all_attachments as $file) {
    $attachments_by_post[$file["post_id"]][] = $file;
}

pg_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDea</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/view_thread.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>

    <div class="bg-gradient"></div>

    <?php require_once 'includes/sign_up.php' ?>
    <?php require_once 'includes/sign_in.php' ?>
    <?php require_once 'includes/new_thread.php' ?>
    <?php require_once 'includes/navbar.php' ?>
    <?php require_once 'includes/image_modal.php' ?>


    <div class="thread-content">

        <div class="thread-header">
            <h1 style="margin: 0px">
                <?php echo htmlspecialchars($thread['title']); ?>
            </h1>
            <p>Categoria:
                <?php echo htmlspecialchars($thread['category_name']); ?>
            </p>
        </div>

        <?php foreach ($posts as $post): ?>
            <div class="post-wrapper">
                    <div class="post <?php if ($post['depth'] == 0): echo 'op'; endif ?>" id="post-<?php echo $post['id']; ?>" style="margin-left: <?php echo $post['depth'] * 40; ?>px; ">
                        <div class="post-user-info">
                            <div class="user-avatar">
                                <img src="<?php echo htmlspecialchars($post['avatar_url']); ?>" alt="Avatar" class="user-avatar">
                            </div>
                            <strong>
                                <?php echo htmlspecialchars($post['username']); ?>
                            </strong>
                            <?php if ($post['depth'] == 0): echo '<small class="badge-op">Author</small>'; endif?>
                            <small>
                                <?php echo htmlspecialchars($post['role']); ?>
                            </small>
                        </div>

                        <div class="post-content-attachments">
                            <div class="post-content">
                                <p>
                                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                                </p>
                            </div>

                            <?php if (isset($attachments_by_post[$post['id']])): ?>
                                <div class="attachments">
                                    <?php foreach ($attachments_by_post[$post['id']] as $file): ?>
                                        <div class="file-item">
                                            <?php if (strpos($file['file_type'], 'image') !== false): ?>
                                                <img src="<?php echo $file['file_url']; ?>" class="post-image">
                                            <?php else: ?>
                                                <a href="<?php echo $file['file_url']; ?>" class="post_attachment">Download attachment</a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="reply-button-div">
                                <a href="javascript:void(0)" class="reply-link" data-target="reply-box-<?php echo $post['id']; ?>" data-username="<?php echo htmlspecialchars($post['username']); ?>">
                                    <span style="position: relative; top: 2px">&#8617;</span>
                                    <span style="display: inline-block;">Reply</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="reply-box-<?php echo $post['id']; ?>" class="reply-form-container hidden">
                        <?php 
                            $parent_id = $post['id']; 
                            include "includes/reply_post.php"; 
                        ?>
                    </div>
            </div>
        <?php endforeach; ?>
                    
    </div>

    <?php require_once "includes/footer.php" ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/welcome.js"></script>
    <script src="assets/js/reply_handler.js"></script>
</body>

</html>