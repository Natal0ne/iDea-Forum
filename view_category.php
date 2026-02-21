<?php 

require_once 'includes/init.php';

if (!isset($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = $_GET['slug'];

$conn = connect_db();

$stmt_name = "view_category_query";
$view_category_query = "SELECT * FROM categories WHERE slug = $1";

if (!pg_prepare($conn, $stmt_name, $view_category_query)) {
    die("Prepare failed: " . pg_last_error());
}

$result = pg_execute($conn, $stmt_name, array($slug));

if (!($result && pg_num_rows($result) > 0)) {
    $_SESSION['view_category_error'] = "No category found";
    header("Location: ../index.php");
    pg_close($conn);
    exit;
}

$category = pg_fetch_assoc($result);


$threads_of_category_query = "SELECT 
                                t.id, 
                                t.title, 
                                t.slug AS thread_slug,
                                c.name AS category_name, 
                                u.username AS author_name,
                                u.avatar_url,
                                t.reply_count,
                                t.view_count,
                                t.is_sticky,
                                t.is_locked,
                                t.created_at,
                                t.last_activity_at
                            FROM threads t
                            JOIN categories c ON t.category_id = c.id
                            LEFT JOIN users u ON t.user_id = u.id
                            WHERE c.id = {$category['id']} 
                            ORDER BY t.is_sticky DESC, t.last_activity_at DESC;";

$result = pg_query($conn, $threads_of_category_query);

if (!($result && pg_num_rows($result) > 0)) {
    $_SESSION['view_category_error'] = "No threads found";
    header("Location: ../index.php");
    pg_close($conn);
    exit;
}

$threads = pg_fetch_all($result);


$query = "SELECT * FROM users WHERE last_active_at > NOW() - INTERVAL '5 minutes' ";

$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {

    $online_users = pg_fetch_all($result);

}

$query = "SELECT threads.*, users.username, users.avatar_url
          FROM threads 
          LEFT JOIN users ON threads.user_id = users.id 
          ORDER BY threads.created_at DESC 
          LIMIT 30";

$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {

    $latest_threads = pg_fetch_all($result);

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
    <link rel="stylesheet" href="assets/css/view_category.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>

    <div class="bg-gradient"></div>

    <?php require_once 'includes/navbar.php' ?>

    <div class="content">
        <div class="category">
            
            <div class="category-header">
                <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
            </div>

            <div class="thread-list">
                <?php foreach ($threads as $thread): ?>

                <div class="thread">

                    <div class="thread-main">

                        <img src="<?php echo htmlspecialchars($thread['avatar_url']); ?>" alt="Avatar" class="author-avatar">
                        
                        <div>
                            <a href="<?php echo "view_thread.php?slug=" . $thread['thread_slug'] ?>" ><h3> <?php echo $thread['title']; ?> </h3></a>
                            <p>Created by <strong><?php echo $thread['author_name'] ?></strong></p>
                        </div>
                        
                    </div>

                    <div class="thread-stats">
                        <div class="stat">
                            Replies: <?php echo $thread['reply_count'] ?>
                        </div>
                        <div class="stat">
                            Views: <?php echo $thread['view_count'] ?>
                        </div>
                    </div>

                </div>

                <?php endforeach; ?>

            </div>
                        
        </div>

        <div class="side">
            <div id="members" class="online-users">
                <div class="side-title">
                    <h3>online users</h3>
                </div>
                <div class="users">
                    <?php if (isset($online_users)): ?>
                        <?php foreach ($online_users as $u): ?>
                        <div class="user <?php echo $u['role']; ?>">
                            <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                                <img src="<?php echo $u['avatar_url']; ?>" alt="Avatar" class="avatar">
                            </div>
                            <p><?php echo $u['username']; ?></p>
                        </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <p> 0 </p>
                    <?php endif; ?>
                </div>
            </div>
            <div id="threads" class="latest-threads">
                <h3>latest threads</h3>
                <div class="threads">
                    <?php if (isset($latest_threads)): ?>
                        <?php foreach ($latest_threads as $t): ?>
                            <div class="thread">
                                <a href="<?php echo "view_thread.php?slug=" . $t['slug'] ?>"><?php echo $t['title'] ?></a>
                            </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <p> Query Error </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "includes/footer.php" ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/modal.js"></script>
</body>

</html>