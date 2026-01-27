<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    
    // Validate inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    // Default category ID (User should have seeded 'General' -> id 1 or we query it)
    $category_id = 1; 

    if (empty($title) || empty($content)) {
        $_SESSION['create_error'] = "Title and body are required.";
        header("Location: ../index.php");
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1. Insert Thread
        // Slug generation (simple version)
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug .= '-' . uniqid();

        $sqlThread = "INSERT INTO threads (category_id, user_id, title, slug) VALUES (:cat, :uid, :title, :slug) RETURNING id";
        $stmtThread = $conn->prepare($sqlThread);
        $stmtThread->execute([
            ':cat' => $category_id,
            ':uid' => $user_id,
            ':title' => $title,
            ':slug' => $slug
        ]);
        $thread_id = $stmtThread->fetchColumn();

        // 2. Insert First Post (Parent ID = 0 per user req, though NULL is usually better for root. User said "id_padre = 0")
        // Note: constraint `fk_post_parent` references posts(id). If we use 0, there must be a post with id 0. 
        // Usually root posts have NULL parent_id. 
        // User said: "viene creato un post con id_padre = 0". 
        // CHECK constraint: FOREIGN KEY (parent_id) REFERENCES posts(id).
        // If posts table is empty, we cannot insert parent_id = 0 unless we force id 0 insert first.
        // SAFE APPROACH: Use NULL for root post. If user insists on 0, schema might fail unless 0 exists.
        // I will use NULL for now as it's standard SQL compliance with the FK constraint shown.
        
        $sqlPost = "INSERT INTO posts (thread_id, user_id, parent_id, content, ip_address) VALUES (:tid, :uid, NULL, :content, :ip) RETURNING id";
        $stmtPost = $conn->prepare($sqlPost);
        $stmtPost->execute([
            ':tid' => $thread_id,
            ':uid' => $user_id,
            ':content' => $content,
            ':ip' => $_SERVER['REMOTE_ADDR']
        ]);
        $post_id = $stmtPost->fetchColumn();

        // 3. Handle File Uploads (Drag & Drop or Standard Input)
        if (!empty($_FILES['attachments'])) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $name = $_FILES['attachments']['name'][$key];
                    $size = $_FILES['attachments']['size'][$key];
                    $type = $_FILES['attachments']['type'][$key];
                    
                    $filename = uniqid('att_') . '_' . basename($name);
                    $dest = $uploadDir . $filename;

                    if (move_uploaded_file($tmpName, $dest)) {
                        $fileUrl = 'uploads/' . $filename;
                        
                        $sqlAtt = "INSERT INTO post_attachments (post_id, file_url, file_type, file_size) VALUES (:pid, :url, :type, :size)";
                        $stmtAtt = $conn->prepare($sqlAtt);
                        $stmtAtt->execute([
                            ':pid' => $post_id,
                            ':url' => $fileUrl,
                            ':type' => $type,
                            ':size' => $size
                        ]);
                    }
                }
            }
        }

        $conn->commit();
        header("Location: ../index.php"); // or thread view
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['create_error'] = "Error creating thread: " . $e->getMessage();
        header("Location: ../index.php");
        exit;
    }
}
?>
