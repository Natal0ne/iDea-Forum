<?php require_once 'includes/init.php';

$conn = connect_db();

// recupero lo slug del thread
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// cerco il thread tramite slug
$query_thread = "SELECT t.*, c.name as category_name, u.username as author_name
                FROM threads t
                JOIN categories c
                ON t.category_id = c.id
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.slug = $1";

$res_thread = pg_query_params($conn, $query_thread, array($slug));
$thread = pg_fetch_assoc($res_thread);

// se lo slug non esiste nel DB
if (!$thread) {
    die("Thread not found.");
}

$thread_id = $thread['id'];

// aggiorno le visite totali del thread
pg_query_params($conn, "UPDATE threads SET view_count = view_count + 1 WHERE id = $1", array($thread_id));

// recupero tutti i post relativi al thread
$query_posts = "SELECT p.*, u.username, u.avatar_url, u.role, u.signature
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.thread_id = $1
                ORDER BY p.created_at ASC";

$res_post = pg_query_params($conn, $query_posts, array($thread_id));
$posts = pg_fetch_all($res_post) ?: [];

// recupero allegati per tutti i post del thread
$query_attach = "SELECT pa.*
                FROM post_attachments pa
                JOIN posts p ON pa.post_id = p.id
                WHERE p.thread_id = $1";

$res_attach = pg_query_params($conn, $query_attach, array($thread_id));
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
    <link rel="stylesheet" href="assets/css/style.css">
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

        <?php
        // Separazione del post principale dalle risposte
        // Assumiamo che il primo post dell'array (ordinato per data) sia quello principale 
        // o quello che ha parent_id NULL/0
        $main_post = null;
        $replies = [];

        foreach ($posts as $post) {
            if ($post['parent_id'] === null || $post['parent_id'] == 0) {
                // Se non abbiamo ancora un main post, questo è il primo (il principale)
                if ($main_post === null) {
                    $main_post = $post;
                } else {
                    // Se ci fossero altri post con parent_id 0, li trattiamo come risposte
                    $replies[] = $post;
                }
            } else {
                $replies[] = $post;
            }
        }
        ?>
        <!-- VISUALIZZAZIONE POST PRINCIPALE -->
        <?php if ($main_post): ?>
            <div class="main-post-section">
                <div class="post op" id="post-<?php echo $main_post['id']; ?>">
                    <div class="post-user-info">
                        <div>
                            <?php $avatar_path = !empty($post['avatar_url']) ? $post['avatar_url'] : 'assets/img/default-avatar.png'; ?>
                            <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Avatar" class="user-avatar">
                        </div>
                        <strong>
                            <?php echo htmlspecialchars($main_post['username']); ?>
                        </strong>
                        <small class="badge-op">Author</small>
                        <small>
                            <?php echo htmlspecialchars($main_post['role']); ?>
                        </small>
                    </div>

                    <div class="post-content-attachments">
                        <div class="post-content">
                            <p>
                                <?php echo nl2br(htmlspecialchars($main_post['content'])); ?>
                            </p>
                        </div>

                        <?php if (isset($attachments_by_post[$main_post['id']])): ?>
                            <div class="attachments">
                                <?php foreach ($attachments_by_post[$main_post['id']] as $file): ?>
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
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- CICLO PER MOSTRARE LE RISPOSTE -->
        <div class="replies-container">
            <h3>Replies (<?php echo count($replies); ?>)
            </h3>

            <?php foreach ($replies as $post): ?>
                <div class="post" id="post-<?php echo $post['id']; ?>">
                    <div class="post-user-info">
                        <strong>
                            <?php echo htmlspecialchars($post['username']); ?>
                        </strong>
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
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require_once "includes/footer.php" ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/welcome.js"></script>
</body>

</html>